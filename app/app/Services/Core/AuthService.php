<?php

namespace App\Services\Core;

use App\Http\Requests\Core\LoginRequest;
use App\Http\Requests\Core\NewPasswordRequest;
use App\Http\Requests\Core\PasswordResetRequest;
use App\Mail\Core\ResetPassword;
use App\Models\Core\Notification;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function login(LoginRequest $request): array
    {
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $request->username, 'password' => $request->password], $request->has('remember_me'))) {
            $user = Auth::user();

            // check if user is deleted or not.
            if ($user->status == STATUS_DELETED) {
                Auth::logout();
                return [
                    RESPONSE_STATUS_KEY => RESPONSE_TYPE_ERROR,
                    RESPONSE_MESSAGE_KEY => __('You account is deleted.'),
                ];
            } elseif ($user->status == STATUS_INACTIVE) {
                return [
                    RESPONSE_STATUS_KEY => RESPONSE_TYPE_WARNING,
                    RESPONSE_MESSAGE_KEY => __('You account is currently inactive.'),
                ];
            } elseif (!$user->is_accessible_under_maintenance && settings('maintenance_mode')) {
                Auth::logout();
                return [
                    RESPONSE_STATUS_KEY => RESPONSE_TYPE_WARNING,
                    RESPONSE_MESSAGE_KEY => __('Application is under maintenance mode. Please try later.'),
                ];
            }

            return [
                RESPONSE_STATUS_KEY => RESPONSE_TYPE_SUCCESS,
                RESPONSE_MESSAGE_KEY => __('Login is successful.'),
            ];
        }

        return [
            RESPONSE_STATUS_KEY => RESPONSE_TYPE_ERROR,
            RESPONSE_MESSAGE_KEY => __('Incorrect :field or password', ['field' => $field])
        ];
    }

    public function sendPasswordResetMail(PasswordResetRequest $request): array
    {
        $conditions = [
            'email' => $request->email,
            ['status', '!=', STATUS_DELETED]
        ];
        $user = User::where($conditions)->first();

        if (!$user) {
            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Failed! Your account is deleted by admin."),
            ];
        }

        Mail::to($user->email)->send(new ResetPassword($user));

        return [
            RESPONSE_STATUS_KEY => true,
            RESPONSE_MESSAGE_KEY => __("Password reset link is sent to your email address."),
        ];
    }

    public function resetPassword(Request $request, $id)
    {
        abort_unless($request->hasValidSignature(), 401, 'Invalid Request.');

        $passwordResetLink = url()->signedRoute('reset-password.update', ['user' => $id]);

        return [
            'id' => $id,
            'passwordResetLink' => $passwordResetLink
        ];
    }

    public function updatePassword(NewPasswordRequest $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Invalid request"),
            ];
        }

        $update = ['password' => Hash::make($request->new_password)];

        if ($user->update($update)) {
            $notification = ['user_id' => $user->id, 'message' => __("You just reset your account's password successfully.")];
            Notification::create($notification);

            return [
                RESPONSE_STATUS_KEY => true,
                RESPONSE_MESSAGE_KEY => __("New password is updated. Please login your account."),
            ];
        }

        return [
            RESPONSE_STATUS_KEY => false,
            RESPONSE_MESSAGE_KEY => __("Failed to set new password. Please try again."),
        ];
    }
}
