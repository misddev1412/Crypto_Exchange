<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Wallet\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWalletController extends Controller
{
    public function __invoke()
    {
        $wallets =  Wallet::with('coin:symbol,name,icon')
            ->withOnOrderBalance()
            ->where('user_id', Auth::id())
            ->withoutSystemWallet()
            ->orderBy('primary_balance', 'desc')
            ->paginate();

        return response()->json([
            RESPONSE_STATUS_KEY => true,
            RESPONSE_DATA => $wallets
        ]);
    }
}
