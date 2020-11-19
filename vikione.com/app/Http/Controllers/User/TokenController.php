<?php

namespace App\Http\Controllers\User;

/**
 * Token Controller
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.5
 */
use Auth;
use Validator;
use IcoHandler;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\IcoStage;
use App\PayModule\Module;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Notifications\TnxStatus;
use App\Http\Controllers\Controller;
use App\Helpers\TokenCalculate as TC;
use GuzzleHttp\Client;

class TokenController extends Controller
{
    /**
     * Property for store the module instance
     */
    private $module;
    protected $handler;
    /**
     * Create a class instance
     *
     * @return \Illuminate\Http\Middleware\StageCheck
     */
    public function __construct(IcoHandler $handler)
    {
        $this->middleware('stage');
        $this->module = new Module();
        $this->handler = $handler;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function index()
    {
        if (token('before_kyc') == '1') {
            $check = User::find(Auth::id());
            if ($check && !isset($check->kyc_info->status)) {
                return redirect(route('user.kyc'))->with(['warning' => __('messages.kyc.mandatory')]);
            } else {
                if ($check->kyc_info->status != 'approved') {
                    return redirect(route('user.kyc.application'))->with(['warning' => __('messages.kyc.mandatory')]);
                }
            }
        }

        $stage = active_stage();
        $tc = new TC();
        $currencies = Setting::active_currency();
        $currencies['base'] = base_currency();
        $bonus = $tc->get_current_bonus(null);
        $bonus_amount = $tc->get_current_bonus('amount');
        $price = Setting::exchange_rate($tc->get_current_price());
        $minimum = $tc->get_current_price('min');
        $active_bonus = $tc->get_current_bonus('active');
        $pm_currency = PaymentMethod::Currency;
        $pm_active = PaymentMethod::where('status', 'active')->get();
        $token_prices = $tc->calc_token(1, 'price');
        $is_price_show = token('price_show');
        $contribution = Transaction::user_contribution();

        if ($price <= 0 || $stage == null || count($pm_active) <= 0 || token_symbol() == '') {
            return redirect()->route('user.home')->with(['info' => __('messages.ico_not_setup')]);
        }

        return view(
            'user.token',
            compact('stage', 'currencies', 'bonus', 'bonus_amount', 'price', 'token_prices', 'is_price_show', 'minimum', 'active_bonus', 'pm_currency', 'contribution')
        );
    }

    /**
     * Access the confirm and count
     *
     * @version 1.1
     * @since 1.0
     * @return void
     * @throws \Throwable
     */
    public function access(Request $request)
    {
        $tc = new TC();
        $get = $request->input('req_type');
        $min = $tc->get_current_price('min');
        $currency = $request->input('currency');
        $token = (float) $request->input('token_amount');
        $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="tranx-popup"><h3>' . __('messages.trnx.wrong') . '</h3></div>';
        $_data = [];
		 /**Check Template Rex*/
        if (!empty($token) && $token >= $min) {
                    $_data = (object) [
                        'currency' => $currency,
                        'currency_rate' => Setting::exchange_rate($tc->get_current_price(), $currency),
                        'token' => round($token, min_decimal()),
                        'bonus_on_base' => $tc->calc_token($token, 'bonus-base'),
                        'bonus_on_token' => $tc->calc_token($token, 'bonus-token'),
                        'total_bonus' => $tc->calc_token($token, 'bonus'),
                        'total_tokens' => $tc->calc_token($token),
                        'base_price' => $tc->calc_token($token, 'price')->base,
                        'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
                    ];
                }
                if ($this->check($token)) {
                    if ($token < $min || $token == null) {
                        $ret['opt'] = 'true';
                        $ret['modal'] = view('modals.payment-amount', compact('currency', 'get'))->render();
                    } else {
                        $ret['opt'] = 'static';
                        $ret['ex'] = [$currency, $_data];
                        $ret['modal'] = $this->module->show_module($currency, $_data);
                    }
                } else {
                    $msg = $this->check(0, 'err');
                    $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="popup-body"><h3 class="alert alert-danger text-center">'.$msg.'</h3></div>';
                }
            
        
        
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Make Payment
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function payment(Request $request)
    {
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        $validator = Validator::make($request->all(), [
            'agree' => 'required',
            'pp_token' => 'required',
            'pp_currency' => 'required',
            'pay_option' => 'required',
        ], [
            'pp_currency.required' => __('messages.trnx.require_currency'),
            'pp_token.required' => __('messages.trnx.require_token'),
            'pay_option.required' => __('messages.trnx.select_method'),
            'agree.required' => __('messages.agree')
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->hasAny(['agree', 'pp_currency', 'pp_token', 'pay_option'])) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        }else{
			 /**Check Template Rex*/
            $type = strtolower($request->input('pp_currency'));
            $method = strtolower($request->input('pay_option'));

            return $this->module->make_payment($method, $request);

        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Check the state
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    private function check($token, $extra = '')
    {
        $tc = new TC();
        $stg = active_stage();
        $min = $tc->get_current_price('min');
        $available_token = ( (double) $stg->total_tokens - ($stg->soldout + $stg->soldlock) );
        $symbol = token_symbol();

        if ($extra == 'err') {
            if ($token >= $min && $token <= $stg->max_purchase) {
                if ($token >= $min && $token > $stg->max_purchase) {
                    return __('Maximum amount reached, You can purchase maximum :amount :symbol per transaction.', ['amount' => $stg->max_purchase, 'symbol' =>$symbol]);
                } else {
                    return __('You must purchase minimum :amount :symbol.', ['amount' => $min, 'symbol' =>$symbol]);
                }
            } else {
                if($available_token < $min) {
                    return __('Our sales has been finished. Thank you very much for your interest.');
                } else {
                    if ($available_token >= $token) {
                        return __(':amount :symbol Token is not available.', ['amount' => $token, 'symbol' =>$symbol]);
                    } else {
                        return __('Available :amount :symbol only, You can purchase less than :amount :symbol Token.', ['amount' => $available_token, 'symbol' =>$symbol]);
                    }
                }
            }
        } else {
            if ($token >= $min && $token <= $stg->max_purchase) {
                if ($available_token >= $token) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }


    /**
     * Payment Cancel
     *
     * @version 1.0.0
     * @since 1.0.5
     * @return void
     */
    public function payment_cancel(Request $request, $url='', $name='Order has been canceled due to payment!')
    {
        if ($request->get('tnx_id') || $request->get('token')) {
            $id = $request->get('tnx_id');
            $pay_token = $request->get('token');
            if($pay_token != null){
                $pay_token = (starts_with($pay_token, 'EC-') ? str_replace('EC-', '', $pay_token) : $pay_token);
            }
            $apv_name = ucfirst($url);
            if(!empty($id)){
                $tnx = Transaction::where('id', $id)->first();
            }elseif(!empty($pay_token)){
                $tnx = Transaction::where('payment_id', $pay_token)->first();
                if(empty($tnx)){
                    $tnx =Transaction::where('extra', 'like', '%'.$pay_token.'%')->first();
                }
            }else{
                return redirect(route('user.token'))->with(['danger'=>"Sorry, we're unable to proceed the transaction. This transaction may deleted. Please contact with administrator.", 'modal'=>'danger']);
            }
            if($tnx){
                $_old_status = $tnx->status;
                if($_old_status == 'deleted' || $_old_status == 'canceled'){
                    $name = "Your transaction is already ".$_old_status.". Sorry, we're unable to proceed the transaction.";
                }elseif($_old_status == 'approved'){
                    $name = "Your transaction is already ".$_old_status.". Please check your account balance.";
                }elseif(!empty($tnx) && ($tnx->status == 'pending' || $tnx->status == 'onhold') && $tnx->user == auth()->id()) {
                    $tnx->status = 'canceled';
                    $tnx->checked_by = json_encode(['name'=>$apv_name, 'id'=>$pay_token]);
                    $tnx->checked_time = Carbon::now()->toDateTimeString();
                    $tnx->save();
                    IcoStage::token_add_to_account($tnx, 'sub');
                    $tnx->tnxUser->notify((new TnxStatus($tnx, 'canceled-user')));
                    if(get_emailt('order-rejected-admin', 'notify') == 1){
                        notify_admin($tnx, 'rejected-admin');
                    }
                }
            }else{
                $name = "Transaction is not found!!";
            }
        }else{
            $name = "Transaction id or key is not valid!";
        }
        return redirect(route('user.token'))->with(['danger'=>$name, 'modal'=>'danger']);
    }


    
}
