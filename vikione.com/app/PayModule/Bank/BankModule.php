<?php

namespace App\PayModule\Bank;

/**
 * Bank Module
 * @version v1.2.4
 * @since v1.0.2
 */

use Auth;
use Route;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\IcoStage;
use App\Helpers\IcoHandler;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\PayModule\ModuleHelper;
use App\PayModule\PmInterface;
use App\Notifications\TnxStatus;
use App\Helpers\TokenCalculate as TC;

class BankModule implements PmInterface
{
    const SLUG = 'bank';
    const VERSION = '1.2.4';
    const APP_VERSION = '^1.1.5';
    const SUPPORT_CURRENCY = ['USD', 'GBP', 'EUR', 'TRY', 'RUB', 'CAD', 'AUD', 'INR', 'NGN', 'BRL', 'NZD', 'PLN', 'JPY', 'MYR', 'IDR'];

    public function routes()
    {
        Route::post('bank/update', 'Bank\BankController@update_transaction')->name('bank.update');
    }

    public function admin_views()
    {
        $pmData = PaymentMethod::get_data(self::SLUG, true);
        $name = self::SLUG;
    	return ModuleHelper::view('Bank.views.card', compact('pmData', 'name'));
    }

    public function admin_views_details()
    {
        $pmData = PaymentMethod::get_data(self::SLUG, true);
        return ModuleHelper::view('Bank.views.admin', compact('pmData'));
    }

    public function show_action()
    {
        $pmData = PaymentMethod::get_data(self::SLUG, true);
        $html = '<li class="pay-item"><div class="input-wrap">
                    <input type="radio" class="pay-check" Value="'.self::SLUG.'" name="pay_option" required="required" id="pay-'.self::SLUG.'" data-msg-required="'.__('Select your payment method.').'">
                    <label class="pay-check-label" for="pay-'.self::SLUG.'"><span class="pay-check-text" title="'.$pmData->details.'">'.$pmData->title.'</span><img class="pay-check-img" src="'.asset('assets/images/pay-bank.png').'" alt="'.ucfirst(self::SLUG).'"></label>
                </div></li>';
        return [
            'currency' => $this->check_currency(),
            'html' => ModuleHelper::str2html($html)
        ];
    }

    public function check_currency()
    {
        return self::SUPPORT_CURRENCY;
    }

    public function transaction_details($transaction)
    {
        return ModuleHelper::view('Bank.views.tnx_details', compact('transaction'));
    }

    public function email_details($transaction){
        $bank = get_pm(self::SLUG);
        
        $text = '';
        $text .= "<tr><td>Payment to Address</td><td>:</td><td>";
        $text .= (isset($bank->bank_account_name) && $bank->bank_account_name) ? "Account Name: <strong>". $bank->bank_account_name . "</strong><br>" : '';
        $text .= (isset($bank->bank_account_number) && $bank->bank_account_number) ? "Account Number: <strong>". $bank->bank_account_number . "</strong><br>" : '';
        $text .= (isset($bank->bank_holder_address) && $bank->bank_holder_address) ? "Account Holder Address: <strong>". $bank->bank_holder_address . "</strong><br>" : '';
        $text .= (isset($bank->bank_name) && $bank->bank_name) ? "Bank Name: <strong>". $bank->bank_name . "</strong><br>" : '';
        $text .= (isset($bank->bank_address) && $bank->bank_address) ? "Bank Address: <strong>". $bank->bank_address . "</strong><br>" : '';
        $text .= (isset($bank->routing_number) && $bank->routing_number) ? "Routing Number: <strong>". $bank->routing_number . "</strong><br>" : '';
        $text .= (isset($bank->iban) && $bank->iban) ? "IBAN: <strong>". $bank->iban . "</strong><br>" : '';
        $text .= (isset($bank->swift_bic) && $bank->swift_bic) ? "SWIFT/BIC: <strong>". $bank->swift_bic . "</strong><br>" : '';
        $text .= "</td></tr>";

        return html_string($text);
    }

