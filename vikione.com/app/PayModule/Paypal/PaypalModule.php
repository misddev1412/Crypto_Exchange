<?php 

namespace App\PayModule\Paypal;

/**
 * Paypal Module
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
use App\Models\EmailTemplate;
use App\PayModule\ModuleHelper;
use App\PayModule\PmInterface;
use App\Notifications\TnxStatus;
use App\PayModule\Paypal\PaypalPay;
use App\Helpers\TokenCalculate as TC;

class PaypalModule implements PmInterface
{
    const SLUG = 'paypal';
    const SUPPORT_CURRENCY = ['USD', 'GBP', 'EUR', 'RUB', 'CAD', 'AUD', 'INR', 'BRL', 'NZD', 'PLN', 'JPY', 'MYR'];
    const VERSION = '1.2.4';
    const APP_VERSION = '^1.1.5';

    public function routes()
    {
        Route::get('paypal/success', 'Paypal\PaypalController@success')->name('paypal.success');
        Route::get('paypal/canceled', 'Paypal\PaypalController@cancel')->name('paypal.cancel');
    }

    public function admin_views()
    {
        $pmData = PaymentMethod::get_data(self::SLUG, true);
        $name = self::SLUG;
    	return ModuleHelper::view('Paypal.views.card', compact('pmData', 'name'));
    }

    public function admin_views_details()
    {
        $pmData = PaymentMethod::get_data(self::SLUG, true);
        return ModuleHelper::view('Paypal.views.admin', compact('pmData'));
    }

    public function show_action()
    {
        $pmData = PaymentMethod::get_data(self::SLUG, true);
        $html = '<li class="pay-item"><div class="input-wrap">
                    <input type="radio" class="pay-check" Value="'.self::SLUG.'" name="pay_option" required="required" id="pay-'.self::SLUG.'" data-msg-required="'.__('Select your payment method.').'">
                    <label class="pay-check-label" for="pay-'.self::SLUG.'"><span class="pay-check-text" title="'.$pmData->details.'">'.$pmData->title.'</span><img class="pay-check-img" src="'.asset('assets/images/pay-paypal.png').'" alt="'.ucfirst(self::SLUG).'"></label>
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
        return ModuleHelper::view('Paypal.views.tnx_details', compact('transaction'));
    }

    public function email_details($transaction){
        $data = json_decode($transaction->extra);
        $pm = get_pm(self::SLUG, true);
        $pay_url = (isset($data->url) ? $data->url : null);
        
        $pay_address = ($pay_url == null ? route('user.token') : '<tr><td>Payment to </td><td>:</td><td><a href="'.$pay_url.'" target="_blank">'.$pm->title.'</a></td></tr>');
    }

    public function create_transaction(Request $request)
    {
    	$helper = new PaypalPay();
    	if(method_exists($helper, 'paypal_pay')){
        	return $helper->paypal_pay($request);
    	}
    	$response['msg'] = 'info';
        $response['message'] = __('messages.nothing');
    	return $response;
    }

    public function payment_address()
    {
        $paypal = PaymentMethod::get_data(self::SLUG);
        $text = $paypal->secret->email;
        return $text;
    }

    public function save_data(Request $request)
    {
    	$response['msg'] = 'info';
        $response['message'] = __('messages.nothing');
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'details' => 'required',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('title')) {
                $message = __($validator->errors()->first(),['attribute' => 'title']);
            } elseif ($validator->errors()->has('details')) {
                $message = __($validator->errors()->first(),['attribute' => 'details']);
            } else {
                $message = __('messages.form.wrong');
            }

            $response['msg'] = 'warning';
            $response['message'] = $message;
        } else {
	        $old = PaymentMethod::get_single_data(self::SLUG);
	        $paypal_data = [
	            'email' => $request->input('email'),
	            'sandbox' => isset($request->sandbox) ? 1 : 0,
	            'clientId' => $request->input('client_id'),
	            'clientSecret' => $request->input('client_secret'),
	            'is_active' => (isset($old->secret) ? $old->secret->is_active : 0)
	        ];
	        $pmp = PaymentMethod::where('payment_method', 'paypal')->first();
	        if (! $pmp) {
	            $pmp = new PaymentMethod();
	            $pmp->payment_method = 'paypal';
	        }
	        $pmp->title = $request->input('title');
	        $pmp->description = $request->input('details');
	        $pmp->status = isset($request->status) ? 'active' : 'inactive';
	        $pmp->data = json_encode($paypal_data);
	            
	        if ($pmp->save()) {
	            $response['msg'] = 'success';
	            $response['message'] = __('messages.update.success', ['what' => 'PayPal payment information']);
	        }else{
	            $response['msg'] = 'error';
	            $response['message'] = __('messages.update.failed', ['what' => 'PayPal payment information']);
	        }
	    }
        return $response;
    }

    public function demo_data()
    {
        $data = [
            'email' => NULL,
            'sandbox' => 0,
            'clientId' => NULL,
            'clientSecret' => NULL,
            'is_active' => 0
        ];
        $template = [
            'order-submit-online-user' => [
                'name' => 'Token Purchase - Order Placed by Online Gateway (USER)',
                'slug' => 'order-submit-online-user',
                'subject' => 'Order placed for Token Purchase #[[order_id]]',
                'greeting' => 'Thank you for your contribution! ',
                'message' => "You have requested to purchase [[token_symbol]] token. Your order has been received and is now being waiting for payment. You order details are show below for your reference. \n\n[[order_details]]\n\nYour token balance will appear in your account as soon as we have confirmed your payment from [[payment_gateway]].\n\nFeel free to contact us if you have any questions. \n ",
                'regards' => "true"
            ],
            'order-canceled-user' => [
                'name' => 'Token Purchase - Order Unpaid/Rejected by Gateway (USER)',
                'slug' => 'order-canceled-user',
                'subject' => 'Unpaid Order Canceled #[[order_id]]',
                'greeting' => 'Hello [[user_name]],',
                'message' => "We noticed that you just tried to purchase [[token_symbol]] token, however we have not received your payment of [[payment_amount]] via [[payment_gateway]] for [[total_tokens]] Token.\n\nIt looks like your payment gateway ([[payment_gateway]]) has been rejected the transaction. \n\n[[order_details]]\n\nIf you want to pay manually, please feel free to contact us via [[support_email]]\n ",
                'regards' => "true"
            ],
            'order-successful-admin' => [
                'name' => 'Token Purchase - Payment Approved by Gateway (ADMIN)',
                'slug' => 'order-successful-admin',
                'subject' => 'Payment Received - Order #[[order_id]]',
                'greeting' => 'Hello Admin,',
                'message' => "You just received a payment of [[payment_amount]] for order (#[[order_id]]) via [[payment_gateway]]. \n\nThis order has now been approved automatically and token balance added to contributor ([[user_email]]) account. \n\n\nPS. Do not reply to this email.  \nThank you.\n ",
                'regards' => "false"
            ],
            'order-rejected-admin' => [
                'name' => 'Token Purchase - Payment Rejected/Canceled by Gateway (ADMIN)',
                'slug' => 'order-rejected-admin',
                'subject' => 'Payment Rejected - Order #[[order_id]]',
                'greeting' => 'Hello Admin,',
                'message' => "The order (#[[order_id]]) has been canceled, however the payment was not successful and [[payment_gateway]] rejected or canceled the transaction. \n\n\n[[order_details]] \n\n\nPS. Do not reply to this email.  \nThank you.\n ",
                'regards' => "false"
            ],
        ];

        foreach ($template as $key => $value) {
            $check = EmailTemplate::where('slug', $key)->count();
            if ($check <= 0) {
                EmailTemplate::create($value);
            }
        }

        if (PaymentMethod::check(self::SLUG)) {
            $paypal = new PaymentMethod();
            $paypal->payment_method = self::SLUG;
            $paypal->title = 'Pay with PayPal';
            $paypal->description = 'You can send your payment using your PayPal account.';
            $paypal->data = json_encode($data);
            $paypal->status = 'inactive';
            $paypal->save();
        }
    }
}
