<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\PasswordResetRequest;
use App\Services\Core\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $response = app(VerificationService::class)->verifyUserEmail($request);
        $status = $response[RESPONSE_STATUS_KEY] ? RESPONSE_TYPE_SUCCESS : RESPONSE_TYPE_ERROR;
        $route = Auth::check() ? REDIRECT_ROUTE_TO_USER_AFTER_LOGIN : REDIRECT_ROUTE_TO_LOGIN;

        return redirect()->route($route)->with($status, $response[RESPONSE_MESSAGE_KEY]);
    }

    public function resendForm()
    {
        return view('core.no_header_pages.email_verify');
    }

    public function send(PasswordResetRequest $request)
    {
        $response = app(VerificationService::class)->sendVerificationLink($request);
        $status = $response[RESPONSE_STATUS_KEY] ? RESPONSE_TYPE_SUCCESS : RESPONSE_TYPE_ERROR;

        return redirect()->back()->with($status, $response[RESPONSE_MESSAGE_KEY]);
    }
}
