<?php

namespace App\Http\Controllers\Admin;
/**
 * Payment Method Controller
 *
 * Manage the Method Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.3
 */
use IcoHandler;
use App\Models\Setting;
use App\PayModule\Module;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    public $module;
    public function __construct()
    {
        $this->module = new Module();
        $this->module->init();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public function index()
    {
        $payments = PaymentMethod::get_data();
        $gateway = PaymentMethod::Currency;
        $methods = $this->module->module_views();

        // Init the currency rate 
        $auto_check = get_setting('pmc_auto_rate_' . base_currency(), false);
        if($auto_check == false){
            $auto_rate = (new PaymentMethod())->automatic_rate_check(30, true);
        }

        return view('admin.payments-methods', compact('payments', 'gateway', 'methods'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     * @version 1.0.0
     * @since 1.0
     */
    public function edit($slug=''){
        $method = $this->module->module_views($slug);
        return view('admin.payments-method-edit', compact('method'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     * @version 1.0.0
     * @since 1.0
     */
    public function show(Request $request)
    {
        $type = $request->input('req_type');

        if ($type == 'manage_currency') {
            $gateway = PaymentMethod::Currency;
            return view('modals.pm_manage', compact('gateway'))->render();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @version 1.1
     * @since 1.0
     */
    public function update(Request $request)
    {
        $type = $request->input('req_type');
        $response['msg'] = 'info';
        $response['message'] = __('messages.nothing');

        if ((!starts_with($type, 'currency')) && $type !== null) {
            $response = $this->module->save_module_data($type, $request);
            if($response == false){
                $response['msg'] = 'info';
                $response['message'] = __('messages.wrong');
            }
        }

        if ($type == 'currency_manage') {
            if (base_currency(true) != $request->input('base_currency')) {
                Setting::updateValue('token_default_in_userpanel', base_currency(true));
            }
            Setting::updateValue('site_base_currency', $request->input('base_currency'));
            Setting::updateValue('pm_exchange_method', $request->input('exchange_method'));
            $rate = PaymentMethod::automatic_rate(base_currency(true));
            $check_time = get_setting('pm_exchange_auto_lastcheck', now()->subMinutes(10));
            $current_time = now();
            $all_new_rate = $all_auto_rate = [];

            foreach (PaymentMethod::Currency as $gt => $val) {
                $auto_currency = strtoupper($gt); $cur = strtolower($gt);

                if ($request->input('exchange_method') == 'automatic') {
                    Setting::updateValue('pm_automatic_rate_time', $request->input('automatic_rate_time'));
                    if ((strtotime($check_time)) <= strtotime($current_time)) {
                        Setting::updateValue('pm_exchange_auto_lastcheck', now());
                        $auto_rate = (isset($rate->$auto_currency) ? $rate->$auto_currency : 1);
                        $all_auto_rate[$cur] = $auto_rate;
                        Setting::updateValue('pmc_auto_rate_' .$cur, $auto_rate);
                    }
                }
                $own_rate = $request->input('pmc_rate_' . $cur) == null ? 1 : $request->input('pmc_rate_' . $cur);
                $all_new_rate[$cur] = $own_rate;
                Setting::updateValue('pmc_rate_' . $cur, $own_rate);
            }
            // v1.1.1
            $set_all_rate = ($request->input('exchange_method') == 'automatic') ? $all_auto_rate : $all_new_rate;
            Setting::updateValue( 'pmc_current_rate', json_encode($set_all_rate) );
            Setting::updateValue( 'token_all_price', json_encode(token_calc(1, 'price')) );

            $response['msg'] = 'success';
            $response['message'] = __('messages.update.success', ['what' => 'Payment Currencies']);
        }

        if ($response['msg'] == 'success') {
            $response['link'] = route('admin.payments.setup');
        }

        $response['link'] = null;
        if ($request->ajax()) {
            return response()->json($response);
        }
        return back()->with([$response['msg'] => $response['message']]);
    }

    public function quick_update(Request $request)
    {
        $method = $request->type;
        $response['msg'] = 'info';
        $response['message'] = __('messages.nothing');
        if(!empty($method)){
            $pm = PaymentMethod::where('payment_method', $method)->first();
            if($pm){
                $pm->status = ($pm->status == 'active' ? 'inactive' : 'active');
                if($pm->save()){
                    $response['msg'] = 'info';
                    $response['message'] = __('messages.payment_method_update', ['status' => ($pm->status == 'active' ? 'enabled' : 'disabled') ]);
                }
            }
        }
        return $response;
    }
}
