<?php


use App\Http\Controllers\Coin\AdminCoinAddressRemoveController;
use App\Http\Controllers\Coin\AdminCoinApiOptionController;
use App\Http\Controllers\Coin\AdminCoinController;
use App\Http\Controllers\Coin\AdminCoinDepositOptionController;
use App\Http\Controllers\Coin\AdminCoinIconOptionController;
use App\Http\Controllers\Coin\AdminCoinRevenueGraphController;
use App\Http\Controllers\Coin\AdminCoinStatusController;
use App\Http\Controllers\Coin\AdminCoinWithdrawalOptionController;
use App\Http\Controllers\CoinPair\AdminCoinPairController;
use App\Http\Controllers\CoinPair\ChangeAdminCoinPairStatusController;
use App\Http\Controllers\CoinPair\MakeDefaultAdminCoinPairController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    //Wallet Management
    Route::put('coins/{coin}/reset-addresses', AdminCoinAddressRemoveController::class)
        ->name('coins.reset-addresses');
    Route::put('coins/{coin}/toggle-status', [AdminCoinStatusController::class, 'change'])
        ->name('coins.toggle-status');
    Route::get('coins/{coin}/withdrawal/edit', [AdminCoinWithdrawalOptionController::class, 'edit'])
        ->name('coins.withdrawal.edit');
    Route::put('coins/{coin}/withdrawal/update', [AdminCoinWithdrawalOptionController::class, 'update'])
        ->name('coins.withdrawal.update');
    Route::post('coins/{coin}/icon/update', [AdminCoinIconOptionController::class, 'update'])
        ->name('coins.icon.update');
    Route::get('coins/{coin}/deposit/edit', [AdminCoinDepositOptionController::class, 'edit'])
        ->name('coins.deposit.edit');
    Route::put('coins/{coin}/deposit/update', [AdminCoinDepositOptionController::class, 'update'])
        ->name('coins.deposit.update');
    Route::get('coins/{coin}/api/edit', [AdminCoinApiOptionController::class, 'edit'])
        ->name('coins.api.edit');
    Route::put('coins/{coin}/api/update', [AdminCoinApiOptionController::class, 'update'])
        ->name('coins.api.update');
    Route::get('coins/{coin}/revenue-graph', [AdminCoinRevenueGraphController::class, 'index'])
        ->name('coins.revenue-graph');
    Route::get('coins/{coin}/revenue-graph/deposit', [AdminCoinRevenueGraphController::class, 'getDepositRevenueGraphData'])
        ->name('coins.revenue-graph.deposit');
    Route::get('coins/{coin}/revenue-graph/withdrawal', [AdminCoinRevenueGraphController::class, 'getWithdrawalRevenueGraphData'])
        ->name('coins.revenue-graph.withdrawal');
    Route::get('coins/{coin}/revenue-graph/trade-revenue', [AdminCoinRevenueGraphController::class, 'getTradeRevenueGraphData'])
        ->name('coins.revenue-graph.trade-revenue');
    Route::resource('coins', AdminCoinController::class)
        ->names('coins')
        ->except('destroy', 'show');
    //Wallet Pair Management
    Route::put('coin-pairs/{coinPair}/toggle-status', [ChangeAdminCoinPairStatusController::class, 'change'])
        ->name('coin-pairs.toggle-status');
    Route::put('coin-pairs/{coinPair}/make-status-default', MakeDefaultAdminCoinPairController::class)
        ->name('coin-pairs.make-status-default');
    Route::resource('coin-pairs', AdminCoinPairController::class)
        ->except('show')
        ->names('coin-pairs');
});
