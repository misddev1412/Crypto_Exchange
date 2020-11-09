<?php

use App\Http\Controllers\BankAccount\ChangeUserBankAccountStatusController;
use App\Http\Controllers\BankAccount\UserBankManagementController;
use App\Http\Controllers\TradeHistory\MyTradeHistoryController;
use App\Http\Controllers\Core\{NotificationController, PreferenceController, ProfileController};
use App\Http\Controllers\Deposit\UserDepositController;
use App\Http\Controllers\GoogleTwoFactor\Google2faController;
use App\Http\Controllers\Guest\AuthController;
use App\Http\Controllers\KycManagement\UserKycController;
use App\Http\Controllers\Orders\CancelOrderController;
use App\Http\Controllers\Orders\CreateOrderController;
use App\Http\Controllers\Orders\UserOpenOrderController;
use App\Http\Controllers\Orders\UserOrderController;
use App\Http\Controllers\Referral\ReferralEarningController;
use App\Http\Controllers\Referral\ReferralLinkController;
use App\Http\Controllers\Referral\UserReferralController;
use App\Http\Controllers\Ticket\UserTicketController;
use App\Http\Controllers\UserActivity\UserActivityController;
use App\Http\Controllers\Wallet\UserWalletController;
use App\Http\Controllers\Withdrawal\UserWithdrawalController;
use Illuminate\Support\Facades\Route;

//User profile
Route::get('profile', [ProfileController::class, 'index'])
    ->name('profile.index');
Route::get('profile/edit', [ProfileController::class, 'edit'])
    ->name('profile.edit');
Route::put('profile/update', [ProfileController::class, 'update'])
    ->name('profile.update');
Route::get('profile/change-password', [ProfileController::class, 'changePassword'])
    ->name('profile.change-password');
Route::put('profile/change-password/update', [ProfileController::class, 'updatePassword'])
    ->name('profile.update-password');
Route::post('profile/avatar/update', [ProfileController::class, 'avatarUpdate'])
    ->name('profile.avatar.update');
Route::get('logout', [AuthController::class, 'logout'])
    ->name('logout')->withoutMiddleware(['2fa']);

//Preference
Route::get('preference', [PreferenceController::class, 'index'])
    ->name('preference.index');
Route::get('preference/edit', [PreferenceController::class, 'edit'])
    ->name('preference.edit');
Route::put('preference/update', [PreferenceController::class, 'update'])
    ->name('preference.update');

//Google 2FA
Route::get('profile/google-2fa', [Google2faController::class, 'create'])
    ->name('profile.google-2fa.create');
Route::put('profile/google-2fa/{googleCode}/store', [Google2faController::class, 'store'])
    ->name('profile.google-2fa.store');
Route::put('profile/google-2fa/destroy', [Google2faController::class, 'destroy'])
    ->name('profile.google-2fa.destroy');

//KYC
Route::resource('kyc-verifications', UserKycController::class)->only(['index', 'store'])
    ->names('kyc-verifications');

//User Specific Notice
Route::get('notifications', [NotificationController::class, 'index'])
    ->name('notifications.index');
Route::get('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.mark-as-read');
Route::get('notifications/{notification}/unread', [NotificationController::class, 'markAsUnread'])
    ->name('notifications.mark-as-unread');

//Ticket Management
Route::resource('tickets', UserTicketController::class)->only(['index', 'create', 'store', 'show'])->names('tickets');
Route::put('tickets/{ticket}/close', [UserTicketController::class, 'close'])
    ->name('tickets.close');
Route::post('tickets/{ticket}/comment', [UserTicketController::class, 'comment'])
    ->name('tickets.comment.store');
Route::get('ticket/{ticket}/download-attachment/{fileName}', [UserTicketController::class, 'download'])
    ->name('tickets.attachment.download');

//Wallet
Route::get('wallets', [UserWalletController::class, 'index'])
    ->name('user.wallets.index');

//Deposit
Route::resource('wallets/{wallet}/deposits', UserDepositController::class)->except('edit')
    ->names('user.wallets.deposits');

//Withdrawal
Route::resource('wallets/{wallet}/withdrawals', UserWithdrawalController::class)
    ->except('edit', 'update')
    ->names('user.wallets.withdrawals');
Route::get('wallets/{wallet}/withdrawals/{withdrawal}/confirmation', [UserWithdrawalController::class, 'confirmation'])
    ->name('user.wallets.withdrawals.confirmation');

//Create Order
Route::post('user/orders/store', [CreateOrderController::class, 'store'])
    ->name('user.order.store');

//Cancel Order
Route::delete('user/order/{order}/destroy', [CancelOrderController::class, 'destroy'])
    ->name('user.order.destroy');

//Open Orders
Route::get('open-orders', [UserOpenOrderController::class, 'index'])
    ->name('user.open.order');

//Order History
Route::get('orders', [UserOrderController::class, 'index'])
    ->name('user.orders.index');

/*//Cancel Order
Route::delete('my-order/{order}/cancel', [CancelOrderController::class, 'destroy'])
    ->name('my-order.cancel');*/

//Bank Account
Route::put('bank-accounts/toggle-status/{bankAccount}', [ChangeUserBankAccountStatusController::class, 'change'])
    ->name('bank-accounts.toggle-status');
Route::resource('bank-accounts', UserBankManagementController::class)
    ->except('show');

//Referral
Route::get('referral', [ReferralLinkController::class, 'show'])
    ->name('referral.link.show');
Route::get('referral-users', [UserReferralController::class, 'list'])
    ->name('referral.users');
Route::get('referral-users/{user}/earnings', [ReferralEarningController::class, 'show'])
    ->name('referral.users.earnings');
Route::get('referral/earnings', [ReferralEarningController::class, 'index'])
    ->name('referral.earnings');

//Activity
Route::get('activities', [UserActivityController::class, 'index'])->name('my-activities.index');

//Trade History
Route::get('trade-history', MyTradeHistoryController::class)
    ->name('my-trade-history');
