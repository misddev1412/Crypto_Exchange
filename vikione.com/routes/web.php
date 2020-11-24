<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middle-ware group. Now create something great!
|
*/
if(application_installed()){
    Route::get('/install/final', function(){
        return redirect('/');
    });
}

// Handle Main / Route
Route::get('/', 'Auth\LoginController@checkLoginState')->name('home');
Route::get('/locale', 'PublicController@set_lang')->name('language');

// Authenticates Routes
Route::get('/auth/{service}', 'Auth\SocialAuthController@redirect')->name('social.login');
Route::get('/auth/{service}/callback', 'Auth\SocialAuthController@callback')->name('social.login.callback');
Route::post( '/auth/social/register', 'Auth\SocialAuthController@register' )->name('social.register');

// Authenticates Routes
Auth::routes();
Route::get('verify/', 'Auth\VerifyController@index')->name('verify');
Route::get('verify/resend', 'Auth\VerifyController@resend')->name('verify.resend');
Route::get('verify/{id}/{token}', 'Auth\VerifyController@verify')->name('verify.email');
Route::get('verify/success', 'Auth\LoginController@verified')->name('verified');
Route::get('register/success', 'Auth\LoginController@registered')->name('registered');
Route::any('log-out', 'Auth\LoginController@logout')->name('log-out');
// Google 2FA Routes 
Route::get('/login/2fa', 'Auth\SocialAuthController@show_2fa_form')->middleware('auth')->name('auth.2fa');
Route::get('/login/2fa/reset', 'Auth\SocialAuthController@show_2fa_reset_form')->name('auth.2fa.reset');
Route::post('/login/2fa/reset', 'Auth\SocialAuthController@reset_2fa');
Route::post('/login/2fa', function(){
    return redirect()->route('home');
})->middleware(['auth', 'g2fa']);

// if(is_maintenance()){
Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Auth\LoginController@login');
Route::post('admin/logout', 'Auth\LoginController@logout')->name('admin.logout');
Route::get('admin/login/2fa', 'Auth\SocialAuthController@show_2fa_form')->middleware('auth')->name('admin.auth.2fa');
Route::post('admin/login/2fa', function(){
    return redirect()->route('home');
})->middleware(['auth', 'g2fa']);
// }

// User Routes
Route::prefix('user')->middleware(['auth', 'user', 'verify_user', 'g2fa'])->name('user.')->group(function () {
    Route::get('/', 'User\UserController@index')->name('home');
    Route::get('/account', 'User\UserController@account')->name('account');
    Route::get('/account/activity', 'User\UserController@account_activity')->name('account.activity');
    Route::get('/contribute', 'User\TokenController@index')->name('token');
    Route::get('/contribute/cancel/{gateway?}', 'User\TokenController@payment_cancel')->name('payment.cancel');
    Route::get('/transactions', 'User\TransactionController@index')->name('transactions');
    Route::get('/kyc', 'User\KycController@index')->name('kyc');
    Route::get('/kyc/application', 'User\KycController@application')->name('kyc.application');
    Route::get('/kyc/application/view', 'User\KycController@view')->name('kyc.application.view');
    Route::get('/kyc-list/documents/{file}/{doc}', 'User\KycController@get_documents')->middleware('ico')->name('kycs.file');
    Route::get('/password/confirm/{token}', 'User\UserController@password_confirm')->name('password.confirm');
    // Referral v1.0.3 > v1.1.1
    Route::get('/referral', 'User\UserController@referral')->name('referral');
    // My Token v1.1.2
    Route::get('/account/balance', 'User\UserController@mytoken_balance')->name('token.balance');
    Route::get('token/push/exchange', 'User\UserController@depositOneBlueToExchange')->name('one.exchange.deposit');

    //Thien Dev 19/11/2020
    Route::get('/buy-sell', 'User\BuySellController@index')->name('buysell');
    Route::get('/sell_goods/show', 'User\SellGoodController@show')->name('sell_goods.show');
    Route::post('/sell_goods/update', 'User\SellGoodController@update')->name('sell_goods.update');

    // User Ajax Request
    Route::name('ajax.')->prefix('ajax')->group(function () {
        Route::post('/account/wallet-form', 'User\UserController@get_wallet_form')->name('account.wallet');
        Route::post('/account/update', 'User\UserController@account_update')->name('account.update')->middleware('demo_user');
        Route::post('/contribute/access', 'User\TokenController@access')->name('token.access');
        Route::post('/contribute/payment', 'User\TokenController@payment')->name('payment');

        Route::post('/transactions/delete/{id}', 'User\TransactionController@destroy')->name('transactions.delete')->middleware('demo_user');
        Route::post('/transactions/send', 'User\TransactionController@send')->name('transactions.send');
        Route::post('/transactions/view', 'User\TransactionController@show')->name('transactions.view');
        Route::post('/kyc/submit', 'User\KycController@submit')->name('kyc.submit');
        Route::post('/account/activity', 'User\UserController@account_activity_delete')->name('account.activity.delete')->middleware('demo_user');
        Route::post('/account/point', 'User\UserController@point')->name('account.point.multiply')->middleware('demo_user');

        Route::post('/buy-sell/send', 'User\BuySellController@send')->name('buysell.send');
        Route::post('/buy-sell/sell', 'User\BuySellController@sell')->name('buysell.sell');
        Route::post('/buy-sell/view', 'User\BuySellController@view')->name('buysell.view');
        Route::post('/buy-sell/cancel', 'User\BuySellController@cancel')->name('buysell.cancel');
        Route::post('/buy-sell/message', 'User\BuySellController@message')->name('buysell.message');
        Route::post('/sell_goods/send', 'User\SellGoodController@send')->name('sell_goods.send');

    });
});

