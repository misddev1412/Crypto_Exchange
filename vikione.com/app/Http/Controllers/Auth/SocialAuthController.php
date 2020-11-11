<?php
/**
* Social Login Controller
* Login with social (google + facebook)
* Handle G2FA
*
* @package TokenLite
* @author Softnio
* @version 1.0.2
*/
namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use Socialite;
use App\Models\User;
use App\Models\Activity;
use App\Models\UserMeta;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller
{
    // Available social login 
    protected $available = ['facebook', 'google'];
    /**
     * Redirect to the Service for Login
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect($social)
    {
        if(! in_array($social, $this->available)) {
            session()->flash('warning', __('messages.invalid.social'));
            return redirect()->route('login');
        }
        if (
            (get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')) != '' && get_setting('site_api_fb_secret', env('FB_CLIENT_SECRET', '')) != '') || 
            (get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')) != '' && get_setting('site_api_google_secret', env('GOOGLE_CLIENT_SECRET', '')) != '')
            ) {
                return Socialite::driver($social)->redirect();
        }else{
            return back()->with(['warning' => __('messages.invalid.social')]);
        }
    }

    /**
     * Callback for Socialite
     *
     * @return \Illuminate\Http\Response
     */
    public function callback($social)
    {
        try {
            $user = Socialite::driver($social)->user();
            
            if(empty($user)){
                session()->flash('info', __('Sorry, Something is wrong, please login via your email & password!'));
                return redirect()->route('login');
            }
            
            $name = $user->getName();
            $email = $user->getEmail();
            $id = $user->getId();
            
            //check if user already exists
            $checkUser = User::where(['email'=> $email, 'social_id' => $id])->first();
            if($checkUser){
                Auth::login($checkUser, true);
                $this->save_activity();
                // return redirect()->route('home');
            }
            $checkEMail = User::where(['email'=> $email])->first();
            if($checkEMail){
                $has_social = ($checkEMail->social_id != null) ? true : false;
                $msg = ($has_social) ? 'You are already registered, try again with different social account!' : 'Sorry, Something is wrong, please try again!';
                session()->flash('warning', $msg);
                return redirect()->route('login');;
            }
            $notice = "You have not registered yet in our platform. You can sign up with your ".ucfirst($social)." account.";
            // show the confirm form 
            return view('auth.social', compact('user', 'social', 'notice'));
        } catch (\Exception $e) {
            session()->flash('warning', __('Sorry, Something is wrong, please login via your email & password!!'));
            return redirect()->route('login');
        }
    }

    /**
     * Finally register the user
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'social_id' => 'required',
        ]);
        $password = str_random(12);
        $createUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 'user',
            'lastLogin' => date("Y-m-d H:i:s"),
        ]);        
        
        if($createUser){
            UserMeta::create([
                'userId' => $createUser->id
            ]);
            $social = $request->social;
            $createUser->email_verified_at = ( $social=='google' ? now() : NULL);
            $createUser->status = 'active';
            $createUser->registerMethod = $social;
            $createUser->social_id = $request->social_id;
            $createUser->save();

            Auth::login($createUser, true);
            $this->save_activity();
            
            return redirect()->route('home');
        }else{
            return redirect()->route('home');
        }
    }
    /**
     * Save user Activity
     *
     * @return activity
     */
    protected function save_activity()
    {
        if (UserMeta::getMeta(Auth::id())->save_activity == 'TRUE') {
            $agent = new Agent();

            $ret['activity'] = Activity::create([
                'user_id' => Auth::id(),
                'browser' => $agent->browser().'/'.$agent->version($agent->browser()),
                'device' => $agent->device().'/'.$agent->platform().'-'.$agent->version($agent->platform()),
                'ip' => request()->ip()
            ]);
        }
    }


    /*
     * Show the Google 2fa form
     */
    public function show_2fa_form()
    {
        if(auth()->user()->google2fa != 1) return redirect()->route('home');
        return view('auth.g2fa');
    }

    public function show_2fa_reset_form(Request $request)
    {
        if(!isset($request->token)) return redirect()->route('login')->with(['warning' => 'Invalid or Expired 2FA reset verification code!']);

        $token = $request->token;
        $meta = UserMeta::where('email_token', $token)->first();

        if(!isset($meta->user)) return redirect()->route('login')->with(['warning' => 'Invalid 2FA reset verification code!']);
        if(strtotime($meta->email_expire) < time()) return redirect()->route('login')->with(['warning' => 'Expired 2FA reset verification code!']);
        $user = $meta->user;

        return view('auth.reset2fa', compact('user', 'meta', 'token'));
    }

    public function reset_2fa(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string'
        ]);
        $token = $request->token;
        $meta = UserMeta::where('email_token', $token)->first();
        if(!isset($meta->user)) return redirect()->route('login')->with(['warning' => 'Invalid 2FA reset verification code!']);
        $user = $meta->user;
        $check = Hash::check($request->password, $user->password);
        if($check){
            $user->google2fa = 0;
            $user->google2fa_secret = null;
            $user->save();
            Auth::login($user);

            if ($user->meta) {
                $user->meta->email_token = str_random(65);
                $user->meta->email_expire = now()->addMinutes(75);
                $user->meta->save();
            }
            session()->flash('info', 'Successfully reset your 2FA authentication!');
            if($user->role == 'admin'){
                return redirect()->route('admin.home');
            }
            return redirect()->route('user.home');
        }
        return back()->with(['error' => 'Invalid password!']);
    }

}