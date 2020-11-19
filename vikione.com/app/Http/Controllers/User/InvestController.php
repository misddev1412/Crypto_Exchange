<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Invests;

use Auth;
use Validator;

class InvestController extends Controller
{
    private $fee = 25;

    public function index() {
        $user = Auth::user();
        $sellers = Invests::where('invests.status', 'pending')->orderBy('id', 'DESC')->join('users', 'users.id', '=', 'invests.seller_id')->get(['invests.*', 'users.email', 'users.mobile']);
        $my_cases = Invests::where('seller_id', $user->id)->orderBy('id', 'DESC')->get();

        return view('user.invests', compact('user', 'sellers', 'my_cases'));
    }

    public static function count()
    {
        return Invests::where('status', 'pending')->count();
    }

    public function sell(Request $request) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50',
        ]);

        if ($validator->fails()) {
            $ret['msg'] = 'error';
            $ret['messsage'] = $validator->errors();
        } else{

            $amount = floatval($request->input('amount'));

            $total_amount = $amount + (($amount * $this->fee) / 100);

            $user = Auth::user();

            if($user->tokenBalance2 < $total_amount)
            {
                $ret['msg'] = 'error';
                $ret['message'] = __('Your balance is not enough to make this transaction!');
            } else {
                $updateBalance = User::where('id', $user->id)->update(['freezeBalance' => floatval($total_amount), 'tokenBalance2' => floatval($user->tokenBalance2 - $total_amount)]);

                if($updateBalance)
                {
                    try {
                        Invests::insert([
                            'id' => time(),
                            'seller_id' => $user->id,
                            'amount' => (int)$amount,
                            'seller_fee' => 20,
                            'admin_fee' => 5,
                            'status' => 'pending',
                            'created_at' => date('Y-m-d H:i:s', time())
                        ]);

                        $ret['msg'] = 'success';
                        $ret['message'] = __('Create sell request successfully!');

                    } catch (\Exception $e) {
                        $ret['msg'] = 'error';
                        $ret['message'] = __('Oops! We have a mistake here, please try agian later or contact to admin.');
                    }
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = __('Oops! We have a mistake here, please try agian later or contact to admin.');            
                }
            }
        }

        return back()->with([$ret['msg'] => $ret['message']]);
    }

    public function view(Request $request)
    {
        $user = Auth::user();

        $valid = Invests::where([['id', '=', $request->input('case_id')], ['seller_id', '=', $user->id]])->exists();
        if($valid){
            $case = Invests::where([['id', '=', $request->input('case_id')], ['seller_id', '=', $user->id]])->first();

            return '<div class="alert alert-info">No information about this case</div>';
        } else
            return 'Oops! Something went wrong.';
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();

        $valid = Invests::where([['id', '=', $request->input('caseId')], ['seller_id', '=', $user->id]])->exists();
        if($valid){
            $case = Invests::where([['id', '=', $request->input('caseId')], ['seller_id', '=', $user->id]])->first();

            if($case->status == 'pending' || $case->status == 'progress')
            {
                try {
                    $refund_amount = $case->amount + (($case->amount * $this->fee) / 100);

                    Invests::where('id', $case->id)->update([
                        'status' => 'canceled', 
                    ]);
                    
                    User::where('id', $user->id)->update([
                        'tokenBalance2' => floatval($user->tokenBalance2 + $refund_amount),
                        'freezeBalance' => floatval($user->freezeBalance - $refund_amount)
                    ]);  

                    $ret['msg'] = 'success';
                    $ret['message'] = 'Canceled case: #'.$case->id.' successfully! we refunded Freeze Balance to your account.';

                } catch (\Exception $e) {
                    $ret['msg'] = 'error';
                    $ret['message'] = __('Oops! We have a mistake here, please try agian later or contact to admin.');   
                }
            } else {
                $ret['msg'] = 'error';
                $ret['message'] = __("You can't cancel this case until buyer confirm!");                   
            }
        } else {
            $ret['msg'] = 'error';
            $ret['message'] = 'Oop! some thing wrong?';
        }

        return back()->with([$ret['msg'] => $ret['message']]);
    }
}
