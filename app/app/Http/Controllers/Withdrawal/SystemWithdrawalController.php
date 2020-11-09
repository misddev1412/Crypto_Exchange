<?php

namespace App\Http\Controllers\Withdrawal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Withdrawal\WithdrawalRequest;
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

class SystemWithdrawalController extends Controller
{
    public function index(Wallet $wallet): View
    {
        $searchFields = [
            ['wallet_withdrawals.id', __('Reference ID')],
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['txn_id', __('Transaction ID')],
            ['symbol', __('Wallet')],
        ];

        $orderFields = [
            ['wallet_withdrawals.id', __('Reference ID')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['wallet_withdrawals.status', __('Status'), transaction_status()],
        ];

        $queryBuilder = $wallet->withdrawals()
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['wallet'] = $wallet;
        $data['title'] = __(':coin Withdrawal History', ['coin' => $wallet->symbol]);

        return view('withdrawal.admin.history.index', $data);
    }

    public function create(Wallet $wallet)
    {
        $data['title'] = __('Withdraw :coin', ['coin' => $wallet->coin->name]);
        $data['wallet'] = $wallet;

        if ($data['wallet']->coin->type == COIN_TYPE_FIAT) {
            $data['apis'] = Arr::only(fiat_apis(), $wallet->coin->api['selected_apis'] ?? []);
            $data['bankAccounts'] = BankAccount::where('user_id', Auth::id())
                ->where('is_active', ACTIVE)
                ->where('is_verified', VERIFIED)
                ->pluck('bank_name', 'id');
        }

        return view("withdrawal.admin.create", $data);
    }

    public function store(WithdrawalRequest $request, $wallet)
    {
        if ($wallet->coin->type === COIN_TYPE_CRYPTO) {
            $wallet->getService();
            if (is_null($wallet->service)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(RESPONSE_TYPE_ERROR, __("Unable to withdraw amount."));
            } else if (!$wallet->service->validateAddress($request->get('address'))) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(RESPONSE_TYPE_ERROR, __("Invalid address."));
            }
        }

        $params = [
            'user_id' => Auth::id(),
            'wallet_id' => $wallet->id,
            'symbol' => $wallet->symbol,
            'address' => $request->get('address'),
            'amount' => $request->get('amount'),
            'api' => $wallet->coin->type === COIN_TYPE_CRYPTO ? $wallet->coin->payment_service['methods'] : $request->get('api'),
            'status' => STATUS_COMPLETED,
        ];

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
                ->with(RESPONSE_TYPE_ERROR, $exception->getMessage());
        }
        DB::commit();
        return redirect()
            ->route('admin.system-wallets.withdrawal.show', ['wallet' => $wallet->symbol, 'withdrawal' => $withdrawal])
            ->with(RESPONSE_TYPE_SUCCESS, __("Withdrawal has been placed successfully."));
    }

    public function show(Wallet $wallet, WalletWithdrawal $withdrawal)
    {
        $wallet->load('coin');

        $data['wallet'] = $wallet;
        $data['withdrawal'] = $withdrawal;
        $data['title'] = __("Withdrawal Details");
        return view('withdrawal.admin.show', $data);
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

        if (settings('is_email_confirmation_required') && settings('is_admin_approval_required')) {
            $withdrawal->update(['status' => STATUS_REVIEWING]);
            $message = __("Withdrawal has been confirmed successfully. It will require admin approval for further process.");
        } else {
            WithdrawalProcessJob::dispatch($withdrawal);
            $message = __("Withdrawal has been confirmed successfully. It will process shortly.");
        }

        return redirect()
            ->route('user.wallets.withdrawals.show', ['wallet' => $withdrawal->symbol, 'withdrawal' => $withdrawal->id])
            ->with(RESPONSE_TYPE_SUCCESS, $message);
    }
}