Route::prefix('admin')->middleware(['auth', 'admin', 'g2fa', 'ico'])->name('admin.')->group(function () {
    Route::get('/', 'Admin\AdminController@index')->name('home');
    Route::get('/affiliate', 'Admin\AffiliateController@index')->name('affiliate');
    Route::any('/system-info', 'Admin\AdminController@system_info')->name('system');
    Route::any('/tokenlite-register', 'Admin\AdminController@treatment')->name('tokenlite');
    Route::get('/profile', 'Admin\AdminController@profile')->middleware('ico')->name('profile');
    Route::get('/profile/activity', 'Admin\AdminController@activity')->middleware('ico')->name('profile.activity');
    Route::get('/password/confirm/{token}', 'Admin\AdminController@password_confirm')->name('password.confirm');
    Route::get('/transactions/{state?}', 'Admin\TransactionController@index')->middleware('ico')->name('transactions');
    Route::get('/stages/settings', 'Admin\IcoController@settings')->middleware('ico')->name('stages.settings');
    Route::get('/pages', 'Admin\PageController@index')->middleware('ico')->name('pages');
    Route::get('/settings', 'Admin\SettingController@index')->middleware(['ico', 'super_admin'])->name('settings');
    Route::get('/settings/email', 'Admin\EmailSettingController@index')->middleware(['ico', 'super_admin'])->name('settings.email');
    Route::get('/settings/referral', 'Admin\SettingController@referral_setting')->middleware(['ico', 'super_admin'])->name('settings.referral'); // v1.1.2
    Route::get('/settings/point', 'Admin\SettingController@point_setting')->middleware(['ico', 'super_admin'])->name('settings.point'); // v1.1.2
    Route::get('/settings/affiliate', 'Admin\SettingController@affiliate_setting')->middleware(['ico', 'super_admin'])->name('settings.affiliate'); // v1.1.2
    Route::get('/settings/rest-api', 'Admin\SettingController@api_setting')->middleware(['ico', 'super_admin'])->name('settings.api'); // v1.0.6
    Route::get('/payment-methods', 'Admin\PaymentMethodController@index')->middleware(['ico', 'super_admin'])->name('payments.setup');
    Route::get('/payment-methods/edit/{slug}', 'Admin\PaymentMethodController@edit')->middleware(['ico', 'super_admin'])->name('payments.setup.edit');
    Route::get('/stages', 'Admin\IcoController@index')->middleware('ico')->name('stages');
    Route::get('/stages/{id}', 'Admin\IcoController@edit_stage')->middleware('ico')->name('stages.edit');
    Route::get('/users/{role?}', 'Admin\UsersController@index')->middleware('ico')->name('users'); //v1.1.0
    Route::get('/users/wallet/change-request', 'Admin\UsersController@wallet_change_request')->middleware('ico')->name('users.wallet.change');
    Route::get('/kyc-list/{status?}', 'Admin\KycController@index')->middleware('ico')->name('kycs'); //v1.1.0
    Route::get('/kyc-list/documents/{file}/{doc}', 'Admin\KycController@get_documents')->middleware('ico')->name('kycs.file');
    Route::get('/transactions/view/{id}', 'Admin\TransactionController@show')->name('transactions.view');
    Route::get('/users/{id?}/{type?}', 'Admin\UsersController@show')->name('users.view');
    Route::get('/kyc/view/{id}/{type}', 'Admin\KycController@show')->name('kyc.view');
    Route::get('/pages/{slug}', 'Admin\PageController@edit')->middleware('ico')->name('pages.edit');
    Route::get('/export/{table?}/{format?}', 'ExportController@export')->middleware(['ico', 'demo_user', 'super_admin'])->name('export'); // v1.1.0
    Route::get('/languages', 'Admin\LanguageController@index')->middleware(['ico'])->name('lang.manage'); // v1.1.3
    Route::get('/languages/translate/{code}', 'Admin\LanguageController@translator')->middleware(['ico'])->name('lang.translate'); // v1.1.3

    Route::get('/sellgoods/{status?}', 'Admin\SellGoodController@index')->middleware('ico')->name('sellgoods');
    Route::post('/sellgoods/update', 'Admin\SellGoodController@update')->middleware('ico')->name('sellgoods.update');

    Route::get('/buysell/{status?}', 'Admin\BuySellController@index')->middleware('ico')->name('buysell');
    Route::post('/buysell/update', 'Admin\BuySellController@update')->middleware('ico')->name('buysell.update');

    /* Admin Ajax Route */
    Route::name('ajax.')->prefix('ajax')->middleware(['ico'])->group(function () {
        Route::post('/affiliate/floor', 'Admin\AffiliateController@floor')->name('affiliate.floor');
         Route::post('/users/interest-calculation','Admin\UsersController@interestCalculation')->middleware('demo_user')->name('users.interest');
         Route::post('/users/affiliate','Admin\UsersController@affiliate')->middleware('demo_user')->name('users.affiliate');
        Route::post('/users/view', 'Admin\UsersController@status')->name('users.view')->middleware('demo_user');
        Route::post('/users/showinfo', 'Admin\UsersController@show')->name('users.show');
        Route::post('/users/delete/all', 'Admin\UsersController@delete_unverified_user')->name('users.delete')->middleware('demo_user');
        Route::post('/users/email/send', 'Admin\UsersController@send_email')->name('users.email')->middleware('demo_user');
        Route::post('/users/point', 'Admin\UsersController@point')->name('users.point')->middleware('demo_user');
        Route::get('/users/get-point', 'Admin\UsersController@getPoint')->name('users.getPoint')->middleware('demo_user');
        Route::post('/users/insert', 'Admin\UsersController@store')->middleware(['super_admin', 'demo_user'])->name('users.add');
        Route::post('/profile/update', 'Admin\AdminController@profile_update')->name('profile.update')->middleware('demo_user');
        Route::post('/profile/activity', 'Admin\AdminController@activity_delete')->name('profile.activity.delete')->middleware('demo_user');
        Route::post('/users/wallet/action', 'Admin\UsersController@wallet_change_request_action')->name('users.wallet.action');
        Route::post('/payment-methods/view', 'Admin\PaymentMethodController@show')->middleware('super_admin')->name('payments.view');
        Route::post('/payment-methods/update', 'Admin\PaymentMethodController@update')->middleware(['super_admin', 'demo_user'])->name('payments.update');
        Route::post('/payment-methods/quick-update', 'Admin\PaymentMethodController@quick_update')->middleware(['super_admin', 'demo_user'])->name('payments.qupdate');
        Route::post('/kyc/view', 'Admin\KycController@ajax_show')->name('kyc.ajax_show');
        Route::post('/stages/update', 'Admin\IcoController@update')->name('stages.update')->middleware('demo_user');
        Route::post('/stages/pause', 'Admin\IcoController@pause')->middleware('ico')->name('stages.pause')->middleware('demo_user');
        Route::post('/stages/active', 'Admin\IcoController@active')->middleware('ico')->name('stages.active')->middleware('demo_user');
        Route::post('/stages/meta/update', 'Admin\IcoController@update_options')->name('stages.meta.update')->middleware('demo_user');
        Route::post('/stages/settings/update', 'Admin\IcoController@update_settings')->name('stages.settings.update')->middleware('demo_user');
        Route::post('/stages/actions', 'Admin\IcoController@stages_action')->middleware('ico')->name('stages.actions'); //v1.1.2
        Route::post('/kyc/update', 'Admin\KycController@update')->name('kyc.update')->middleware('demo_user');
        Route::post('/transactions/update', 'Admin\TransactionController@update')->name('transactions.update')->middleware('demo_user');

        Route::post('/transactions/adjust', 'Admin\TransactionController@adjustment')->name('transactions.adjustement');
        Route::post('/settings/email/template/view', 'Admin\EmailSettingController@show_template')->middleware('super_admin')->name('settings.email.template.view');
        Route::post('/transactions/view', 'Admin\TransactionController@show')->name('transactions.view');
        Route::post('/transactions/insert', 'Admin\TransactionController@store')->name('transactions.add')->middleware('demo_user');
        Route::post('/pages/upload', 'Admin\PageController@upload_zone')->name('pages.upload')->middleware('demo_user');
        Route::post('/pages/view', 'Admin\PageController@show')->name('pages.view');
        Route::post('/pages/update', 'Admin\PageController@update')->name('pages.update')->middleware('demo_user');
        Route::post('/settings/update', 'Admin\SettingController@update')->middleware(['super_admin','demo_user'])->name('settings.update');
        // Settings UpdateMeta v1.1.0
        Route::post('/settings/meta/update', 'Admin\SettingController@update_meta')->middleware(['super_admin','demo_user'])->name('settings.meta.update'); 
        Route::post('/settings/email/update', 'Admin\EmailSettingController@update')->middleware(['super_admin', 'demo_user'])->name('settings.email.update');
        Route::post('/settings/email/template/update', 'Admin\EmailSettingController@update_template')->middleware(['super_admin', 'demo_user'])->name('settings.email.template.update');
        Route::post('/languages', 'Admin\LanguageController@language_action')->middleware(['ico', 'demo_user'])->name('lang.action'); // v1.1.3
        Route::post('/languages/translate', 'Admin\LanguageController@language_action')->middleware(['ico', 'demo_user'])->name('lang.translate.action'); // v1.1.3

        //Transaction
        Route::post('/transactions/approve', 'Admin\TransactionController@approve')->name('transactions.approve');
        Route::post('/transactions/cancel', 'Admin\TransactionController@cancel')->name('transactions.cancel');

    });

    //Clear Cache facade value:
    Route::get('/clear', function () {
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('config:clear');
        $exitCode = Artisan::call('route:clear');
        $exitCode = Artisan::call('view:clear');

        $data = ['msg' => 'success', 'message' => 'Cache Cleared and Optimized!'];

        if (request()->ajax()) {
            return response()->json($data);
        }
        return back()->with([$data['msg'] => $data['message']]);
    })->name('clear.cache');
	
	Route::get('token/one/update-24hrs', 'Admin\UsersController@updateTokenOne')->name('token.one.update');
});

