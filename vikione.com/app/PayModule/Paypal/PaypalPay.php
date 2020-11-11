<?php
namespace App\PayModule\Paypal;

/**
 * PaypalPay Helper Class
 */

use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\TnxStatus;
use App\Helpers\TokenCalculate as TC;
// Models
use App\Models\User;
use App\Models\Setting;
use App\Models\IcoStage;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Helpers\ReferralHelper;
// Paypal
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\Details;
use PayPal\Rest\ApiContext;
use PayPal\Api\Transaction as PaypalTrnx;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;

class PaypalPay
{
	/**
     * The attributes that are mass assignable.
     *
     * @var string
     */
	
    public function apiContext()
    {
    	$apiContext = new ApiContext(
	        new OAuthTokenCredential(
	            PaymentMethod::get_data('paypal')->clientId,     // ClientID
	            PaymentMethod::get_data('paypal')->clientSecret      // ClientSecret
	        )
		);
        if(PaymentMethod::get_data('paypal')->sandbox == 0){
			$apiContext->setConfig([
		        'mode' => 'live',
		    ]);
		}
		return $apiContext;
    }

    

	/**
	 * Make Payment via PayPal
	 *
     * @version 1.0.0
     * @since 1.0.2
     * @return void
     */
    public function paypal_pay(Request $request)
    {
    	if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', get_setting('token_decimal_max', 8));
            ini_set('serialize_precision', -1);
        }
    	$tc = new TC();
    	$token = $request->input('pp_token');
    	$currency = strtolower($request->input('pp_currency'));
    	$currency_rate = Setting::exchange_rate($tc->get_current_price(), $currency);
    	$base_currency = strtolower(base_currency());
    	$base_currency_rate = Setting::exchange_rate($tc->get_current_price(), $base_currency);
        $all_currency_rate = json_encode(Setting::exchange_rate($tc->get_current_price(), 'except'));
    	$trnx_data = [
    		'token' => round($token, min_decimal()),
    		'bonus_on_base' => round($tc->calc_token($token, 'bonus-base'), min_decimal()),
    		'bonus_on_token' => round($tc->calc_token($token, 'bonus-token'), min_decimal()),
    		'total_bonus' => round($tc->calc_token($token, 'bonus'), min_decimal()),
    		'total_tokens' => round($tc->calc_token($token), min_decimal()),
    		'base_price' => round($tc->calc_token($token, 'price')->base, max_decimal()),
    		'amount' => round($tc->calc_token($token, 'price')->$currency, max_decimal()),
    	];

    	$payer = new \PayPal\Api\Payer();
		$payer->setPaymentMethod('paypal');

		$amount = new \PayPal\Api\Amount();
		$amount->setTotal($trnx_data['amount']);
		$amount->setCurrency(strtoupper($currency));

		$transaction = new \PayPal\Api\Transaction();
		$transaction->setAmount($amount);
		// $transaction->setInvoiceNumber($amount);

		$redirectUrls = new \PayPal\Api\RedirectUrls();
		$redirectUrls->setReturnUrl(route('payment.paypal.success'))
		    ->setCancelUrl(route('payment.paypal.cancel'));

		$payment = new \PayPal\Api\Payment();
		$payment->setIntent('sale') // sale | order
		    ->setPayer($payer)
		    ->setTransactions(array($transaction))
		    ->setRedirectUrls($redirectUrls);

		try {
		    $payment->create($this->apiContext());
		    // Start Insert to Transaction
		    
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
                'all_currency_rate' => $all_currency_rate,
		    	'payment_method' => 'paypal',
		    	'receive_currency' => strtolower($payment->transactions[0]->amount->currency),
		    	'payment_to' => get_pm('paypal')->email,
		    	'payment_id' => $payment->id,
		    	'extra' => json_encode(['url'=> $payment->getApprovalLink(), 'token'=>$payment->getToken(), 'info'=>$payment]),
		    	'details' => 'Tokens Purchase',
		    	'status' => 'pending',
	    	];
	    	$ret['msg'] = 'info';
	        $ret['message'] = __('messages.trnx.created');;
	        $iid = Transaction::insertGetId($save_data);

	    	if ($payment->getApprovalLink()) {
			    $tnx = Transaction::where('id', $iid)->first();
			    $tnx->tnx_id = set_id($iid, 'trnx');
			    $tnx->save();

			    IcoStage::token_add_to_account($tnx, 'add');
	            try {
                    $tnx->tnxUser->notify((new TnxStatus($tnx, 'submit-online-user')));
		            if(get_emailt('order-placed-admin', 'notify') == 1){
		                notify_admin($tnx, 'placed-admin');
		            }
                } catch (\Exception $e) {
                    $response['error'] = $e->getMessage();
                }

				$ret['link'] = $payment->getApprovalLink();
			}else{
				$ret['msg'] = 'error';
	        	$ret['message'] = __('messages.trnx.canceled');
				Transaction::where('id', $iid)->delete();
			}
		    
