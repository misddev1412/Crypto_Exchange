<?php

namespace App\Jobs\Webhook;

use App\Jobs\Deposit\DepositProcessJob;
use App\Override\Logger;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ValidateBitcoinIpnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $currency;

    public function __construct(string $currency, array $data)
    {
        $this->data = $data;
        $this->currency = $currency;
    }

    public function handle()
    {
        //Get Bitcoin API
        $api = app(API_BITCOIN, [$this->currency]);

        //Validate IPN and process deposit one by one in queue
        if ($transactions = $api->validateIpn($this->data['txn_id'])) {

            $status = $transactions['confirmations'] ? STATUS_COMPLETED : STATUS_PENDING;

            foreach ($transactions['details'] as $transaction) {
                if ($transaction['category'] === 'receive') {
                    $depositData = [
                        'address' => $transaction['address'],
                        'amount' => sprintf('%.8f', $transaction['amount']),
                        'txn_id' => $transactions['txid'],
                        'symbol' => $this->currency,
                        'status' => $status,
                        'type' => TRANSACTION_DEPOSIT,
                        'api' => API_BITCOIN,
                    ];

                    DepositProcessJob::dispatch($depositData);
                }
            }
        }
    }

    public function failed(Exception $exception)
    {
        Logger::error($exception, "[FAILED][ValidateBitcoinIpnJob]");
    }
}