Route::name('public.')->group(function () {
    Route::get('/check/updater', 'PublicController@update_check');
    Route::get('/insert/database', 'PublicController@database')->name('database');
    Route::get('/kyc-application', 'PublicController@kyc_application')->name('kyc');
    Route::get('/invite', 'PublicController@referral')->name('referral');
    Route::post('/kyc-application/file-upload', 'User\KycController@upload')->name('kyc.file.upload');
    Route::post('/kyc-application/submit', 'User\KycController@submit')->name('kyc.submit');
    Route::get('/qrgen.png', 'PublicController@qr_code')->name('qrgen');

    Route::get('white-paper', function () {
        $filename = get_setting('site_white_paper');
        $path = storage_path('app/public/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        $file = \File::get($path);
        $type = \File::mimeType($path);
        $response = response($file, 200)->header("Content-Type", $type);
        return $response;
    })->name('white.paper');

    Route::get('/{slug}', 'PublicController@site_pages')->name('pages');
});

// Ajax Routes
Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::post('/kyc/file-upload', 'User\KycController@upload')->name('kyc.file.upload');
    Route::get('transaction/payment/show', 'User\TransactionController@paymentShow')->name('transaction.payment.show');
    Route::get('transaction/payment/method', 'User\TransactionController@paymentMethod')->name('transaction.payment.method');
});
