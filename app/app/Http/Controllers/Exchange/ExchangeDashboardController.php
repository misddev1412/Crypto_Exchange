<?php

namespace App\Http\Controllers\Exchange;

use App\Http\Controllers\Controller;
use App\Services\CoinPair\GetDefaultCoinPair;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\UnauthorizedException;

class ExchangeDashboardController extends Controller
{

    public function index($pair = null)
    {
        $data['title'] = __('Exchange');
        $data['coinPair'] = app(GetDefaultCoinPair::class)->getCoinPair($pair);

        if( $data['coinPair'] ) {
            return view('exchange.index', $data);
        }

        throw new UnauthorizedException('exchange_exception', 404);
    }
}
