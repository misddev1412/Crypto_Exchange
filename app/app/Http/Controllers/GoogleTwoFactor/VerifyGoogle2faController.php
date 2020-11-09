<?php

namespace App\Http\Controllers\GoogleTwoFactor;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Google2faRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class VerifyGoogle2faController extends Controller
{
    public function verify(Google2faRequest $request): RedirectResponse
    {
        $google2fa = app('pragmarx.google2fa');

        try {
            if ($google2fa->verifyKey(Auth::user()->google2fa_secret, $request->google_app_code)) {
                $authenticator = app(Authenticator::class)->boot($request);
                $authenticator->login();

                return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __("The One Time Password was correct."));
            }

            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to verify google authentication.'));
        } catch (Exception $exception) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to verify google authentication.'));
        }
    }
}
