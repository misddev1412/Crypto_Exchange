<?php

namespace App\Http\Controllers\Coin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coin\CoinDepositRequest;
use App\Models\Coin\Coin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCoinDepositOptionController extends Controller
{
    protected $service;

    public function edit(Coin $coin): View
    {
        $data['title'] = __('Edit Deposit Options');
        $data['coin'] = $coin;
        return view('coins.admin.deposit_form', $data);
    }

    public function update(CoinDepositRequest $request, Coin $coin): RedirectResponse
    {
        $attributes = [
            'deposit_status' => $request->get('deposit_status'),
            'deposit_fee_type' => $request->get('deposit_fee_type'),
            'deposit_fee' => $request->get('deposit_fee') ?: 0
        ];

        if( $request->has('minimum_deposit_amount') ) {
            $attributes['minimum_deposit_amount'] = $request->get('minimum_deposit_amount');
        }

        if ($coin->update($attributes)) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The coin has been updated successfully.'));
        }
        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update.'));
    }
}
