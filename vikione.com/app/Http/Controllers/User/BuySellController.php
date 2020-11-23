<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\BuySell;
use App\Models\BuySellConversations as Conversations;

use Auth;
use Validator;
use Storage;

class BuySellController extends Controller
{
    private $fee = 25;

    public function index() {
        $user = Auth::user();
        $sellers = BuySell::where('buy_sell.status', 'pending')->orderBy('id', 'DESC')->join('users', 'users.id', '=', 'buy_sell.seller_id')->get(['buy_sell.*', 'users.email', 'users.mobile']);
        $my_cases = BuySell::where('seller_id', $user->id)->orderBy('id', 'DESC')->get();
        $histories = BuySell::where('buyer_id', $user->id)->orderBy('id', 'DESC')->get();

        return view('user.buysell', compact('user', 'sellers', 'my_cases', 'histories'));
    }

    public static function count()
    {
        return BuySell::where('status', 'pending')->count();
    }

    public function send(Request $request) {
        $validator = Validator::make($request->all(), [
            'method' => 'required',
            'seller_hidden_id' => 'required'
        ]); 

        if ($validator->fails()) {
            $ret['msg'] = 'error';
            $ret['message'] = $validator->errors();
        } else{
            $user = Auth::user();
            $validMethod = 'direct & safety';

            $method = $request->input('method');
            $caseId = $request->input('seller_hidden_id');

            if(strpos($validMethod, $method) !== false)
            {
                $validCase = BuySell::where([['id', '=', $caseId], ['buyer_id', '=', 0], ['status', '=', 'pending']])->exists();
                if($validCase)
                {
                    try {
                        $case = BuySell::where('id', $caseId)->first();

                        BuySell::where('id', $caseId)->update([
                            'buyer_id' => $user->id,
                            'status' => 'progress',
                            'method' => $method
                        ]);
                        
                        Conversations::insert([
                            'case_id' => $caseId,
                            'is_seller' => false,
                            'message' => 'Hi, i want to buy '.$case->amount.' ONE. How can i pay for you ?. Paypal, Bank Transfer,.. ?',
                            'created_at' => date('Y-m-d H:i:s', time())
                        ]);

                        $ret['msg'] = 'success';
                        if($method == 'direct')
                            $ret['message'] = 'Now, we created for you and seller a new conversation, we will not be responsible for any problems that occur during the transaction';     
                        else
                            $ret['message'] = 'Now, go to History tab > view your transaction and follow instruction.';              
                    } catch(\Exception $e){
                        $ret['msg'] = 'error';
                        $ret['message'] = 'Oops! We have a mistake here, please try agian later or contact to admin.';                        
                    }
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = 'This transaction has been taken from another user.';                    
                }

            } else {
                $ret['msg'] = 'error';
                $ret['message'] = 'Invalid method.';
            }
        }

        return back()->with([$ret['msg'] => $ret['message']]);
    }

