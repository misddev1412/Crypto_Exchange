<?php

namespace App\Http\Controllers\Coin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coin\CoinWithdrawalRequest;
use App\Models\Coin\Coin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCoinWithdrawalOptionController extends Controller
{
    protected $service;

    public function edit(Coin $coin): View
    {
        $data['title'] = __('Edit Wallet Withdrawal Info');
        $data['coin'] = $coin;
        return view('coins.admin.withdrawal_form', $data);
    }

    public function update(CoinWithdrawalRequest $request, Coin $coin): RedirectResponse
    {
        $attributes = [
            'withdrawal_status' => $request->get('withdrawal_status'),
            'minimum_withdrawal_amount' => $request->get('minimum_withdrawal_amount') ?: 0,
            'daily_withdrawal_limit' => $request->input('daily_withdrawal_limit') ?: 0,
            'withdrawal_fee' => $request->get('withdrawal_fee') ?: 0,
            'withdrawal_fee_type' => $request->get('withdrawal_fee_type'),
        ];

        if ($coin->update($attributes)) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The coin has been updated successfully.'));
        }
        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update.'));
    }
}
