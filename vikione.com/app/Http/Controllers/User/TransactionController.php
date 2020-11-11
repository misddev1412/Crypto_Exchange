<?php

namespace App\Http\Controllers\User;

/**
 * Transaction Controller
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.0
 */

use Auth;
use Validator;
use App\Models\IcoStage;
use App\PayModule\Module;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Notifications\TnxStatus;
use App\Http\Controllers\Controller;
use App\Models\KYC;
use App\Models\User;
use App\Helpers\TokenCalculate as TC;
use App\Models\Setting;
use App\Notifications\VerifyTransaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
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
        Transaction::where(['user' => auth()->id(), 'status' => 'new'])->delete();

        $trnxs = Transaction::leftJoin('users', 'transactions.user_receive', '=', 'users.id')
            ->where('transactions.user', Auth::id())
            ->where('transactions.status', '!=', 'deleted')
            ->where('transactions.status', '!=', 'new')
            ->whereNotIn('transactions.tnx_type', ['withdraw'])
            ->select('transactions.*', 'users.name', 'users.email')
            ->orderBy('transactions.created_at', 'DESC')->get();
        $transfers = Transaction::get_by_own(['tnx_type' => 'transfer'])->get()->count();
        $referrals = Transaction::get_by_own(['tnx_type' => 'referral'])->get()->count();
        $bonuses   = Transaction::get_by_own(['tnx_type' => 'bonus'])->get()->count();
        $refunds   = Transaction::get_by_own(['tnx_type' => 'refund'])->get()->count();
        $has_trnxs = (object) [
            'transfer' => ($transfers > 0) ? true : false,
            'referral' => ($referrals > 0) ? true : false,
            'bonus' => ($bonuses > 0) ? true : false,
            'refund' => ($refunds > 0) ? true : false
        ];
        return view('user.transactions', compact('trnxs', 'has_trnxs'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     *
     * @throws \Throwable
     */
    public function show(Request $request, $id = '')
    {
        $module = new Module();
        $tid = ($id == '' ? $request->input('tnx_id') : $id);
        if ($tid != null) {
            $tnx = Transaction::find($tid);
            return $module->show_details($tnx);
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     */
    public function destroy(Request $request, $id = '')
    {
        $tid = ($id == '' ? $request->input('tnx_id') : $id);
        if ($tid != null) {
            $tnx = Transaction::FindOrFail($tid);
            if ($tnx) {
                $old = $tnx->status;
                $tnx->status = 'deleted';
                $tnx->save();
                if ($old == 'pending' || $old == 'onhold') {
                    IcoStage::token_add_to_account($tnx, 'sub');
                }
                $ret['msg'] = 'error';
                $ret['message'] = __('messages.delete.delete', ['what' => 'Transaction']);
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = 'This transaction is not available now!';
            }
        } else {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.delete.failed', ['what' => 'Transaction']);
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Send token for User Kycs
     */

    public function send(Request $request)
    {
        $user = Auth::user();
        if ($request->input('otp')) {
            $tranx = Transaction::where('verify_code', $request->input('otp'))->first();
            $tranx->checked_by = json_encode(['name' => Auth::user()->name, 'id' => Auth::id()]);

            $tranx->added_by        = set_added_by(Auth::id(), Auth::user()->role);
            $tranx->checked_time    = now();
            $transStatus            = 'pending';
            // $tranx->status = 'approved';
            $tranx->status          = $transStatus;
            
            $tranx->verify_code         = 0;
            $feeToken                   = $tranx->total_tokens * 0.004;
               
            $user->tokenBalance2        = number_format(((float) $user->tokenBalance2 + $tranx->total_tokens), min_decimal(), '.', '');
            $user->tokenTransaction     = number_format(((float) $user->tokenTransaction - $feeToken), min_decimal(), '.', '');
            $user->save();

            //Update Token for User Receive
            $userReceiver               = User::where(['id' => $tranx->user_receive, 'status' => 'active'])->first();
            if ($tranx->status == 'approved') {
                $userReceiver->tokenBalance = number_format(((float) $userReceiver->tokenBalance - ($tranx->total_tokens - $feeToken)), min_decimal(), '.', '');
                $userReceiver->save();
            }
            
            $iid = $this->create_transaction($userReceiver, $request, -($tranx->total_tokens - $feeToken) , $transStatus, $user, 0);

            if($iid) {
                $tranx->save();
                $ret['msg']     = 'success';
                $ret['message'] = __('messages.trnx.send.success');
                $ret['reload']  = true;
            }
            
        } else {
            $validator = Validator::make($request->all(), [
                'token' => 'required|min:1',
                'email' => 'required',
                'phone' => 'required|string|min:4|max:4',
            ], [
                'tokens.required' => "Token amount is required!.",
                'email.required' => "Email is required!.",
                'phone.required' => "Phone is required!.",
            ]);

            if ($validator->fails()) {
               // if ($validator->errors()->has('token')) {
                //    $msg = $validator->errors()->first();
                //} else {
                    
                //}
				$msg = __('messages.form.wrong');
                $ret['msg'] = 'warning';
                $ret['message'] = $msg;
            } else {
                $emailReceiver  = $request->input('email');
                $phoneReceiver  = $request->input('phone');
                $token          = $request->input('token');
				$feeToken       = $token * 0.004;
				// $token = $token + $feeToken;
				if ($user->tokenBalance2 && (int)$token < $user->tokenBalance2) {
                    //Check user Receiver
                   // $userReceiver = KYC::where('email', $emailReceiver)->where('phone', 'like', '%' . $phoneReceiver)->where('status', 'approved')->first();
                    $userReceiver = KYC::where('email', $emailReceiver)->where('phone', 'like', '%' . $phoneReceiver)->first();
                    if ($userReceiver) {
                        //Check Suppend
                        $verify_code = rand(10000, 99999);
                        $userNotSuppend = User::where(['id' => $userReceiver->userId, 'status' => 'active'])->first();
                        if ($userNotSuppend) {
                            $iid = $this->create_transaction($user, $request, $token, 'onhold', $userNotSuppend, $verify_code, $feeToken);
                            if ($iid != null) {
                                $user->notify(new VerifyTransaction($user,$verify_code));
                                $ret['msg'] = 'info';
                                $ret['message'] = __('messages.trnx.manual.success');
                            } else {

                                $ret['msg'] = 'error';
                                $ret['message'] = __('messages.token.failed');
                                Transaction::where('id', $iid)->delete();
                            }
                        } else {
                            $ret['msg'] = 'warning';
                            $ret['message'] = __('messages.transaction.user_failed', ['what' => 'Transaction']);
                        }
                    } else {
                        $ret['msg'] = 'warning';
                        $ret['message'] = __('messages.transaction.user_failed', ['what' => 'Transaction']);
                    }
                } else {
                    $ret['msg'] = 'warning_token';
                    $ret['message'] = __('messages.transaction.not_enough_tokens_fee', ['what' => 'Transaction']);
                }
            }
        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
    }

    private function create_transaction($user,$request, $token, $status = 'onhold', $userNotSuppend = null, $verify_code = "", $fee = 0)
    {
        $tc = new TC();
        $bonus_calc = false;
        $tnx_type = 'exchange';
        $currency = 'usd';
        $currency_rate = 0;
        $base_currency = strtolower(base_currency());
        $base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
        $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
        $added_time = Carbon::now()->toDateTimeString();
        $tnx_date   = (($request->tnx_date) ? $request->tnx_date : date('m/d/y') ). ' ' . date('H:i');

        $trnx_data = [
            'token' => ($status == 'onhold') ? -round($token, min_decimal()) : round($token, min_decimal()),
            'bonus_on_base' => 0,
            'bonus_on_token' => 0,
            'total_bonus' => 0,
            'total_tokens' => ($status == 'onhold') ? -round($token, min_decimal()) : round($token, min_decimal()),
            'base_price' => 0,
            'amount' => 0,
        ];

        $save_data          = [
            'created_at'            => $added_time,
            'tnx_id'                => set_id(rand(100, 999), 'trnx'),
            'tnx_type'              => $tnx_type,
            'tnx_time'              => ($request->tnx_date) ? _cdate($tnx_date)->toDateTimeString() : $added_time,
            'tokens'                => $trnx_data['token'],
            'bonus_on_base'         => $trnx_data['bonus_on_base'],
            'bonus_on_token'        => $trnx_data['bonus_on_token'],
            'total_bonus'           => $trnx_data['total_bonus'],
            'total_tokens'          => $trnx_data['total_tokens'],
            'stage'                 => 0,
            'user'                  => (int)$user->id,
            'user_receive'          => ($status == 'onhold') ? ($userNotSuppend ? (int) $userNotSuppend->id : 0) : 0,
            'amount'                => $trnx_data['amount'],
            'receive_amount'        => $trnx_data['amount'],
            'receive_currency'      => $currency,
            'base_amount'           => $trnx_data['base_price'],
            'base_currency'         => $base_currency,
            'base_currency_rate'    => $base_currency_rate,
            'currency'              => $currency,
            'currency_rate'         => $currency_rate,
            'all_currency_rate'     => $all_currency_rate,
            'payment_method'        => $request->input('payment_method', 'manual'),
            'payment_to'            => '',
            'payment_id'            => rand(1000, 9999),
            'details'               => ($status == 'onhold') ? (__('Receiver: ') . '<i><b>' . $userNotSuppend->name .'</b></i>') : (__('Received from: ') .  '<i><b>' . $userNotSuppend->name .'</b></i>'),
            'status'                => $status,
            'verify_code'           => ($status == 'onhold') ? $verify_code : 0,
            'fee'                   => $fee
        ];

        $iid = Transaction::insertGetId($save_data);

        return $iid;
    }

    /**
    *     _
    * .__(.)< (MEOW)
    * \___)
    * Author : Thinh Nguyen
    * Created at : 2020-11-06 11:46:00
    * Function todo : 
    */
    public function paymentShow(Request $request)
    {
        $data       = [
            'tnxId' => $request->tnx_id
        ];
        $response   = [
            'view'      => view('user.payment.info', $data)->render(),
            'status'    => 1
        ];

        return response()->json($response);
    }

    /**
    *     _
    * .__(.)< (MEOW)
    * \___)
    * Author : Thinh Nguyen
    * Created at : 2020-11-06 14:33:09
    * Function todo : 
    */
    public function paymentMethod(Request $request)
    {
        $data       = [
            'tnxId' => $request->tnx_id
        ];
        
        switch ($request->method) {
            case "banking":
                $view   = view('user.payment.banking', $data)->render();
                break;
        }

        

        $response   = [
            'view'      => $view,
            'status'    => 1
        ];

        return response()->json($response);
    }
}
