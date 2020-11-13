<?php

use App\Http\Controllers\Api\BankAccount\UserBankAccountController;
use App\Http\Controllers\Api\Deposit\UserDepositController;
use App\Http\Controllers\Api\Orders\CancelOrderController;
use App\Http\Controllers\Api\Orders\CreateOrderController;
use App\Http\Controllers\Api\Orders\UserOrderController;
use App\Http\Controllers\Api\PaymentMethods\PaymentMethodController;
use App\Http\Controllers\Api\Ticker\PublicApiController;
use App\Http\Controllers\Api\Wallet\UserWalletController;
use App\Http\Controllers\Api\Webhook\BitcoinIpnController;
use App\Http\Controllers\Api\Webhook\CoinpaymentsIpnController;
use App\Http\Controllers\Api\Withdrawal\UserWithdrawalController;
use App\Http\Controllers\Api\Local\CoinController;
use Illuminate\Support\Facades\Route;

Route::any('/ipn/coinpayments', CoinpaymentsIpnController::class);
Route::any('/ipn/bitcoin/{currency}', BitcoinIpnController::class);
Route::get('public', PublicApiController::class);
Route::get('coins/{coin}/payment-methods', PaymentMethodController::class);
Route::group(['prefix' => 'local', 'middleware' => ['api_local']], function () {
    Route::get('test', [CoinController::class, 'test']);
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    // user orders
    Route::get('user/orders/{coinPair}/open', UserOrderController::class);
    Route::post('user/orders/place', [CreateOrderController::class, 'store']);
    Route::delete('user/orders/{order}/destroy', [CancelOrderController::class, 'destroy']);
    Route::get('user/wallets', UserWalletController::class);
    //Deposit
    Route::get('user/wallets/{wallet}/deposits', [UserDepositController::class, 'index']);
    Route::post('user/wallets/{wallet}/deposits/store', [UserDepositController::class, 'store']);
    Route::get('user/wallets/{wallet}/get-deposit-address', [UserDepositController::class, 'getDepositAddress']);
    Route::post('user/wallets/{wallet}/deposits/{deposit}/upload-bank-receipt', [UserDepositController::class, 'uploadReceipt']);
    // user withdrawals
    Route::get('user/wallets/{wallet}/withdrawals', [UserWithdrawalController::class, 'index']);
    Route::post('user/wallets/{wallet}/withdrawals/store', [UserWithdrawalController::class, 'store']);
    Route::delete('user/wallets/{wallet}/withdrawals/{withdrawal}/destroy', [UserWithdrawalController::class, 'destroy']);
    Route::get('user/bank-accounts', UserBankAccountController::class);
});
