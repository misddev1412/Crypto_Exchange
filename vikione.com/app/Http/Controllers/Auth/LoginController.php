<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Activity;
use App\Models\UserMeta;
use App\Helpers\ReCaptcha;
use App\Helpers\IcoHandler;
use Jenssegers\Agent\Agent;
use App\Notifications\UnusualLogin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as AuthRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers, ReCaptcha; //, ThrottlesLogins;
    protected $maxAttempts = 6; // Default is 5
    protected $decayMinutes = 15; // Default is 1

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $handler;
    public function __construct(IcoHandler $handler)
    {
        $this->handler = $handler;
        $this->middleware('guest')
            ->except(['logout', 'log-out', 'verified', 'registered', 'checkLoginState']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request as AuthRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(AuthRequest $request)
    {
        if(recaptcha()) {
            $this->checkReCaptcha($request->recaptcha);
        }

        $this->validateLogin($request);
        $attempt = $this->hasTooManyLoginAttempts($request);
        
        if ($attempt) {
            $this->fireLockoutEvent($request);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            $totalAttempts = $this->totalAttempts($request);
            if ($user && $totalAttempts < $this->maxAttempts) {
                $userMeta = UserMeta::where('userId', $user->id)->first();
                if ($userMeta->unusual == 1) {
                    try{
                        $user->notify(new UnusualLogin($user));
                    }catch(\Exception $e){
                    } finally{
                        $this->incrementLoginAttempts($request);
                    } 
                }
            }

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Return how much time attempts to login 
     * 
     * @version 1.0.0
     * @param Illuminate\Http\Request as $request
     * @return integer
     */
    public function totalAttempts(AuthRequest $request)
    {
        return $this->limiter()->attempts($this->throttleKey($request));
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (!file_exists(storage_path('installed'))) {
            return redirect(url('/install'));
        }

        $have_user = User::where('role', 'admin')->count();
        if(!$have_user){
            return redirect(url('/register?setup=admin'));
        }
        return view('auth.login');
    }


    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request as AuthRequest $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendLockoutResponse(AuthRequest $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        $seconds = ($seconds >= 60 ? gmdate('i', $seconds).' minutes.' : $seconds.' seconds.');

        throw ValidationException::withMessages([
            $this->username() => [__('auth.throttle', ['seconds' => $seconds])],
        ])->status(429);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
	 /**Check Template Rex*/
     protected function authenticated()
    {
        $user = Auth::user();
        $user->lastLogin = now();
        $user->save();
        if (UserMeta::getMeta(Auth::id())->save_activity == 'TRUE') {
            $agent = new Agent();

            $ret['activity'] = Activity::create([
                'user_id' => Auth::id(),
                'browser' => $agent->browser() . '/' . $agent->version($agent->browser()),
                'device' => $agent->device() . '/' . $agent->platform() . '-' . $agent->version($agent->platform()),
                'ip' => request()->ip(),
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkLoginState(AuthRequest $request)
    {
        if (application_installed(true) == false) {
            return redirect(url('/install'));
        }
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status == 'active') {
                $link = ($user->role == 'admin') ? '/admin' : '/user';
                return redirect(url('/') . $link);
            } else {
                Auth::logout();
                return redirect(route('login'))->with(['danger' => __('messages.login.inactive')]);
            }
        } else {
            return redirect(url('/login'));
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        session()->forget('_g2fa_session');
        return (!is_maintenance() ? redirect(route('login')) : redirect(route('admin.login')));
    }


    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function loggedOut(Request $request)
    {
        session()->forget('_g2fa_session');
        return (! is_maintenance() ? redirect(route('login')) : redirect(route('admin.login')));
    }
    

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verified()
    {
        Auth::logout();
        return view('auth.message')->with(['text' => __('messages.verify.success.heading'), 'subtext' => __('messages.verify.success.subhead'), 'msg' => __('messages.verify.success.msg')]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */

    public function registered()
    {
        Auth::logout();
        $data = ['text' => __('messages.register.success.heading'), 'subtext' => __('messages.register.success.subhead'), 'msg' => ['type' => 'success', 'text' => __('messages.register.success.msg')]];
        $last_url = str_replace(url('/'), '', url()->previous());
        if ($last_url == '/register') {
            return view('auth.message')->with($data);
        }
        return redirect('/login');
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request as AuthRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(AuthRequest $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [__('auth.failed')],
        ]);
    }

}
