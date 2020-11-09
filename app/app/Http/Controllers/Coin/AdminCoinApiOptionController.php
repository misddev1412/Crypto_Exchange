<?php

namespace App\Http\Controllers\Coin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coin\CoinApiRequest;
use App\Models\BankAccount\BankAccount;
use App\Models\Coin\Coin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCoinApiOptionController extends Controller
{
    protected $service;

    public function edit(Coin $coin): View
    {
        $data['title'] = __('Edit API');
        $data['coin'] = $coin;
        $data['bankAccounts'] = BankAccount::whereNull('user_id')->pluck('bank_name', 'id');
        return view('coins.admin.api_form', $data);
    }

    public function update(CoinApiRequest $request, Coin $coin): RedirectResponse
    {
        $api['selected_apis'] = $request->get('api', []);
        if ($coin->type === COIN_TYPE_FIAT && in_array(API_BANK, $api['selected_apis'])) {
            $api['selected_banks'] = $request->get('banks', []);
        }

        if ($coin->update(['api' => $api])) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The coin API has been updated successfully.'));
        }
        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update coin API.'));
    }
}
