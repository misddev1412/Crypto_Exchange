<?php

namespace App\PayModule\Manual;

use Auth;
use Validator;
use App\Models\IcoStage;
use App\Helpers\IcoHandler;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\PayModule\ModuleHelper;
use App\Http\Controllers\Controller;

class ManualController extends Controller
{
	public function update_transaction(Request $request)
	{
		$response['msg'] = 'info';
        $response['message'] = __('messages.nothing');
        if ($request->input('action') == 'confirm') {
            $validator = Validator::make($request->all(), [
                'trnx_id' => 'required',
                'payment_address' => 'required',
            ], [
                'trnx_id.required' => __('messages.trnx.required'),
                'payment_address.required' => __('messages.invalid.address'),
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'trnx_id' => 'required',
            ], [
                'trnx_id.required' => __('messages.trnx.required'),
            ]);
        }

        if ($validator->fails()) {
            if ($validator->errors()->has('trnx_id')) {
                $msg = $validator->errors()->first();
            } elseif ($validator->errors()->has('payment_address')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $response['msg'] = 'warning';
            $response['message'] = $msg;
        } else {
            $action = $request->input('action');
            $address = $request->input('payment_address');
            $tnxns = Transaction::where('id', $request->input('trnx_id'))->first();
            $_old_status = $tnxns->status;
            $is_valid = IcoHandler::validate_address($address, $tnxns->currency);
            $need = (manual_payment(strtolower($tnxns->currency), 'req') == 'yes' ? true : false);
            if( $need && empty($address) && $is_valid == false ){
                $response['msg'] = 'error';
                $response['message'] = __('messages.invalid.address');
            }else{
                if ($_old_status == 'canceled' || $_old_status == 'deleted') {
                    $response['msg'] = 'warning';
                    $response['message'] = "Your transaction is already " . $_old_status . ". Sorry, we're unable to proceed the transaction.";
                    if ($action != 'confirm') {
                        $response['modal'] = view('modals.payment-canceled', compact('tnxns'))->render();
                    } else {
                        $response['modal'] = view('modals.payment-canceled', compact('tnxns'))->render();
                    }
                } else {
                    if ($action == 'confirm' && $is_valid == true && $address != null) {
                        $tnxns->payment_id = $address;
                        $tnxns->wallet_address = $address;
                        $tnxns->extra = json_encode(['address' => $address]);
                        $tnxns->status = 'onhold';
                        $tnxns->save();
                        if ($tnxns) {
                            $response['msg'] = 'info';
                            $response['message'] = __('messages.trnx.reviewing');
                            $response['modal'] = view('modals.payment-review', compact('tnxns'))->render();
                        }
                    } 
                    else {
                        $response['msg'] = 'warning';
                        $response['message'] = __('messages.invalid.address');
                    }
                    if ($action == 'cancel') {
                        $tnxns->status = 'canceled';
                        $tnxns->save();

                        IcoStage::token_add_to_account($tnxns, 'sub');
                        if ($tnxns) {
                            try {
                                if (get_emailt('order-canceled-admin', 'notify') == 1) {
                                    notify_admin($tnxns, 'canceled-admin');
                                }
                            } catch (\Exception $e) {
                                $response['error'] = $e->getMessage();
                            }
                            $response['msg'] = 'warning';
                            $response['message'] = __('messages.trnx.canceled_own');
                            $response['modal'] = view('modals.payment-canceled', compact('tnxns'))->render();
                        }
                    }
                }
            }
        }

        if ($request->ajax()) {
            return response()->json($response);
        }
        return back()->with([$response['msg'] => $response['message']]);
	}
}
