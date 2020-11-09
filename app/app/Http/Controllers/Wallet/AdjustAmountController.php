<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\AdjustAmountRequest;
use App\Models\Core\User;
use App\Models\Wallet\Wallet;
use App\Models\Core\Notification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdjustAmountController extends Controller
{
    public function create(User $user, Wallet $wallet): View
    {
        $data['title'] = __('Adjustment Wallet Amount: :wallet', ['wallet' => $wallet->symbol]);
        $data['wallet'] = $wallet;
        return view('wallets.admin.adjust_amount', $data);
    }

    public function store(AdjustAmountRequest $request, $user, Wallet $walletId)
    {
        $wallet = $walletId;
        if ($wallet->user_id != $user) {
            return redirect()->back()->with(RESPONSE_TYPE_WARNING, __('Failed to update the wallet balance for illegal wallet info.'));
        }

        $attributes = $this->_attributes($request);
        $beforeBalance = $wallet->primary_balance;

        if ($request->type == TRANSACTION_TYPE_BALANCE_DECREMENT && bccomp($beforeBalance, $request->amount) < 0) {
            return redirect()->back()->with(RESPONSE_TYPE_WARNING, __('Failed to update the wallet balance for illegal amount.'));
        }

        try {
            DB::beginTransaction();
            if (!$wallet->update($attributes)) {
                throw new Exception(__('Failed to update the wallet balance.'));
            }

            // compare the balance with given amount to identify if it's decreased or increased
            Notification::create($this->_notificationAttributes($wallet, $request));

            DB::commit();

            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The wallet balance has been updated successfully.'));
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to update the wallet balance.'));
        }
    }

    public function _attributes($request): array
    {
        $attributes = ['primary_balance' => DB::raw('primary_balance + ' . $request->amount)];

        if ($request->type == TRANSACTION_TYPE_BALANCE_DECREMENT) {
            $attributes = ['primary_balance' => DB::raw('primary_balance - ' . $request->amount)];
        }
        return $attributes;
    }

    public function _notificationAttributes($wallet, $request): array
    {
        return [
            'user_id' => $wallet->user_id,
            'message' => __("Your :currency wallet has been increased with :amount :currency by system.", [
                'amount' => $request->amount,
                'currency' => $wallet->symbol
            ]),
        ];
    }
}
