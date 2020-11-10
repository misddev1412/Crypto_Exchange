<?php

namespace App\Http\Controllers\Api\Withdrawal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Withdrawal\WithdrawalRequest;
use App\Jobs\Withdrawal\WithdrawalCancelJob;
use App\Models\Wallet\Wallet;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Override\Logger;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class UserWithdrawalController extends Controller
{
    public function index(Wallet $wallet)
    {
        $withdrawals = $wallet->withdrawals()
            ->with("bankAccount")
            ->orderBy('created_at', 'desc')
            ->paginate();

        return [
            RESPONSE_STATUS_KEY => true,
            RESPONSE_DATA => $withdrawals,
        ];
    }

    public function store(WithdrawalRequest $request, Wallet $wallet)
    {
        if ($wallet->coin->withdrawal_status == INACTIVE) {
            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("The withdrawal service is currently disabled. Please try sometime later.")
            ], 405);
        }

        if (bccomp($request->get('amount'), $wallet->primary_balance) > 0) {
            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("You don't have enough balance to withdrawal!. Your current balance is :amount", [
                    'amount' => $wallet->primary_balance])
            ], 400);
        }

        $api = $wallet->coin->getAssociatedApi($request->get('api'));

        if (is_null($api)) {
            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Payment service is currently not available. Please try sometime later.")
            ], 406);
        }

        if ($request->has('address') && !$api->validateAddress($request->get('address'))) {
            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Invalid given address.")
            ], 406);
        }

        $params = [
            'user_id' => Auth::id(),
            'wallet_id' => $wallet->id,
            'symbol' => $wallet->symbol,
            'address' => $request->get('address'),
            'amount' => $request->get('amount'),
            'system_fee' => calculate_withdrawal_system_fee(
                $request->get('amount'),
                $wallet->coin->withdrawal_fee,
                $wallet->coin->withdrawal_fee_type
            ),
            'api' => (new ReflectionClass($api))->getShortName(),
            'status' => STATUS_PENDING,
        ];

        if ($request->has('api') && $request->get('api') === API_BANK) {
            $params['bank_account_id'] = $request->get('bank_account_id');
            $params['status'] = STATUS_REVIEWING;
        }

        if (settings('is_email_confirmation_required')) {
            $params['status'] = STATUS_EMAIL_SENT;
        } elseif (settings('is_admin_approval_required')) {
            $params['status'] = STATUS_REVIEWING;
        }

        DB::beginTransaction();
        try {
            if (!$wallet->decrement('primary_balance', $request->get('amount'))) {
                throw new Exception(__('Failed to update wallet.'));
            }

            $withdrawal = WalletWithdrawal::create($params);

            if (empty($withdrawal)) {
                throw new Exception(__('Failed to create withdrawal.'));
            }
        } catch (Exception $exception) {
            DB::rollBack();
            Logger::error($exception, "[FAILED][Withdrawal][store]");

            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Unable to withdraw amount.")
            ], 400);
        }

        DB::commit();

        return response()->json([
            RESPONSE_STATUS_KEY => true,
            RESPONSE_MESSAGE_KEY => __("Your withdrawal has been placed successfully.")
        ], 200);
    }

    public function destroy(Wallet $wallet, WalletWithdrawal $withdrawal)
    {
        if ($withdrawal->update(['status' => STATUS_CANCELING])) {
            WithdrawalCancelJob::dispatch($withdrawal);

            return response()->json([
                RESPONSE_STATUS_KEY => true,
                RESPONSE_MESSAGE_KEY => __("The withdrawal cancellation will be processed shortly.")
            ], 200);
        }

        return response()->json([
            RESPONSE_STATUS_KEY => false,
            RESPONSE_MESSAGE_KEY => __("Failed to cancel withdrawal.")
        ], 400);
    }
}
