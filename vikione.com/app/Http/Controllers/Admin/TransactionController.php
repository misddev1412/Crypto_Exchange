<?php

namespace App\Http\Controllers\Admin;
/**
 * Transactions Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.0
 */
use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\IcoStage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Helpers\ReferralHelper;
use App\Notifications\TnxStatus;
use App\Notifications\Refund;
use App\Http\Controllers\Controller;
use App\Helpers\TokenCalculate as TC;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public function index(Request $request, $status = '')
    {
        $per_page = gmvl('tnx_per_page', 10);
        $order_by = gmvl('tnx_order_by', 'id');
        $ordered  = gmvl('tnx_ordered', 'DESC');

        if($status=='referral' || $status=='bonus') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->where('tnx_type', $status)->orderBy($order_by, $ordered)->paginate($per_page);
        } elseif($status=='bonuses') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->whereIn('tnx_type', ['referral', 'bonus'])->orderBy($order_by, $ordered)->paginate($per_page);
        } elseif($status=='approved') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw', 'bonus', 'referral'])->where('status', $status)->orderBy($order_by, $ordered)->paginate($per_page);
        }  elseif($status=='pending') {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->whereIn('status', [$status, 'onhold'])->orderBy($order_by, $ordered)->paginate($per_page);
        } elseif($status!=null) {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->where('status', $status)->orderBy($order_by, $ordered)->paginate($per_page);
        } else {
            $trnxs = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->orderBy($order_by, $ordered)->paginate($per_page);
        }

        // Advance search v1.1.0
        if($request->s){
            $trnxs  = Transaction::AdvancedFilter($request)
                                ->orderBy($order_by, $ordered)->paginate($per_page);
        }
        if($request->filter){
            $trnxs = Transaction::AdvancedFilter($request)
                                ->orderBy($order_by, $ordered)->paginate($per_page);
        }

        $is_page = (empty($status) ? 'all' : $status);
        $pmethods = PaymentMethod::where('status', 'active')->get();
        $gateway = PaymentMethod::all()->pluck('payment_method');
        $stages = IcoStage::whereNotIn('status', ['deleted'])->get();
        $pm_currency = PaymentMethod::Currency;
        $users = User::where('status', 'active')->whereNotNull('email_verified_at')->where('role', '!=', 'admin')->get();
        $pagi = $trnxs->appends(request()->all());
        return view('admin.transactions', compact('trnxs', 'users', 'stages', 'pmethods', 'pm_currency', 'gateway', 'is_page', 'pagi'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $trnx_id
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     * @version 1.0.0
     * @since 1.0
     */
    public function show($trnx_id = '')
    {
        if ($trnx_id == '') {
            return __('messages.wrong');
        } else {
            $trnx = Transaction::FindOrFail($trnx_id);
            return view('admin.trnx_details', compact('trnx'))->render();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @version 1.1.0
     * @since 1.0
     */
    public function update(Request $request)
    {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }

        $type = $request->input('req_type');
        $id = $request->input('tnx_id');
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        $trnx = Transaction::findOrFail($id);
        if ($trnx) {
            $status = $trnx->status;
            if($status == 'approved') {                
                $ret['msg'] = 'info';
                $ret['message'] = __('messages.trnx.admin.already_approved');
            } else {
                if( $status == 'canceled' && in_array($type, ['approved', 'deleted']) ) {
                    if($type == 'deleted') {
                        $ret = $this->deleted_tnx($trnx);
                    } elseif($type == 'approved') {
                        $ret = $this->approved_tnx($trnx, $request);
                    }
                }elseif(in_array($status, ['onhold', 'pending']) && in_array($type, ['approved', 'canceled']) ){
                    if( $type == 'approved' ){
                        $ret = $this->approved_tnx($trnx, $request);
                    }elseif($type == 'canceled'){
                        $ret = $this->canceled_tnx($trnx, $request);
                    }
                }
            }
        }

        if($type == 'refund' && $trnx){
            $refund = $this->refund($trnx, $request->input('message'));
            if($refund){
                $ret['refund'] = $refund;
                $ret['msg'] = 'success';
                $ret['reload'] = true;
                $ret['message'] = __('Refund Successful!');
            }else{
                $ret['msg'] = 'warning';
                $ret['message'] = __('Already Refunded!');
            }
        }

        $ret['data'] = $trnx;
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Cancel the Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.4
     */
    private function canceled_tnx($trnx, $request)
    {
        $ret['msg'] = 'warning';
        $ret['message'] = __('Unable to cancel the transaction, reload the page.');
        if ($trnx) {
            if($trnx->status == 'canceled' || $trnx->status == 'approved') {
                $ret['msg'] = 'info';
                $ret['message'] = ($trnx->status == 'approved') ? __('messages.trnx.admin.already_approved') : __('messages.trnx.admin.already_canceled');
                 return $ret;
            }

            if(in_array($trnx->status, ['onhold', 'pending'])){
                $trnx->status = 'canceled';
                $trnx->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                $trnx->checked_time = date('Y-m-d H:i:s');
                $trnx->save();
                IcoStage::token_add_to_account($trnx, 'sub');
                
                try {
                    $trnx->tnxUser->notify((new TnxStatus($trnx, 'rejected-user')));
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.trnx.admin.canceled');
                } catch (\Exception $e) {
                    $ret['errors'] = $e->getMessage();
                    $ret['msg'] = 'warning';
                    $ret['message'] = __('messages.trnx.admin.canceled').' '.__('messages.email.failed');
                }
            }
        }
        return $ret;
    }

    /**
     * Approve the Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.4
     */
    private function approved_tnx($trnx, $request)
    {
        $ret['msg'] = 'warning';
        $ret['message'] = __('Unable to approve the transaction, reload the page.');

        if($trnx->status == 'deleted' || $trnx->status == 'approved') {
            $ret['msg'] = 'info';
            $ret['message'] = __('messages.trnx.admin.already_updated', ['status' => $trnx->status]);
            return $ret;
        }

        if ($trnx) {
            $msg = __('messages.form.wrong');
            $validator = Validator::make($request->all(), [ 'amount' => 'gt:0' ]);
            if ($validator->fails()) {
                if ($validator->errors()->has('amount')) {
                    $msg = $validator->errors()->first();
                }
                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
            } else {
                $chk_adjust = $request->input('chk_adjust');
                $receive_amount = round($request->input('amount'), max_decimal());
                $adjust_token = round($request->input('adjusted_token'), min_decimal());
                $token = round($request->input('token'), min_decimal());
                $base_bonus = round($request->input('base_bonus'), min_decimal());
                $token_bonus = round($request->input('token_bonus'), min_decimal());
                
                if(in_array($trnx->status, ['onhold', 'pending', 'canceled'])){
                    $old_status = $trnx->status;

                    if ($chk_adjust == 1) {
                        $old_tokens = $trnx->total_tokens;
                        $old_base_amount = $trnx->base_amount;
                        $trnx->tokens = $token;
                        $trnx->base_amount = $token * $trnx->base_currency_rate;
                        $trnx->total_bonus = $base_bonus + $token_bonus;
                        $trnx->bonus_on_base = $base_bonus;
                        $trnx->bonus_on_token = $token_bonus;
                        $trnx->total_tokens = $adjust_token;
                        $trnx->amount = $receive_amount;

                        if ($old_status != 'canceled') {
                            $adjust_stage_token = $old_tokens - $trnx->total_tokens;
                            $adjust_base_amount = $old_base_amount - $trnx->base_amount;

                            if ($adjust_stage_token < 0) {
                                IcoStage::token_adjust_to_stage($trnx, abs($adjust_stage_token), abs($adjust_base_amount), 'add');
                            } elseif ($adjust_stage_token > 0) {
                                IcoStage::token_adjust_to_stage($trnx, abs($adjust_stage_token), abs($adjust_base_amount), 'sub');
                            }
                        }
                    }

                    $trnx->receive_currency = $trnx->currency;
                    $trnx->receive_amount = $receive_amount;
                    $trnx->status = 'approved';
                    $trnx->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                    $trnx->checked_time = date('Y-m-d H:i:s');
                    $trnx->save();

                    IcoStage::token_add_to_account($trnx, null, 'add'); // user

                    if($trnx->status == 'approved' && is_active_referral_system()){
                        $referral = new ReferralHelper($trnx);
                        $referral->addToken('refer_to');
                        $referral->addToken('refer_by');
                    }

                    if ($old_status == 'canceled') {
                        IcoStage::token_add_to_account($trnx, 'add'); // stage
                    }

                    try {
                        $trnx->tnxUser->notify((new TnxStatus($trnx, 'successful-user')));
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.trnx.admin.approved');
                    } catch (\Exception $e) {
                        $ret['errors'] = $e->getMessage();
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.trnx.admin.approved').' '.__('messages.email.failed');
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Delete the Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.4
     */
    private function deleted_tnx($trnx)
    {
        $ret['msg'] = 'warning';
        $ret['message'] = __('Unable to delete the transaction, reload the page.');

        if ($trnx) {
            if($trnx->status == 'deleted' || $trnx->status == 'approved') {
                $ret['msg'] = 'info';
                $ret['message'] = ($trnx->status == 'approved') ? __('messages.trnx.admin.already_approved') : __('messages.trnx.admin.already_deleted');
                return $ret;
            }
            if($trnx->status == 'canceled') {
                $trnx->status = 'deleted';
                $trnx->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);
                $trnx->checked_time = date('Y-m-d H:i:s');
                $trnx->save();
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.trnx.admin.deleted');
            } else {
                $ret['msg'] = 'info';
                $ret['message'] = __('Cancel the transaction first.');
            }
        }
        return $ret;
    }

    /**
     * Create Refund Transaction by Admin
     *
     * @version 1.0.0
     * @since 1.1.2
     */
    protected function refund(Transaction $transaction, $message = '')
    {
        if(empty($transaction->refund)){
            $refund = new Transaction();
            $refund->fill($transaction->only([
                'tnx_id', 'tnx_type', 'tnx_time', 'tokens', 'bonus_on_base', 'bonus_on_token', 'total_bonus', 'total_tokens', 'stage', 'user', 'amount', 'receive_amount', 'receive_currency', 'base_amount', 'base_currency', 'base_currency_rate', 'currency', 'currency_rate', 'all_currency_rate', 'wallet_address', 'payment_method', 'payment_id', 'payment_to', 'checked_by', 'added_by', 'checked_time', 'status', 'dist'
            ]))->save();
            IcoStage::token_add_to_account($transaction, 'sub');
            IcoStage::token_add_to_account($transaction, null, 'sub');
            $refund->fill([
                'tnx_id' => set_id($refund->id, 'refund'),
                'tnx_type' => 'refund',
                'tnx_time'=> now()->toDateTimeString(),
                'total_tokens' => (- $transaction->total_tokens),
                'amount' => (- $transaction->amount),
                'receive_amount' => (- $transaction->receive_amount),
                'base_amount' => (- $transaction->base_amount),
                'checked_by' => json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]),
                'added_by' => set_added_by(Auth::id(), Auth::user()->role),
                'details' => 'Refund for #'.$transaction->tnx_id,
                'extra' => json_encode(['trnx' => $transaction->id, 'message' => $message])
            ])->save();
            $transaction->refund = $refund->id;
            $transaction->save();
            $this->refund_email($refund, $transaction);
            return $refund;
        }else{
            return false;
        }
    }

    /**
     * Refund Email sent to user.
     *
     * @version 1.0.0
     * @since 1.1.2
     */
    protected function refund_email($refund, $transaction)
    {
        try {
            $refund->tnxUser->notify(new Refund($refund, $transaction));
            return true;
        } catch (\Exception $e) {
            // info($e->getMessage());
            return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public function store(Request $request)
    {
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');
        $validator = Validator::make($request->all(), [
            'total_tokens' => 'required|integer|min:1',
        ], [
            'total_tokens.required' => "Token amount is required!.",
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('total_tokens')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $this->affiliate(User::where('id',(int) $request->input('user'))->first(), $request->input('total_tokens'), 1);
            $tc = new TC();
            $token = $request->input('total_tokens');
            $bonus_calc = isset($request->bonus_calc) ? true : false;
            $tnx_type = $request->input('type');
            $currency = strtolower($request->input('currency'));
            $currency_rate = Setting::exchange_rate($tc->get_current_price(), $currency);
            $base_currency = strtolower(base_currency());
            $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
            $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
            $added_time = Carbon::now()->toDateTimeString();
            $tnx_date   = $request->tnx_date.' '.date('H:i');

            // v1.2
            if($tnx_type=='purchase' && $bonus_calc==true) {
                $trnx_data = [
                    'token' => round($token, min_decimal()),
                    'bonus_on_base' => $tc->calc_token($token, 'bonus-base'),
                    'bonus_on_token' => $tc->calc_token($token, 'bonus-token'),
                    'total_bonus' => $tc->calc_token($token, 'bonus'),
                    'total_tokens' => $tc->calc_token($token),
                    'base_price' => $tc->calc_token($token, 'price')->base,
                    'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
                ];
            } else {
                $trnx_data = [
                    'token' => round($token, min_decimal()),
                    'bonus_on_base' => 0,
                    'bonus_on_token' => 0,
                    'total_bonus' => 0,
                    'total_tokens' => round($token, min_decimal()),
                    'base_price' => $tc->calc_token($token, 'price')->base,
                    'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
                ];
            }
            $save_data = [
                'created_at' => $added_time,
                'tnx_id' => set_id(rand(100, 999), 'trnx'),
                'tnx_type' => $tnx_type,
                'tnx_time' => ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time,
                'tokens' => $trnx_data['token'],
                'bonus_on_base' => $trnx_data['bonus_on_base'],
                'bonus_on_token' => $trnx_data['bonus_on_token'],
                'total_bonus' => $trnx_data['total_bonus'],
                'total_tokens' => $trnx_data['total_tokens'],
                'stage' => (int) $request->input('stage', active_stage()->id),
                'user' => (int) $request->input('user'),
                'user_receive' => 0,
                'amount' => $trnx_data['amount'],
                'receive_amount' => $request->input('amount') != '' ? $request->input('amount') : $trnx_data['amount'],
                'receive_currency' => $currency,
                'base_amount' => $trnx_data['base_price'],
                'base_currency' => $base_currency,
                'base_currency_rate' => $base_currency_rate,
                'currency' => $currency,
                'currency_rate' => $currency_rate,
                'all_currency_rate' => $all_currency_rate,
                'payment_method' => $request->input('payment_method', 'manual'),
                'payment_to' => '',
                'payment_id' => rand(1000, 9999),
                'details' => ($tnx_type =='bonus' ? 'Bonus Token' : 'Token Purchase'),
                'status' => 'onhold',
                'verify_code' => 0,
                'point' => 0
            ];

            $iid = Transaction::insertGetId($save_data);

            if ($iid != null) {
                $ret['msg'] = 'info';
                $ret['message'] = __('messages.trnx.manual.success');

                $address = $request->input('wallet_address');
                $transaction = Transaction::where('id', $iid)->first();
                $transaction->tnx_id = set_id($iid, 'trnx');
                $transaction->wallet_address = $address;
                $transaction->extra = ($address) ? json_encode(['address' => $address]) : null;
                $transaction->status = 'approved';
                $transaction->save();

                IcoStage::token_add_to_account($transaction, 'add');

                $transaction->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);

                $transaction->added_by = set_added_by(Auth::id(), Auth::user()->role);
                $transaction->checked_time = now();
                $transaction->save();
                // Start adding
                IcoStage::token_add_to_account($transaction, '', 'add');

                $ret['link'] = route('admin.transactions');
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.token.success');
            } else {
                $ret['msg'] = 'error';
                $ret['message'] = __('messages.token.failed');
                Transaction::where('id', $iid)->delete();
            }
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
    * Adjustment modal function for token verified.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * @version 1.0.0
    * @since 1.0
    * @return void
    */

    public function adjustment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tnx_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('tnx_id')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $trnx = Transaction::findOrFail($request->tnx_id);
            $ret['modal'] = view('modals.adjustment_token', compact('trnx'))->render();
        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    //Check
    private function affiliate($user,$tokenAffiliate = 0,$floor = 1)
    { 
        
        if($user->referral) {
            //Get Info Ref
            $userRefed = User::where('id',$user->referral)->first();
           
            if($userRefed) { 
                if($userRefed->affiliate != 'normal') {
                    $floorUser = $this->getRefFloor($userRefed->id);
                    if($floorUser >= $floor) {
                       if($floor == 1) {
                            //Direct
                            $fee = $tokenAffiliate * (get_setting('affiliate_'. $userRefed->affiliate . '_direct') / 100);
                            $this->create_transaction($userRefed,'point',$fee);
                            $userRefed->tokenPoint = $userRefed->tokenPoint + $fee;
                            $userRefed->affiliate_point = $userRefed->affiliate_point + $fee;
                            $userRefed->save();
                        } else {
                            //Indirect
                            $fee = $tokenAffiliate * (get_setting('affiliate_'. $userRefed->affiliate . '_indirect') / 100);
                            $this->create_transaction($userRefed,'token',$fee);
                            $userRefed->tokenBalance2 = $userRefed->tokenBalance2 + $fee;
                            $userRefed->affiliate_token = $userRefed->affiliate_token + $fee;
                            $userRefed->save();
                        }
                        
                    }    
                }
                if($userRefed && $userRefed->referral) {
                    $this->affiliate($userRefed, $tokenAffiliate, ++$floor);
                   
                }
            }
            
        }
    }

    private function getRefFloor($uid) {
        $floor = 0;
        $users = Referral::where('refer_by', $uid)->leftJoin('users', 'users.id', '=','referrals.user_id')
        ->select('referrals.*', 'users.name', 'users.email','users.id','users.affiliate')->get();
        $silver = 0;
        foreach($users as $user) {
            if($user->affiliate != 'normal') {
                $silver++;
            }
        }

        if($silver >= 9) {
            $floor = 18;
        } elseif($silver >= 8) {
            $floor = 16;
        } elseif($silver >= 7) {
            $floor = 14;
        } elseif($silver >= 6) {
            $floor = 12;
        } elseif($silver >= 5) {
            $floor = 10;
        } elseif($silver >= 4) {
            $floor = 8;
        } elseif($silver >= 3) {
            $floor = 6;
        } elseif($silver >= 2) {
            $floor = 4;
        } elseif($silver >= 1) {
            $floor = 2;
        }
    
        return $floor;
      }

    private function create_transaction($user,$type = 'point', $token = 0)
    {
          $tc = new TC();
          $bonus_calc = false;
          $tnx_type = 'affiliate';
          $currency = 'usd';
          $currency_rate = 0;
          $base_currency = strtolower(base_currency());
          $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
          $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
          $added_time = Carbon::now()->toDateTimeString();
          $tnx_date   = date('m/d/y') . ' ' . date('H:i');
         
          $trnx_data = [
              'bonus_on_base' => 0,
              'bonus_on_token' => 0,
              'total_bonus' => 0,
              'base_price' => 0,
              'amount' => 0,
          ];
           
          if($type == 'point') {
            $trnx_data['point'] = round($token, min_decimal());
            $trnx_data['token'] = 0;
            $trnx_data['total_tokens'] = 0;
          } else {
            $trnx_data['point'] = 0;
            $trnx_data['token'] = round($token, min_decimal());
            $trnx_data['total_tokens'] = round($token, min_decimal());
          }
  
          $save_data = [
              'created_at' => $added_time,
              'tnx_id' => set_id(rand(100, 999), 'trnx'),
              'tnx_type' => $tnx_type,
              'tnx_time' => $added_time,
              'tokens' => $trnx_data['token'],
              'bonus_on_base' => $trnx_data['bonus_on_base'],
              'bonus_on_token' => $trnx_data['bonus_on_token'],
              'total_bonus' => $trnx_data['total_bonus'],
              'total_tokens' => $trnx_data['total_tokens'],
              'stage' => 0,
              'user' => (int)$user->id,
              'user_receive' => 0,
              'amount' => $trnx_data['amount'],
              'receive_amount' => $trnx_data['amount'],
              'receive_currency' => $currency,
              'base_amount' => $trnx_data['base_price'],
              'base_currency' => $base_currency,
              'base_currency_rate' => $base_currency_rate,
              'currency' => $currency,
              'currency_rate' => $currency_rate,
              'all_currency_rate' => $all_currency_rate,
              'payment_method' => 'affiliate',
              'payment_to' => '',
              'payment_id' => 0,
              'details' => 'Affiliate',
              'status' => 'approved',
              'verify_code' => 0,
              'point' => $trnx_data['point']
          ];
  
          $iid = Transaction::insertGetId($save_data);
  
          return $iid;
    }

    /**
    *     _
    * .__(.)< (MEOW)
    * \___)
    * Author : Thinh Nguyen
    * Created at : 2020-11-06 16:47:28
    * Function todo : 
    */
    public function approve(Request $request)
    {
        $transactionDetail = Transaction::where('id', $request->trans_id)->first();
        if (!$transactionDetail) {
            return abort(404);
        } else {
            $userReceiver               = User::where(['id' => $transactionDetail->user_receive, 'status' => 'active'])->first();
            $userReceiver->tokenBalance = number_format(((float) $userReceiver->tokenBalance - $transactionDetail->total_tokens), min_decimal(), '.', '');
            $userReceiver->save();

            $userSender                 = User::where(['id' => $transactionDetail->user, 'status' => 'active'])->first();
            $tokenRollback              = $transactionDetail->total_tokens * 0.7; 
            $userSender->tokenBalance   = number_format(((float) $userSender->tokenBalance -  $tokenRollback), min_decimal(), '.', '');
            $userSender->save();

            $dataResponse               = [
                'status'                    => 1
            ];
            Transaction::where('id', $request->trans_id)->update(['status' => 'approved']);
            return response()->json($dataResponse);
        }
    }

    /**
    *     _
    * .__(.)< (MEOW)
    * \___)
    * Author : Thinh Nguyen
    * Created at : 2020-11-07 11:03:56
    * Function todo : 
    */
    public function cancel(Request $request)
    {
        $transactionDetail = Transaction::where('id', $request->trans_id)->first();
        if (!$transactionDetail) {
            return abort(404);
        } else {
            $userSender                 = User::where(['id' => $transactionDetail->user, 'status' => 'active'])->first();
            $userSender->tokenBalance2  = number_format(((float) $userSender->tokenBalance2 - $transactionDetail->total_tokens), min_decimal(), '.', '');
            $userSender->save();

            $dataResponse               = [
                'status'                    => 1
            ];

            Transaction::where('id', $request->trans_id)->update(['status' => 'canceled']);
            return response()->json($dataResponse);
        }
    }
}
