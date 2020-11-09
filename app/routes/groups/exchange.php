<?php

use App\Http\Controllers\Exchange\CoinMarketController;
use App\Http\Controllers\Exchange\ExchangeDashboardController;
use App\Http\Controllers\Exchange\MyOpenOrderController;
use App\Http\Controllers\Exchange\OrderController;
use App\Http\Controllers\Exchange\UserTradesController;
use App\Http\Controllers\Exchange\TradingHistoryController;
use App\Http\Controllers\Exchange\WalletSummaryController;

Route::get('get-coin-market/{baseCoin}', [CoinMarketController::class, 'getCoinMarket'])
    ->name('exchange.get-coin-market');

Route::get('get-orders', OrderController::class)
    ->name('exchange.get-orders');

Route::get('get-trade-histories', TradingHistoryController::class)
    ->name('exchange.get-trade-histories');

Route::group(['middleware' => ['auth', 'permission']], function () {
    Route::get('get-my-open-orders', MyOpenOrderController::class)
        ->name('exchange.get-my-open-orders');
    Route::get('get-my-trades', UserTradesController::class)
        ->name('exchange.get-my-trades');
    Route::get('get-wallet-summary', WalletSummaryController::class)
        ->name('exchange.get-wallet-summary');
});
Route::get('/{pair?}', [ExchangeDashboardController::class, 'index'])
    ->name('exchange.index')->middleware('menuable');
