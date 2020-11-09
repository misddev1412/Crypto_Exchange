<?php

namespace App\Override\Api;

use App\Override\Logger;
use Exception;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class BitcoinForkedApi
{
    private $currency;
    private $scheme;
    private $host;
    private $port;
    private $user;
    private $password;

    public function __construct($currency)
    {
        $this->currency = $currency;
        $this->loadConfiguration();
    }

    private function loadConfiguration(): void
    {
        $currency = strtolower($this->currency);
        $this->scheme = settings("{$currency}_api_scheme") ?? "";
        $this->host = settings("{$currency}_api_host") ?? "";
        $this->port = settings("{$currency}_api_port") ?? "";
        $this->user = settings("{$currency}_api_rpc_user") ?? "";
        $this->password = settings("{$currency}_api_rpc_password") ?? "";
    }

    public function generateAddress(string $label = ""): array
    {
        try {
            $response = $this->call("getnewaddress", [$label]);
            $response->throw();
            if ($response->successful()) {
                return [
                    'error' => 'ok',
                    'result' => [
                        'address' => $response['result']
                    ],
                ];
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][BitcoinForkedApi][generateAddress]");
        }
        return ['error' => 'Failed to generate address.'];
    }

    private function call(string $method, array $params = []): Response
    {
        try {
            $response = Http::timeout(10)
                ->withBasicAuth($this->user, $this->password)
                ->accept('application/json')
                ->contentType('application/json-rpc')
                ->post($this->getUrl(), [
                    'id' => 1,
                    'jsonrpc' => '2.0',
                    'method' => $method,
                    'params' => $params
                ]);
        } catch (Exception $exception) {
            return new Response(new GuzzleResponse($exception->getCode()));
        }
        return $response;
    }

    private function getUrl(): string
    {
        return $this->scheme . "://" . $this->host . ":" . $this->port;
    }

    public function sendToAddress(string $address, float $amount): array
    {
        try {
            $response = $this->call("sendtoaddress", ["address" => $address, "amount" => $amount, "subtractfeefromamount" => true]);
            $response->throw();
            if ($response->successful()) {
                return [
                    RESPONSE_STATUS_KEY => true,
                    RESPONSE_DATA => [
                        'status' => STATUS_COMPLETED,
                        'txn_id' => $response['result'],
                    ]
                ];
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][BitcoinForkedApi][sendToAddress]");
        }
        return [
            RESPONSE_STATUS_KEY => false
        ];
    }

    public function getBalance(string $wallet = ""): float
    {
        try {
            $response = $this->call("getbalance", [$wallet]);
            $response->throw();
            if ($response->successful()) {
                return $response['result'];
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][BitcoinForkedApi][getBalance]");
        }
        return 0;
    }

    public function validateAddress(string $address): bool
    {
        try {
            $response = $this->call("validateaddress", [$address]);
            $response->throw();
            if ($response->successful()) {
                return $response['result']['isvalid'];
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][BitcoinForkedApi][validateAddress]");
        }
        return false;
    }

    public function validateIpn(string $txnId)
    {
        $response = $this->getTransaction($txnId);
        if ($response['error'] === 'ok') {
            return $response['result'];
        }
        return false;
    }

    public function getTransaction(string $txnId): array
    {
        try {
            $response = $this->call("gettransaction", ["txid" => $txnId]);
            $response->throw();
            if ($response->successful()) {
                return [
                    'error' => 'ok',
                    'result' => $response['result']
                ];
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][BitcoinForkedApi][getTransaction]");
        }
        return ['error' => 'No transactions found.'];
    }
}
