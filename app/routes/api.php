<?php

    use App\Http\Controllers\Api\Ticker\ChartDataController;
use App\Http\Controllers\Api\Ticker\PublicApiController;
use App\Http\Controllers\Api\Webhook\BitcoinIpnController;
    use App\Http\Controllers\Api\Webhook\CoinpaymentsIpnController;
    use Illuminate\Support\Facades\Route;

    Route::any('/ipn/coinpayments', CoinpaymentsIpnController::class);
    Route::any('/ipn/bitcoin/{currency}', BitcoinIpnController::class);
    Route::get('public', PublicApiController::class);