    public function create_transaction(Request $request)
    {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', get_setting('token_decimal_max', 8));
            ini_set('serialize_precision', -1);
        }
        $response['msg'] = 'info';
        $response['message'] = __('messages.nothing');
        $validator = Validator::make($request->all(), [
            'agree' => 'required',
            'pp_token' => 'required|integer|min:1',
            'pp_currency' => 'required',
        ], [
            'agree.required' => __('messages.agree'),
            'pp_currency.required' => __('messages.trnx.require_currency'),
            'pp_token.required' => __('messages.trnx.require_token'),
            'pp_token.min' => __('messages.trnx.minimum_token'),
            'pp_token.integer' => __('messages.trnx.minimum_token'),
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('agree')) {
                $msg = $validator->errors()->first();
            } elseif ($validator->errors()->has('pp_token')) {
                $msg = $validator->errors()->first();
            } elseif ($validator->errors()->has('pp_currency')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $response['msg'] = 'warning';
            $response['message'] = $msg;
        } else {
            $tc = new TC();
            $token = $request->input('pp_token');
            $currency = strtolower($request->input('pp_currency'));
            $currency_rate = Setting::exchange_rate($tc->get_current_price(), $currency);
            $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
            $base_currency = strtolower(base_currency());
            $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
            $trnx_data = [
                'token' => round($token, min_decimal()),
                'bonus_on_base' => round($tc->calc_token($token, 'bonus-base'), min_decimal()),
                'bonus_on_token' => round($tc->calc_token($token, 'bonus-token'), min_decimal()),
                'total_bonus' => round($tc->calc_token($token, 'bonus'), min_decimal()),
                'total_tokens' => round($tc->calc_token($token), min_decimal()),
                'base_price' => round($tc->calc_token($token, 'price')->base, max_decimal()),
                'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
            ];
            $save_data = [
                'created_at' => Carbon::now()->toDateTimeString(),
                'tnx_id' => set_id(rand(100, 999), 'trnx'),
                'tnx_type' => 'purchase',
                'tnx_time' => Carbon::now()->toDateTimeString(),
                'tokens' => $trnx_data['token'],
                'bonus_on_base' => $trnx_data['bonus_on_base'],
                'bonus_on_token' => $trnx_data['bonus_on_token'],
                'total_bonus' => $trnx_data['total_bonus'],
                'total_tokens' => $trnx_data['total_tokens'],
                'stage' => active_stage()->id,
                'user' => Auth::id(),
                'amount' => $trnx_data['amount'],
                'base_amount' => $trnx_data['base_price'],
                'base_currency' => $base_currency,
                'base_currency_rate' => $base_currency_rate,
                'currency' => $currency,
                'currency_rate' => $currency_rate,
                'receive_currency' => $currency,
                'all_currency_rate' => $all_currency_rate,
                'payment_method' => self::SLUG,
                'payment_to' => $this->payment_address(),
                'added_by' => set_added_by('00'),
                'details' => __('messages.trnx.purchase_token'),
                'status' => 'pending',
            ];
            $iid = Transaction::insertGetId($save_data);

            if ($iid != null) {
                $response['trnx'] = 'true';
                $response['msg'] = 'info';
                $response['message'] = __('messages.trnx.manual.success');
                $transaction = Transaction::where('id', $iid)->first();
                $transaction->tnx_id = set_id($iid, 'trnx');
                $transaction->save();

                IcoStage::token_add_to_account($transaction, 'add');
                try {
                    $transaction->payment_to = '(as mentioned above)';
                    $transaction->tnxUser->notify((new TnxStatus($transaction, 'submit-user')));
                    if (get_emailt('order-placed-admin', 'notify') == 1) {
                        notify_admin($transaction, 'placed-admin');
                    }
                } catch (\Exception $e) {
                    $response['error'] = $e->getMessage();
                }
                $response['modal'] = ModuleHelper::view('Bank.views.payment', compact('transaction'), false);
            } else {
                $response['msg'] = 'error';
                $response['message'] = __('messages.trnx.manual.failed');
                Transaction::where('id', $iid)->delete();
            }
        }

        if ($request->ajax()) {
            return response()->json($response);
        }
        return back()->with([$response['msg'] => $response['message']]);
    }

    public function payment_address()
    {
        $bank = PaymentMethod::get_data(self::SLUG);
        $bank_info = [];
        if (isset($bank->bank_account_name) && isset($bank->bank_account_number)) {
            $bank_info[] = $bank->bank_account_name. ' (' . $bank->bank_account_number .')';
        }
        if (isset($bank->bank_name)) {
            $bank_info[] = $bank->bank_name;
        }
        if (isset($bank->bank_address)) {
            $bank_info[] = $bank->bank_address;
        }
        $text = (!empty($bank_info)) ? join(', ', $bank_info) : '';

        return $text;
    }

    public function save_data(Request $request)
    {
        $bank = PaymentMethod::get_data(self::SLUG);
        $data = [
            'bank_account_name' => $request->input('bank_account_name', (isset($bank->bank_account_name) ? $bank->bank_account_name : '')),
            'bank_account_number' => $request->input('bank_account_number', (isset($bank->bank_account_number) ? $bank->bank_account_number : '')),
            'bank_holder_address' => $request->input('bank_holder_address', (isset($bank->bank_holder_address) ? $bank->bank_holder_address : '')),
            'bank_name' => $request->input('bank_name', (isset($bank->bank_name) ? $bank->bank_name : '')),
            'bank_address' => $request->input('bank_address', (isset($bank->bank_address) ? $bank->bank_address : '')),
            'routing_number' => $request->input('routing_number', (isset($bank->routing_number) ? $bank->routing_number : '')),
            'iban' => $request->input('iban', (isset($bank->iban) ? $bank->iban : '')),
            'swift_bic' => $request->input('swift_bic', (isset($bank->swift_bic) ? $bank->swift_bic : '')),
        ];
        $bank_pm = PaymentMethod::where('payment_method', self::SLUG)->first();
        $bank_pm->title = $request->input('title');
        $bank_pm->description = $request->input('details');
        $bank_pm->data = json_encode($data);
        $bank_pm->status = isset($request->status) ? 'active' : 'inactive';
        if ($bank_pm->save()) {
            $response['msg'] = 'success';
            $response['message'] = __('messages.update.success', ['what' => 'Bank payment information']);
        } else {
            $response['msg'] = 'error';
            $response['message'] = __('messages.update.failed', ['what' => 'Bank payment information']);
        }
        return $response;
    }

    public function demo_data()
    {
        $data = [
            'bank_account_name' => null,
            'bank_account_number' => null,
            'bank_holder_address' => null,
            'bank_name' => null,
            'bank_address' => null,
            'routing_number' => null,
            'iban' => null,
            'swift_bic' => null,
        ];

        if (PaymentMethod::check(self::SLUG)) {
            $bank = new PaymentMethod();
            $bank->payment_method = self::SLUG;
            $bank->title = 'Pay via Bank Transfer';
            $bank->description = 'You can send payment direct to our bank account.';
            $bank->data = json_encode($data);
            $bank->status = 'inactive';
            $bank->save();
        }
    }
}