	        if ($request->ajax()) {
	            return response()->json($ret);
	        }
		    return redirect($payment->getApprovalLink());
		}
		catch (\PayPal\Exception\PayPalConnectionException $ex) {
		    $err = $ex->getData();
		    $err = json_decode($err);
		    if ($request->ajax()) {
		    	$ret['msg'] = 'info';
	        	$ret['message'] = isset($err->error_description) ? $err->error_description : 'Unable to connect with PayPal.'; 
		    	return response()->json($ret);
		    }
		    return $this->payment_cancel($err->error_description);

		}
    }

	/**
	 * Success Callback Payment via PayPal
	 *
     * @version 1.0.0
     * @since 1.0.2
     * @return void
     */
    public function paypal_success(Request $request){
    	try {
    		$paymentId = $request->get('paymentId');
		    $payment = Payment::get($paymentId, $this->apiContext());
		    $execution = new PaymentExecution();
		    $execution->setPayerId($request->get('PayerID'));

			$transaction = new PaypalTrnx();
		    $amount = new Amount();
		    $details = new Details();

		    $amount->setCurrency($payment->transactions[0]->amount->currency);
		    $amount->setTotal($payment->transactions[0]->amount->total);
		    $amount->setDetails($details);
		    $transaction->setAmount($amount);

			$execution->addTransaction($transaction);

			try {
				$result = $payment->execute($execution, $this->apiContext());
				$payment = Payment::get($paymentId, $this->apiContext());

				$tranx = Transaction::where('payment_id', $paymentId)->first();
				$_old_status = $tranx->status;
				$tranx->status = ($payment->state == 'approved') ? 'approved' : (($payment->state == 'failed') ? 'rejected' : 'canceled');
				if($payment->state == 'approved'){
			    	$tranx->wallet_address = $payment->payer->payer_info->email;
			    	$tranx->receive_amount = $payment->transactions[0]->amount->total;
			    	$tranx->tnx_time = date('Y-m-d H:i:s',strtotime($payment->create_time));
			    	$tranx->checked_by = json_encode(['name'=>'paypal', 'id'=>$paymentId]);
			    	$tranx->checked_time = Carbon::now()->toDateTimeString();
			    	$tranx->extra = is_json($payment) ? $payment : json_encode($payment);
			    	$tranx->save();
			    	
			    	if($_old_status == 'deleted'){
			    		$tranx->status = 'missing';
			    		$tranx->save();
			    		return redirect()->route('user.token')->with(['info', 'Thank you! We received your payment but we found something wrong in your transaction, please contact with us with the order id: '.$tranx->tnx_id.'.']);
			    	} else {
					    if($tranx->status == 'approved' && is_active_referral_system()){
					        $referral = new ReferralHelper($tranx);
					        $referral->addToken('refer_to');
					        $referral->addToken('refer_by');
					    }
					    IcoStage::token_add_to_account($tranx, null, 'add');
                        try {
		                    $tranx->tnxUser->notify((new TnxStatus($tranx, 'successful-user')));
	                        if(get_emailt('order-successful-admin', 'notify') == 1){
	                            notify_admin($tranx, 'successful-admin');
	                        }
		                } catch (\Exception $e) {
		                    $response['error'] = $e->getMessage();
		                }
					    return redirect(route('user.token'))->with(['success'=>'Thank You, We have received your payment!', 'modal'=>'success']);
					}
				}else{
			    	$tranx->save();
					IcoStage::token_add_to_account($tranx, 'sub');

					return redirect(route('user.token'))->with(['warning'=>'Sorry, We have not received your payment!', 'modal'=>'failed']);
				}
			} catch (Exception $ex) {
				return $this->payment_cancel($ex);
			}

		} catch (Exception $ex) {
			return $this->payment_cancel($ex);
		}
    }

    /**
	 * Payment Cancel
	 *
     * @version 1.0.0
     * @since 1.0.2
     * @return void
     */
    public function payment_cancel(Request $request, $name='Order has been canceled due to payment!')
    {
    	if ($request->get('tnx_id') || $request->get('token')) {
            $id = $request->get('tnx_id');
            $pay_token = $request->get('token');
            if($pay_token != null){
                $pay_token = (starts_with($pay_token, 'EC-') ? str_replace('EC-', '', $pay_token) : $pay_token);
            }
            $apv_name = ucfirst('paypal');
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
                }elseif(!empty($tnx) && ($tnx->status == 'pending' || $tnx->status == 'onhold')) {
                    $tnx->status = 'canceled';
                    $tnx->checked_by = json_encode(['name'=>$apv_name, 'id'=>$pay_token]);
                    $tnx->checked_time = Carbon::now()->toDateTimeString();
                    $tnx->save();
                    
                    IcoStage::token_add_to_account($tnx, 'sub');
                    try {
	                    $tnx->tnxUser->notify((new TnxStatus($tnx, 'canceled-user')));
	                    if(get_emailt('order-rejected-admin', 'notify') == 1){
	                        notify_admin($tnx, 'rejected-admin');
	                    }
	                } catch (\Exception $e) {
	                    $response['error'] = $e->getMessage();
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
