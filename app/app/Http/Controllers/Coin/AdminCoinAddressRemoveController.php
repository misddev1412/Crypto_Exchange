<?php

namespace App\Http\Controllers\Coin;

use App\Http\Controllers\Controller;
use App\Models\Coin\Coin;
use App\Models\Wallet\Wallet;

class AdminCoinAddressRemoveController extends Controller
{
    public function __invoke(Coin $coin)
    {
        if($coin->type != COIN_TYPE_CRYPTO){
            return redirect()
                ->back()
                ->with(RESPONSE_TYPE_ERROR, __('Action is not allowed for this coin.'));
        }

        if(Wallet::where('symbol', $coin->symbol)->update(['address' => null])){
            return redirect()
                ->route('coins.index')
                ->with(RESPONSE_TYPE_SUCCESS, __('Addresses related to this coin has been removed successfully.'));
        }
        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, __('Failed to remove addresses related to this coin.'));
    }
}
