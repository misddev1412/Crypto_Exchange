<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\Deposit\DepositProcessJob;
use App\Jobs\Withdrawal\WithdrawalConfirmationJob;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Override\Logger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CoinpaymentsIpnController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            if (!$request->has('currency')) {
                throw new Exception("Invalid request.");
            }

            //Get Coinpayments API
            $api = app(API_COINPAYMENT, [$request->get('currency')]);

            //validate IPN request and if pass process the request in queue
            if ($validateIpnData = $api->validateIpn($request)) {
                if ($validateIpnData['type'] === TRANSACTION_DEPOSIT) {
                    DepositProcessJob::dispatch($validateIpnData);
                }
                if ($validateIpnData['type'] === TRANSACTION_WITHDRAWAL) {
                    $withdrawal = WalletWithdrawal::where('txn_id', $validateIpnData['id'])
                        ->where('status', STATUS_PENDING)
                        ->first();
                    if (!empty($withdrawal)) {
                        WithdrawalConfirmationJob::dispatch($withdrawal, Arr::only($validateIpnData, ['status', 'txn_id']));
                    }
                }
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][Coinpayments][CoinpaymentsIpnController]");
        }

        return [];
    }
}
