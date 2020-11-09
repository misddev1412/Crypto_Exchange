<?php

namespace App\Override\Api;

use App\Override\Logger;
use Exception;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CoinpaymentsApi
{
    private $privateKey;
    private $publicKey;
    private $merchantID;
    private $ipnSecret;
    private $ipnUrl;
    private $ch;
    private $currency;

    public function __construct($currency)
    {
        $this->currency = $currency;
        $this->loadConfiguration();
    }

    private function loadConfiguration(): void
    {
        $keys = ['coinpayments_private_key', 'coinpayments_public_key', 'coinpayments_merchant_id', 'coinpayments_ipn_secret', 'coinpayments_ch'];
        $configuration = settings($keys, true);
        $this->privateKey = $configuration['coinpayments_private_key'];
        $this->publicKey = $configuration['coinpayments_public_key'];
        $this->merchantID = $configuration['coinpayments_merchant_id'];
        $this->ipnSecret = $configuration['coinpayments_ipn_secret'];
        $this->ipnUrl = url('api/ipn/coinpayments');
        $this->ch = empty($configuration['coinpayments_ch']) ? null : $configuration['coinpayments_ch'];
    }

    public function generateAddress(string $label = ""): array
    {
        try {
            $response = $this->call("get_callback_address", ["currency" => $this->currency, 'ipn_url' => $this->ipnUrl]);
            $response->throw();
            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][CoinpaymentsApi][generateAddress]");
        }
        return ['error' => 'Failed to generate address.'];
    }

    private function call(string $method, array $params = []): Response
    {
        $params['version'] = 1;
        $params['cmd'] = $method;
        $params['key'] = $this->publicKey;
        $params['format'] = 'json';

        $hmac = hash_hmac('sha512', http_build_query($params), $this->privateKey);
        try {
            $response = Http::timeout(10)
                ->asForm()
                ->withHeaders([
                    "HMAC" => $hmac
                ])
                ->post($this->getUrl(), $params);
        } catch (Exception $exception) {
            return new Response(new GuzzleResponse($exception->getCode()));
        }
        return $response;
    }

    private function getUrl(): string
    {
        return "https://www.coinpayments.net/api.php";
    }

    public function sendToAddress(string $address, float $amount): array
    {
        try {
            $response = $this->call("create_withdrawal", [
                "address" => $address,
                "amount" => $amount,
                "currency" => $this->currency,
                "auto_confirm" => 1,
                "ipn_url" => $this->ipnUrl
            ]);

            $response->throw();

            if ($response->successful()) {
                $response = $response->json();
                if ($response['error'] === 'ok') {
                    return [
                        RESPONSE_STATUS_KEY => true,
                        RESPONSE_DATA => [
                            'status' => $this->getIpnStatus($response['result']['status']),
                            'txn_id' => $response['result']['txn_id'] ?? $response['result']['id'],
                        ]
                    ];
                }
                throw new Exception($response['error']);
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][CoinpaymentsApi][sendToAddress]");
            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_DATA => []
            ];
        }
        return ['error' => 'Failed to send.'];
    }

    public function getIpnStatus(int $statusCode): string
    {
        if ($statusCode >= 100 || $statusCode == 2) {
            $status = STATUS_COMPLETED;
        } elseif ($statusCode < 0) {
            $status = STATUS_FAILED;
        } else {
            $status = STATUS_PENDING;
        }
        return $status;
    }

    public function getBalance(string $wallet = ""): float
    {
        try {
            $response = $this->call("balances");
            $response->throw();
            if ($response->successful()) {
                return isset($response['result'][$this->currency]['balancef']) ? $response['result'][$this->currency]['balancef'] : 0;
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][CoinpaymentsApi][getBalance]");
        }
        return 0;
    }

    public function getTransaction(string $txnId): array
    {
        try {
            $response = $this->call("get_tx_info", ["txid" => $txnId]);
            $response->throw();
            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][CoinpaymentsApi][getTransaction]");
        }
        return ['error' => 'No transaction found.'];
    }

    public function validateAddress(string $address): bool
    {
        return !empty($address);
    }

    public function validateIpn(Request $request): array
    {
        try {
            if (!$request->has(["ipn_mode", "merchant", "status", "status_text"])) {
                throw new Exception("Insufficient POST data provided.");
            }

            if ($request->get('ipn_mode') == 'hmac') {
                $hmac = hash_hmac("sha512", file_get_contents('php://input'), $this->ipnSecret);
                if ($request->server('HTTP_HMAC') !== $hmac) {
                    throw new Exception("Invalid HMAC provided.");
                }
                if ($request->get('merchant') !== $this->merchantID) {
                    throw new Exception("Invalid merchant ID provided.");
                }
            } else {
                throw new Exception("Invalid IPN mode provided.");
            }
            $amount = sprintf('%.8f', $request->get('amount'));
            $fee = sprintf('%.8f', $request->get('fee', 0));
            $data = [
                'symbol' => $request->get('currency'),
                'amount' => bcsub($amount, $fee),
                'address' => $request->get('address'),
                'id' => $request->get('id'),
                'txn_id' => $request->get('txn_id'),
                'type' => $request->get('ipn_type'),
                'status' => $this->getIpnStatus($request->get('status')),
                'api' => API_COINPAYMENT,
            ];

        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][CoinpaymentsApi][validateIpn]");
            return [];
        }
        return $data;
    }

}
