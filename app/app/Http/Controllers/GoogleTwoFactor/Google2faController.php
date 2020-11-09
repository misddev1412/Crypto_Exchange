<?php

namespace App\Http\Controllers\GoogleTwoFactor;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Google2faRequest;
use App\Models\Core\User;
use App\Services\Core\ProfileService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2faController extends Controller
{
    public function create(): View
    {
        $data = app(ProfileService::class)->profile();
        $data['title'] = __('Google Two Factor Authentication');
        if (empty(Auth::user()->google2fa_secret)) {
            $google2fa = app('pragmarx.google2fa');
            $key = Session::get('google_two_factor_code', $google2fa->generateSecretKey(16));
            Session::put('google_two_factor_code', $key);
            $data['secretKey'] = $key;
            $data['inlineUrl'] = $google2fa->getQRCodeInline(company_name(), Auth::user()->email, $data['secretKey'], 250);
        }
        return view('google2fa.create', $data);
    }

    public function store(Google2faRequest $request, $googleCode): RedirectResponse
    {
        $google2fa = app('pragmarx.google2fa');

        try {
            if ($google2fa->verifyKey($googleCode, $request->google_app_code)) {
                $updated = Auth::user()->update(['google2fa_secret' => $googleCode]);

                if ($updated) {
                    $authenticator = app(Authenticator::class)->boot($request);
                    $authenticator->logout();
                    Session::forget('google_two_factor_code');
                    Auth::logout();
                    return redirect()->route('login')->with(RESPONSE_TYPE_SUCCESS, __('Google Authentication has been enabled successfully.'));
                }
            }

            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to enable google authentication.'));
        } catch (Exception $exception) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to enable google authentication.'));
        }

    }

    public function destroy(Google2faRequest $request): RedirectResponse
    {
        $google2fa = app('pragmarx.google2fa');

        try {
            if ($google2fa->verifyKey(Auth::user()->google2fa_secret, $request->google_app_code)) {
                $updated = User::where(['id' => Auth::user()->id])->first()->update(['google2fa_secret' => null]);
                if ($updated) {
                    return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Google Authentication has been disabled successfully.'));
                }
            }

            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to disabled google authentication.'));
        } catch (Exception $exception) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to disabled google authentication.'));
        }
    }
}