    public function sell(Request $request) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50',
        ]); 

        if ($validator->fails()) {
            $ret['msg'] = 'error';
            $ret['message'] = 'Amount must be a number from 50 to 100000.';
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
                        BuySell::insert([
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
        $validType = 'seller,buyer';

        if(strpos($validType, $request->input('case_type')) !== false) {
            $valid = BuySell::where([['id', '=', $request->input('case_id')], [$request->input('case_type').'_id', '=', $user->id]])->exists();
            if($valid){
                
                $case = BuySell::where([['id', '=', $request->input('case_id')], [$request->input('case_type').'_id', '=', $user->id]])->first();

                if($case->method == 'direct' && $case->buyer_id != 0)
                {
                    if($case->status == 'progress' || $case->status == 'complete') {
                        $type = $request->input('case_type').'_id';

                        $messages = Conversations::where('case_id', $case->id)->get();
                        $u = User::where('id', $case->$type)->first(['users.email']);

                        $chatBox = '
                        <div class="type_msg">
                            <div class="input_msg_write">
                            <form method="POST" action="" id="sendMsgForm" data-case-id="'.$case->id.'">
                                <input type="text" class="write_msg" placeholder="Type a message" />
                                <button class="msg_send_btn" type="submit"><i class="fa fa-paper-plane"></i></button>
                            </form>
                            </div>
                        </div>';

                        if($case->status == 'complete')
                            $chatBox = '';
                            
                        return '
                        <h4 class="text-center text-primary text-uppercase">Chatting with '.$u->email.'.</h4>
                        <div class="alert alert-warning">* Please verify you received money before send ONE.</div>
                        <div class="row messaging">
                            <div class="col-12 inbox_msg">
                            <div class="mesgs">
                                <div class="msg_history">
                                    '.$this->view_content($messages, $request->input('case_type')).'
                                </div>
                            '.$chatBox.'
                            </div>
                            </div>
                        </div>               
                        ';
                    } else if($case->status == 'canceled') {
                        return "<div class='alert alert-danger'><i class='fa fa-exclamation-circle'></i> This coversation is closed!</h4>";
                    }
                } else {
                    if($case->verify_file == null && $case->status == 'progress') {
                        return '
                        <h4 class="text-center text-primary text-uppercase">Waiting Buyer transfer money...</h4>
                        <div class="alert alert-warning">* When Buyer transfered money to us, we will verify and confirm to you step by step.</div>
                        <div class="text-center text-primary mt-2">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                        </div>';
                    } else if($case->verify_file != null && $case->status == 'progress') {
                        return '
                        <h4 class="text-center text-primary text-uppercase">Buyer transfered, checking payment information...</h4>
                        <div class="alert alert-info">Only one more step to finish this transaction.</div>
                        <div class="text-center text-primary mt-2">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                        </div>';                    
                    }
                    else if($case->status == 'complete') {
                        return '
                        <h4 class="text-center text-success text-uppercase">Finished! we transfered money to your account.</h4>
                        <div class="text-center text-success mt-2">
                            <i class="fa fa-check fa-3x"></i>
                        </div>                    
                        ';
                    }
                    else {
                        return '
                        <h3 class="text-center text-danger text-uppercase">This case canceled</h3>
                        <h4 class="text-center text-danger text-uppercase mb-2"><b>Reason:</b> '.$case->cancel_reason.'.</h4>
                        <div class="text-center text-danger mb-2">
                            <i class="fa fa-times fa-3x"></i>
                        </div>
                        ';
                    }
                }
            } else
                return 'Oops! Something went wrong.';
        } else {
            return 'Oops! Something went wrong.';
        }
    }

    public function view_content($messages, $case_type) {
        $content = '';

        foreach($messages as $message)
        {
            if($case_type == 'seller') {
                if($message->is_seller) {
                    $content .= '
                    <div class="outgoing_msg">
                        <div class="sent_msg">
                        <p>'.$message->message.'</p>
                        <span class="time_date">'.date('Y-m-d H:i:s', strtotime($message->created_at)).'</span> </div>
                    </div>                
                    ';                
                } else {
                    $content .= '
                    <div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                        <div class="received_msg">
                        <div class="received_withd_msg">
                            <p>'.$message->message.'</p>
                            <span class="time_date">'.date('Y-m-d H:i:s', strtotime($message->created_at)).'</span></div>
                        </div>
                    </div>
                    ';                
                }
            } else {
                if(!$message->is_seller) {
                    $content .= '
                    <div class="outgoing_msg">
                        <div class="sent_msg">
                        <p>'.$message->message.'</p>
                        <span class="time_date">'.date('Y-m-d H:i:s', strtotime($message->created_at)).'</span> </div>
                    </div>                
                    ';                
                } else {
                    $content .= '
                    <div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                        <div class="received_msg">
                        <div class="received_withd_msg">
                            <p>'.$message->message.'</p>
                            <span class="time_date">'.date('Y-m-d H:i:s', strtotime($message->created_at)).'</span></div>
                        </div>
                    </div>
                    ';                
                }                
            }
        }

        return $content;
    }

    public function message(Request $request) {
        $valid = BuySell::where('id', $request->input('case_id'))->exists();
        if($valid) {
            $user = Auth::user();
            $case = BuySell::where('id', $request->input('case_id'))->exists();

            if($user->id == $case->seller_id || $user->id == $case->buyer_id) {
                if(strlen($request->input('message')) > 10){
                $is_seller = false;

                if($user->id == $case->seller_id)
                    $is_seller = true;

                try {
                    Conversations::insert([
                        'is_seller' => $is_seller,
                        'message' => $request->input('message')
                    ]);
                } catch (\Exception $e) {
                    $ret['msg'] = 'error';
                    $ret['message'] = 'Oop! some thing wrong?';                       
                }
                    } else {
                        $ret['msg'] = 'error';
                        $ret['message'] = 'Oop! some thing wrong?';                  
                    }
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = 'Oop! some thing wrong?';  
                }
         } else {
                        
         }

        return back()->with([$ret['msg'] => $ret['message']]);
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();
        
        $validType = 'seller,buyer';

        if(strpos($validType, $request->input('cancel_type')) !== false)
        {
            $valid = BuySell::where([['id', '=', $request->input('cancel_id')], [$request->input('cancel_type').'_id', '=', $user->id]])->exists();
            if($valid){
                $case = BuySell::where([['id', '=', $request->input('cancel_id')], [$request->input('cancel_type').'_id', '=', $user->id]])->first();

                if($case->status == 'pending' || $case->status == 'progress' && $case->method == 'direct')
                {
                    try {
                        $refund_amount = $case->amount + (($case->amount * $this->fee) / 100);

                        BuySell::where('id', $case->id)->update([
                            'status' => 'canceled',
                            'cancel_reason' => 'Canceled'
                        ]);
                        
                        User::where('id', $user->id)->update([
                            'tokenBalance2' => floatval($user->tokenBalance2 + $refund_amount),
                            'freezeBalance' => floatval($user->freezeBalance - $refund_amount)
                        ]);  

                        $ret['msg'] = 'success';
                        $ret['message'] = 'Canceled case: #'.$case->id.' successfully.';

                    } catch (\Exception $e) {
                        $ret['msg'] = 'error';
                        $ret['message'] = __('Oops! We have a mistake here, please try agian later or contact to admin.');   
                    }
                } else if($case->status == 'canceled') {
                    $ret['msg'] = 'error';
                    $ret['message'] = __("This transaction already canceled.");                        
                } 
                else {
                    $ret['msg'] = 'error';
                    $ret['message'] = __("You can't cancel this case when you using Safety method, contact admin if you want cancel.");                   
                }
            } else {    
                $ret['msg'] = 'error';
                $ret['message'] = 'Oop! some thing wrong?';
            }
        } else {
            $ret['msg'] = 'error';
            $ret['message'] = 'Oop! some thing wrong?';            
        }

        return back()->with([$ret['msg'] => $ret['message']]);
    }    

    public static function buyer($id) {
        $buyer = User::where('id', $id)->first(['users.email']);

        return $buyer->email;
    }

    public static function seller($id) {
        $seller = User::where('id', $id)->first(['users.email']);

        return $seller->email;
    }    
}
