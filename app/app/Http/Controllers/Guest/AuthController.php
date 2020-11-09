<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Jobs\Wallet\GenerateUserWalletsJob;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Core\{LoginRequest, NewPasswordRequest, PasswordResetRequest, RegisterRequest};
use App\Models\Core\User;
use App\Services\Core\{AuthService, UserActivityService, UserService, VerificationService};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function loginForm(): View
    {
        return view('core.no_header_pages.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $response = $this->service->login($request);

        if (Auth::check()) {
            Cookie::queue(Cookie::forget('coinPair'));
            app(UserActivityService::class)->store(Auth::id(), 'login');
            return redirect()->route(REDIRECT_ROUTE_TO_USER_AFTER_LOGIN)->with($response[RESPONSE_STATUS_KEY], $response[RESPONSE_MESSAGE_KEY]);
        }

        return redirect()->back()->with($response[RESPONSE_STATUS_KEY], $response[RESPONSE_MESSAGE_KEY]);
    }

    public function logout(): RedirectResponse
    {
        $userId = Auth::id();
        Auth::logout();
        app(UserActivityService::class)->store($userId, 'logout');
        session()->flush();
        return redirect()->route('login');
    }

    public function register(): View
    {
        return view('core.no_header_pages.register');
    }

    public function storeUser(RegisterRequest $request): RedirectResponse
    {
        $parameters = $request->only(['first_name', 'last_name', 'email', 'username', 'password', 'referral_id']);
        $response = app(UserService::class)->generate($parameters);

        if ( $response[RESPONSE_STATUS_KEY] ) {
            if (env('QUEUE_CONNECTION', 'sync') === 'sync') {
                GenerateUserWalletsJob::dispatchNow($response[RESPONSE_DATA]['user']);
            } else {
                GenerateUserWalletsJob::dispatch($response[RESPONSE_DATA]['user']);
            }
            app(VerificationService::class)->_sendEmailVerificationLink($response[RESPONSE_DATA]['user']);

            return redirect()->route('login')->with(RESPONSE_TYPE_SUCCESS, $response[RESPONSE_MESSAGE_KEY]);
        }

        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, $response[RESPONSE_MESSAGE_KEY]);
    }

    public function forgetPassword(): View
    {
        return view('core.no_header_pages.forget_password');
    }

    public function sendPasswordResetMail(PasswordResetRequest $request): RedirectResponse
    {
        $response = $this->service->sendPasswordResetMail($request);
        $status = $response[RESPONSE_STATUS_KEY] ? RESPONSE_TYPE_SUCCESS : RESPONSE_TYPE_ERROR;

        return redirect()->route('forget-password.index')->with($status, $response[RESPONSE_MESSAGE_KEY]);
    }

    public function resetPassword(Request $request, User $user): View
    {
        $data = $this->service->resetPassword($request, $user->id);

        return view('core.no_header_pages.reset_password', $data);
    }

    public function updatePassword(NewPasswordRequest $request, User $user): RedirectResponse
    {
        $response = $this->service->updatePassword($request, $user);

        if ($response[RESPONSE_STATUS_KEY]){
            app(UserActivityService::class)->store($user->id, 'reset password');

            return redirect()->route('login')->with(RESPONSE_TYPE_SUCCESS, $response[RESPONSE_MESSAGE_KEY]);
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, $response[RESPONSE_MESSAGE_KEY]);
    }
}
