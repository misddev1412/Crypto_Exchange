<?php

namespace App\Http\Controllers\Coin;

use App\Http\Controllers\Controller;
use App\Models\Coin\Coin;
use App\Models\Coin\CoinPair;
use Illuminate\Http\RedirectResponse;

class AdminCoinStatusController extends Controller
{
    public function change(Coin $coin): RedirectResponse
    {
        $isDefaultCoinPair = CoinPair::where('is_default', ACTIVE)
            ->where(function ($query) use ($coin) {
                $query->where('trade_coin', $coin->symbol)
                    ->orWhere('base_coin', $coin->symbol);
            })->first();

        if ($isDefaultCoinPair) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('This is a part of default coin pair and it cannot be inactivated.'));
        }

        if ($coin->toggleStatus()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Successfully system coin status changed. Please try again.'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to change status. Please try again.'));
    }
}
