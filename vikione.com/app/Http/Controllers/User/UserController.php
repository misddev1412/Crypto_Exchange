<?php

namespace App\Http\Controllers\User;

/**
 * User Controller
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.6
 */

use Auth;
use Validator;
use IcoHandler;
use Carbon\Carbon;
use App\Models\Page;
use App\Models\User;
use App\Models\IcoStage;
use App\Models\UserMeta;
use App\Models\Activity;
use App\Helpers\NioModule;
use App\Models\GlobalMeta;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Notifications\PasswordChange;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Models\Referral;
use GuzzleHttp\Client;
use Session;
use App\Helpers\TokenCalculate as TC;

class UserController extends Controller
{
  protected $handler;
  public function __construct(IcoHandler $handler)
  {
    $this->middleware('auth');
    $this->handler = $handler;
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   * @version 1.0.0
   * @since 1.0
   * @return void
   */
  public function index()
  {
    /**Check Template Rex*/
    $user = Auth::user();
    $stage = active_stage();
    $contribution = Transaction::user_contribution();
    $tc = new \App\Helpers\TokenCalculate();
    $active_bonus = $tc->get_current_bonus('active');

    return view('user.dashboard', compact('user', 'stage', 'active_bonus', 'contribution'));
  }


  /**
   * Show the user account page.
   *
   * @return \Illuminate\Http\Response
   * @version 1.0.0
   * @since 1.0
   * @return void
   */
  public function account()
  {
    $countries = $this->handler->getCountries();
    $user = Auth::user();
    $userMeta = UserMeta::getMeta($user->id);

    $g2fa = new Google2FA();
    $google2fa_secret = $g2fa->generateSecretKey();
    $google2fa = $g2fa->getQRCodeUrl(
      site_info() . '-' . $user->name,
      $user->email,
      $google2fa_secret
    );

    return view('user.account', compact('user', 'userMeta', 'countries', 'google2fa', 'google2fa_secret'));
  }

  /**
   * Show the user account activity page.
   * and Delete Activity
   *
   * @return \Illuminate\Http\Response
   * @version 1.0.0
   * @since 1.0
   * @return void
   */
  public function account_activity()
  {
    $user = Auth::user();
    $activities = Activity::where('user_id', $user->id)->orderBy('created_at', 'DESC')->limit(50)->get();

    return view('user.activity', compact('user', 'activities'));
  }

  /**
   * Show the user account token management page.
   *
   * @return \Illuminate\Http\Response
   * @version 1.0.0
   * @since 1.1.2
   * @return void
   */
  public function mytoken_balance()
  {
    if (gws('user_mytoken_page') != 1) {
      return redirect()->route('user.home');
    }
    $user = Auth::user();
    $token_account = Transaction::user_mytoken('balance');
    $token_stages = Transaction::user_mytoken('stages');
    $user_modules = nio_module()->user_modules();
    return view('user.account-token', compact('user', 'token_account', 'token_stages', 'user_modules'));
  }

  /**
   * Activity delete
   *
   * @version 1.0.0
   * @since 1.0
   * @return void
   */
  public function account_activity_delete(Request $request)
  {
    $id = $request->input('delete_activity');
    $ret['msg'] = 'info';
    $ret['message'] = "Nothing to do!";

    if ($id !== 'all') {
      $remove = Activity::where('id', $id)->where('user_id', Auth::id())->delete();
    } else {
      $remove = Activity::where('user_id', Auth::id())->delete();
    }
    if ($remove) {
      $ret['msg'] = 'success';
      $ret['message'] = __('messages.delete.delete', ['what' => 'Activity']);
    } else {
      $ret['msg'] = 'warning';
      $ret['message'] = __('messages.form.wrong');
    }
    if ($request->ajax()) {
      return response()->json($ret);
    }
    return back()->with([$ret['msg'] => $ret['message']]);
  }

  /**
   * update the user account page.
   *
   * @return \Illuminate\Http\Response
   * @version 1.2
   * @since 1.0
   * @return void
   */
  public function account_update(Request $request)
  {
    $type = $request->input('action_type');
    $ret['msg'] = 'info';
    $ret['message'] = __('messages.nothing');

    if ($type == 'personal_data') {
      $validator = Validator::make($request->all(), [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'dateOfBirth' => 'required|date_format:"m/d/Y"'
      ]);

      if ($validator->fails()) {
        $msg = __('messages.form.wrong');
        if ($validator->errors()->hasAny(['name', 'email', 'dateOfBirth'])) {
          $msg = $validator->errors()->first();
        }

        $ret['msg'] = 'warning';
        $ret['message'] = $msg;
        return response()->json($ret);
      } else {
        $user = User::FindOrFail(Auth::id());
        $user->name = strip_tags($request->input('name'));
        $user->email = $request->input('email');
        $user->mobile = strip_tags($request->input('mobile'));
        $user->dateOfBirth = $request->input('dateOfBirth');
        $user->nationality = strip_tags($request->input('nationality'));
        $user_saved = $user->save();

        if ($user) {
          $ret['msg'] = 'success';
          $ret['message'] = __('messages.update.success', ['what' => 'Account']);
        } else {
          $ret['msg'] = 'warning';
          $ret['message'] = __('messages.update.warning');
        }
      }
    }
    if ($type == 'wallet') {
      $validator = Validator::make($request->all(), [
        'wallet_name' => 'required',
        'wallet_address' => 'required|min:10'
      ]);

      if ($validator->fails()) {
        $msg = __('messages.form.wrong');
        if ($validator->errors()->hasAny(['wallet_name', 'wallet_address'])) {
          $msg = $validator->errors()->first();
        }

        $ret['msg'] = 'warning';
        $ret['message'] = $msg;
        return response()->json($ret);
      } else {
        $is_valid = $this->handler->validate_address($request->input('wallet_address'), $request->input('wallet_name'));
        if ($is_valid) {
          $user = User::FindOrFail(Auth::id());
          $user->walletType = $request->input('wallet_name');
          $user->walletAddress = $request->input('wallet_address');
          $user_saved = $user->save();

          if ($user) {
            $ret['msg'] = 'success';
            $ret['message'] = __('messages.update.success', ['what' => 'Wallet']);
          } else {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.update.warning');
          }
        } else {
          $ret['msg'] = 'warning';
          $ret['message'] = __('messages.invalid.address');
        }
      }
    }
    if ($type == 'wallet_request') {
      $validator = Validator::make($request->all(), [
        'wallet_name' => 'required',
        'wallet_address' => 'required|min:10'
      ]);

      if ($validator->fails()) {
        $msg = __('messages.form.wrong');
        if ($validator->errors()->hasAny(['wallet_name', 'wallet_address'])) {
          $msg = $validator->errors()->first();
        }

        $ret['msg'] = 'warning';
        $ret['message'] = $msg;
        return response()->json($ret);
      } else {
        $is_valid = $this->handler->validate_address($request->input('wallet_address'), $request->input('wallet_name'));
        if ($is_valid) {
          $meta_data = ['name' => $request->input('wallet_name'), 'address' => $request->input('wallet_address')];
          $meta_request = GlobalMeta::save_meta('user_wallet_address_change_request', json_encode($meta_data), auth()->id());

          if ($meta_request) {
            $ret['msg'] = 'success';
            $ret['message'] = __('messages.wallet.change');
          } else {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.wallet.failed');
          }
        } else {
          $ret['msg'] = 'warning';
          $ret['message'] = __('messages.invalid.address');
        }
      }
    }
    if ($type == 'notification') {
      $notify_admin = $newsletter = $unusual = 0;

      if (isset($request['notify_admin'])) {
        $notify_admin = 1;
      }
      if (isset($request['newsletter'])) {
        $newsletter = 1;
      }
      if (isset($request['unusual'])) {
        $unusual = 1;
      }

      $user = User::FindOrFail(Auth::id());
      if ($user) {
        $userMeta = UserMeta::where('userId', $user->id)->first();
        if ($userMeta == null) {
          $userMeta = new UserMeta();
          $userMeta->userId = $user->id;
        }
        $userMeta->notify_admin = $notify_admin;
        $userMeta->newsletter = $newsletter;
        $userMeta->unusual = $unusual;
        $userMeta->save();
        $ret['msg'] = 'success';
        $ret['message'] = __('messages.update.success', ['what' => 'Notification']);
      } else {
        $ret['msg'] = 'warning';
        $ret['message'] = __('messages.update.warning');
      }
    }
    if ($type == 'security') {
      $save_activity = $mail_pwd = 'FALSE';

      if (isset($request['save_activity'])) {
        $save_activity = 'TRUE';
      }
      if (isset($request['mail_pwd'])) {
        $mail_pwd = 'TRUE';
      }

      $user = User::FindOrFail(Auth::id());
      if ($user) {
        $userMeta = UserMeta::where('userId', $user->id)->first();
        if ($userMeta == null) {
          $userMeta = new UserMeta();
          $userMeta->userId = $user->id;
        }
        $userMeta->pwd_chng = $mail_pwd;
        $userMeta->save_activity = $save_activity;
        $userMeta->save();
        $ret['msg'] = 'success';
        $ret['message'] = __('messages.update.success', ['what' => 'Security']);
      } else {
        $ret['msg'] = 'warning';
        $ret['message'] = __('messages.update.warning');
      }
    }
    if ($type == 'account_setting') {
      $notify_admin = $newsletter = $unusual = 0;
      $save_activity = $mail_pwd = 'FALSE';
      $user = User::FindOrFail(Auth::id());

      if (isset($request['save_activity'])) {
        $save_activity = 'TRUE';
      }
      if (isset($request['mail_pwd'])) {
        $mail_pwd = 'TRUE';
      }

      $mail_pwd = 'TRUE'; //by default true
      if (isset($request['notify_admin'])) {
        $notify_admin = 1;
      }
      if (isset($request['newsletter'])) {
        $newsletter = 1;
      }
      if (isset($request['unusual'])) {
        $unusual = 1;
      }


      if ($user) {
        $userMeta = UserMeta::where('userId', $user->id)->first();
        if ($userMeta == null) {
          $userMeta = new UserMeta();
          $userMeta->userId = $user->id;
        }

        $userMeta->notify_admin = $notify_admin;
        $userMeta->newsletter = $newsletter;
        $userMeta->unusual = $unusual;

        $userMeta->pwd_chng = $mail_pwd;
        $userMeta->save_activity = $save_activity;

        $userMeta->save();
        $ret['msg'] = 'success';
        $ret['message'] = __('messages.update.success', ['what' => 'Account Settings']);
      } else {
        $ret['msg'] = 'warning';
        $ret['message'] = __('messages.update.warning');
      }
    }
    if ($type == 'pwd_change') {
      //validate data
      $validator = Validator::make($request->all(), [
        'old-password' => 'required|min:6',
        'new-password' => 'required|min:6',
        're-password' => 'required|min:6|same:new-password',
      ]);
      if ($validator->fails()) {
        $msg = __('messages.form.wrong');
        if ($validator->errors()->hasAny(['old-password', 'new-password', 're-password'])) {
          $msg = $validator->errors()->first();
        }

        $ret['msg'] = 'warning';
        $ret['message'] = $msg;
        return response()->json($ret);
      } else {
        $user = Auth::user();
        if ($user) {
          if (!Hash::check($request->input('old-password'), $user->password)) {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.password.old_err');
          } else {
            $userMeta = UserMeta::where('userId', $user->id)->first();
            $userMeta->pwd_temp = Hash::make($request->input('new-password'));
            $cd = Carbon::now();
            $userMeta->email_expire = $cd->copy()->addMinutes(60);
            $userMeta->email_token = str_random(65);
            if ($userMeta->save()) {
              try {
                $user->notify(new PasswordChange($user, $userMeta));
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.password.changed');
              } catch (\Exception $e) {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.email.password_change', ['email' => get_setting('site_email')]);
              }
            } else {
              $ret['msg'] = 'warning';
              $ret['message'] = __('messages.form.wrong');
            }
          }
        } else {
          $ret['msg'] = 'warning';
          $ret['message'] = __('messages.form.wrong');
        }
      }
    }
    if ($type == 'google2fa_setup') {
      $google2fa = $request->input('google2fa', 0);
      $user = User::FindOrFail(Auth::id());
      if ($user) {
        // Google 2FA
        $ret['link'] = route('user.account');
        if (!empty($request->google2fa_code)) {
          $g2fa = new Google2FA();
          if ($google2fa == 1) {
            $verify = $g2fa->verifyKey($request->google2fa_secret, $request->google2fa_code);
          } else {
            $verify = $g2fa->verify($request->google2fa_code, $user->google2fa_secret);
          }

          if ($verify) {
            $user->google2fa = $google2fa;
            $user->google2fa_secret = ($google2fa == 1 ? $request->google2fa_secret : null);
            $user->save();
            $ret['msg'] = 'success';
            $ret['message'] = __('Successfully ' . ($google2fa == 1 ? 'enable' : 'disable') . ' 2FA security in your account.');
          } else {
            $ret['msg'] = 'error';
            $ret['message'] = __('You have provide a invalid 2FA authentication code!');
          }
        } else {
          $ret['msg'] = 'warning';
          $ret['message'] = __('Please enter a valid authentication code!');
        }
      }
    }

    if ($request->ajax()) {
      return response()->json($ret);
    }
    return back()->with([$ret['msg'] => $ret['message']]);
  }

  public function password_confirm($token)
  {
    $user = Auth::user();
    $userMeta = UserMeta::where('userId', $user->id)->first();
    if ($token == $userMeta->email_token) {
      if (_date($userMeta->email_expire, 'Y-m-d H:i:s') >= date('Y-m-d H:i:s')) {
        $user->password = $userMeta->pwd_temp;
        $user->save();
        $userMeta->pwd_temp = null;
        $userMeta->email_token = null;
        $userMeta->email_expire = null;
        $userMeta->save();

        $ret['msg'] = 'success';
        $ret['message'] = __('messages.password.success');
      } else {
        $ret['msg'] = 'warning';
        $ret['message'] = __('messages.password.failed');
      }
    } else {
      $ret['msg'] = 'warning';
      $ret['message'] = __('messages.password.token');
    }

    return redirect()->route('user.account')->with([$ret['msg'] => $ret['message']]);
  }

  /**
   * Get pay now form
   *
   * @version 1.0.0
   * @since 1.0
   * @return void
   */
  public function get_wallet_form(Request $request)
  {
    return view('modals.user_wallet')->render();
  }

  /**
   * Show the user Referral page
   *
   * @version 1.0.0
   * @since 1.0.3
   * @return void
   */
  public function referral()
  {
    $page = Page::where('slug', 'referral')->where('status', 'active')->first();
    $reffered = User::where('referral', auth()->id())->get();

    $treeRef = [
      'name' => Auth::user()->name,
      'childrens' => $this->getRef(Auth::id())
    ];
    
    $treeRef = '<ul><li>'.Auth::user()->name;
    if($this->getRef(Auth::id())) {
      $treeRef .= '<ul>' . $this->getRef(Auth::id()) .'</ul>';
    }
      // <ul>
      //   <li id="child_node_1">Child node 1</li>
      //   <li>Child node 2</li>
      // </ul>
      $treeRef .= '</li></ul>';

    if (get_page('referral', 'status') == 'active') {
      return view('user.referral', compact('page', 'reffered', 'treeRef'));
    } else {
      abort(404);
    }
  }

  private function getRef($uid) {
    $userRef = "";
    $users = Referral::where('refer_by', $uid)->leftJoin('users', 'users.id', '=','referrals.user_id')
    ->select('referrals.*', 'users.name', 'users.email','users.id', 'users.affiliate')->get();
    foreach ($users as $user) {
      $userRef .= '<li>'.$user->name; 
      $userRef .=  ' - <b>'. $user->affiliate .'</b>';
      if($this->getRef($user->id)) {
        $userRef .= '<ul>' .$this->getRef($user->id). '</ul>';
      }
      $userRef .= '</li>';
      // $userRef[] = [
      //   'name' => $user->name,
      //   'childrens' => $this->getRef($user->id)
      // ];
    }
    return $userRef;
  }
  /**
   * Get Point to Token One
   */

  public function point(Request $request)
  {
    //Check Muity
    $user = Auth::user();
   
    if ($user->tokenBalance > 0) {
      if($user->poinMultiply) {
        $poinMultiply = json_decode($user->poinMultiply, true);

        $poinMultiply = $poinMultiply[count($poinMultiply) - 1];
        if (Setting::getValue("point_receiving_" . (($poinMultiply['turn'] + 1))) && Setting::getValue("point_multiply_" . (($poinMultiply['turn'] + 1)))) {
          $point_receiving = Setting::getValue("point_receiving_" . (($poinMultiply['turn'] + 1)));
          $point_multiply = Setting::getValue("point_multiply_" . (($poinMultiply['turn'] + 1)));

          $turn = $poinMultiply['turn'] + 1;
          $poinMultiply = json_decode($user->poinMultiply, true);
          $poinMultiply[] = [
            'turn' => $turn,
            'receiving' => $point_receiving,
            'multiply' => $point_multiply,
          ];
          $user->poinMultiply = json_encode($poinMultiply);

          //Update Point Redeem
          $user->tokenPoint = $user->tokenPoint + ($user->tokenBalance * (int)$point_multiply);
          $user->tokenBalance = 0;

          $user_saved = $user->save();
          if ($user) {
            $ret['msg'] = 'success';
            $ret['message'] = __('messages.point.muity.success', ['what' => 'Point']);
            $ret['reload'] = true;
          }
        } else {
          $ret['msg'] = 'warning';
          $ret['message'] = __('messages.point.muity.warning');
        }
      } else {
        //Set Turn Redeem 1
        $point_receiving = (Setting::getValue("point_receiving_" . $user->id . "_1") != null) ? Setting::getValue("point_receiving_" . $user->id . "_1") : Setting::getValue("point_receiving_1");
        $point_multiply = (Setting::getValue("point_multiply_" . $user->id . "_1") != null) ? Setting::getValue("point_multiply_" . $user->id . "_1") : Setting::getValue("point_multiply_1");

        $user->poinMultiply = json_encode([
          [
            'turn' => 1,
            'receiving' => $point_receiving,
            'multiply' => $point_multiply,
          ]
        ]);

        //Update Point Redeem
        $user->tokenPoint = $user->tokenPoint + ($user->tokenBalance * (int)$point_multiply);
        $user->tokenBalance = 0;

        $user_saved = $user->save();
        if ($user) {
          $ret['msg'] = 'success';
          $ret['message'] = __('messages.point.muity.success', ['what' => 'Point']);
          $ret['reload'] = true;
        } else {
          $ret['msg'] = 'warning';
          $ret['message'] = __('messages.point.muity.warning');
        }
      }
    } else {
      $ret['msg'] = 'warning';
      $ret['message'] = __('messages.point.muity.warning');
    }

    if ($request->ajax()) {
      return response()->json($ret);
    }
  }

  public function depositOneBlueToExchange(Request $request)
  {
    $apiUrl = 'https://vikione.exchange/api/local/coin/deposit';
    $headers = [
        'Content-Type'  => 'application/json',
        'local-token'   => 'dC38Xaq0YO03A3fRbaitx78zmYhvIPSjsOjVB4VGpfOtNLar37gPphE1tlfJIZxs',
    ];
    
    $client = new Client([
        'headers'   => $headers
    ]);

    $data       = [
        'email'     => Auth::user()->email . 'sss',
        'phone'     => Auth::user()->mobile,
        'amount'    => Auth::user()->one_exchange ?? 0
    ];
    if (Auth::user()->mobile == null) {
      Session::flash('error', 'Please update your phone before continue to do!');
      return redirect()->back();
    }

    if ($data['amount'] == 0) {
      Session::flash('error', 'Your balance is not enough.');
      return redirect()->back();
    }
    User::pushOneExchange(Auth::user()->id, $data['amount']);
    try {
      $result     = $client->post($apiUrl,  ['form_params'=> $data]);
      $body       = json_decode($result->getBody()->getContents());
      if (isset($body->status) && $body->status == 200) {
        Session::flash('info', 'Your conversion was successful!');
        $systemInfo = new \stdClass();
        $systemInfo->name ='Exchange';
        $systemInfo->id   = -1;
        $this->create_transaction($systemInfo, $request, -$data['amount'], 'approved', Auth::user(), 0, 0);
        return redirect()->back();
      } else if (isset($body->status) && $body->status == 404) {
        Session::flash('error', $body->message);
        return redirect()->back();
      }
    } catch (\Exception $e) {
      User::revertOneExchange(Auth::user()->id, $data['amount']);
      Session::flash('error', 'Something went wrong, please try again later!');
      return redirect()->back();
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

}
