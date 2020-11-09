<?php

namespace App\Http\Controllers\Exchange;

use App\Http\Controllers\Controller;
use App\Models\Coin\CoinPair;
use App\Models\Wallet\Wallet;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletSummaryController extends Controller
{
    public function __invoke(Request $request) {
        $walletSummary = $this->_getWalletSummary($request->coin_pair);

        return response()->json($walletSummary);
    }

    public function _getWalletSummary($coinPair): array
    {
        $coinPair = CoinPair::where('name', $coinPair)->first();

        if( $coinPair )
        {
            $baseCoinWallet = Wallet::where('symbol', $coinPair->base_coin)
                ->where('is_system_wallet', INACTIVE)
                ->where('user_id', Auth::id())
                ->first();

            $coinWallet = Wallet::where('symbol', $coinPair->trade_coin)
                ->where('is_system_wallet', INACTIVE)
                ->where('user_id', Auth::id())
                ->first();

            return [
                'base_coin_balance' => $baseCoinWallet->primary_balance,
                'trade_coin_balance' => $coinWallet->primary_balance,
            ];
        }

        return [];
    }
}
