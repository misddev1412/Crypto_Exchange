<?php

namespace App\Http\Controllers\Withdrawal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Withdrawal\WithdrawalRequest;
use App\Jobs\Withdrawal\WithdrawalCancelJob;
use App\Jobs\Withdrawal\WithdrawalProcessJob;
use App\Models\BankAccount\BankAccount;
use App\Models\Wallet\Wallet;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Override\Logger;
use App\Services\Core\DataTableService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use ReflectionClass;

class UserWithdrawalController extends Controller
{
    public function index(Wallet $wallet): View
    {
        $searchFields = [
            ['id', __('Reference ID')],
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['bank_name', __('Bank'), 'bankAccount'],
            ['txn_id', __('Transaction ID')],
            ['symbol', __('Wallet')],
        ];

        $orderFields = [
            ['id', __('Reference ID')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['status', __('Status'), transaction_status()],
        ];

        $queryBuilder = $wallet->withdrawals()
            ->with("bankAccount")
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['wallet'] = $wallet;
        $data['title'] = __(':coin Withdrawal History', ['coin' => $wallet->symbol]);

        return view('withdrawal.user.index', $data);
    }

    public function create(Wallet $wallet)
    {
        $data['title'] = __('Withdraw :coin', ['coin' => $wallet->symbol]);
        $data['wallet'] = $wallet;

        if ($data['wallet']->coin->type == COIN_TYPE_FIAT) {
            $data['apis'] = Arr::only(fiat_apis(), $wallet->coin->api['selected_apis'] ?? []);
            $data['bankAccounts'] = BankAccount::where('user_id', Auth::id())
                ->where('is_active', ACTIVE)
                ->where('is_verified', VERIFIED)
                ->pluck('bank_name', 'id');
            return view('withdrawal.user.create', $data);
        }
        return view("withdrawal.user.create", $data);
    }

    public function store(WithdrawalRequest $request, Wallet $wallet)
    {
        if ($wallet->coin->withdrawal_status == INACTIVE) {
            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __("The withdrawal service is currently disabled. Please try sometime later."));
        }

        if (bccomp($request->get('amount'), $wallet->primary_balance) > 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __("You don't have enough balance to withdrawal!. Your current balance is :amount", [
                        'amount' => $wallet->primary_balance,
                    ])
                );
        }

        $api = $wallet->coin->getAssociatedApi($request->get('api'));

        if (is_null($api)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __("Payment service is currently not available. Please try sometime later."));
        }

        if ($request->has('address') && !$api->validateAddress($request->get('address'))) {
            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __("Invalid given address."));
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
            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __("Unable to withdraw amount."));
        }

        DB::commit();

        return redirect()
            ->route('user.wallets.withdrawals.index', ['wallet' => $wallet->symbol])
            ->with(RESPONSE_TYPE_SUCCESS, __("Your withdrawal has been placed successfully."));
    }

    public function show(Wallet $wallet, WalletWithdrawal $withdrawal)
    {
        $wallet->load('coin');

        if (!is_null($withdrawal->bank_account_id)) {
            $withdrawal->load('user.profile', 'bankAccount.country');
        }

        $data['wallet'] = $wallet;
        $data['withdrawal'] = $withdrawal;
        $data['title'] = __("Withdrawal Details");
        return view('withdrawal.user.show', $data);
    }

    public function confirmation(Wallet $wallet, WalletWithdrawal $withdrawal, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, __("Link is expired!!."));
        } else if (!Auth::check()) {
            abort(401, __("You are not authorized for this action."));
        } else if (Auth::check() && Auth::id() != $withdrawal->user_id) {
            abort(401, __("You are not authorized for this action."));
        } else if ($wallet->id != $withdrawal->wallet_id) {
            abort(401, __("You are not authorized for this action."));

        }

        $message = __("Withdrawal has been confirmed successfully. It will be processed shortly.");
        if (settings('is_admin_approval_required')) {
            $withdrawal->update(['status' => STATUS_REVIEWING]);
            $message = __("Withdrawal has been confirmed successfully. It will require admin approval for further process.");
        } else {
            if ($withdrawal->update(['status' => STATUS_PENDING])) {
                WithdrawalProcessJob::dispatch($withdrawal);
            } else {
                abort(404, __("Failed to confirm withdrawal."));
            }
        }

        return redirect()
            ->route('user.wallets.withdrawals.show', ['wallet' => $withdrawal->symbol, 'withdrawal' => $withdrawal->id])
            ->with(RESPONSE_TYPE_SUCCESS, $message);
    }

    public function destroy(Wallet $wallet, WalletWithdrawal $withdrawal)
    {
        if ($withdrawal->update(['status' => STATUS_CANCELING])) {
            WithdrawalCancelJob::dispatch($withdrawal);
            return redirect()
                ->route('user.wallets.withdrawals.index', $withdrawal->symbol)
                ->with(RESPONSE_TYPE_SUCCESS, __("The withdrawal cancellation will be processed shortly."));
        }
        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_SUCCESS, __("Failed to cancel withdrawal."));
    }
}
