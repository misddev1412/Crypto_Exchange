<?php

namespace App\Services\Core;

use App\Models\Core\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function generate($parameters)
    {
        $userParams = Arr::only($parameters, [
            'email',
            'username',
            'is_email_verified',
            'is_financial_active',
            'is_accessible_under_maintenance',
            'is_active',
            'created_by',
            'password',
            'is_super_admin',
        ]);

        $userParams['password'] = Hash::make($userParams['password']);
        $userParams['referral_code'] = Str::uuid()->toString();
        $userParams['assigned_role'] = settings('default_role_to_register');
        if (Arr::has($parameters, 'referral_id')) {
            $refUser = User::where('referral_code', $parameters['referral_id'])->first();
            if ($refUser) {
                $userParams['referrer_id'] = $refUser->id;
            }
        }

        if (Arr::has($parameters, 'assigned_role')) {
            $userParams['assigned_role'] = $parameters['assigned_role'];
        }

        DB::beginTransaction();
        try {
            $user = User::create($userParams);
            $profileParams = Arr::only($parameters, ['first_name', 'last_name', 'address', 'phone']);
            $user->profile()->create($profileParams);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __('Failed to register.')
            ];
        }

        return [
            RESPONSE_STATUS_KEY => true,
            RESPONSE_MESSAGE_KEY => __('The registration was successful. Please check your email to verify your account.'),
            RESPONSE_DATA => [
                'user' => $user
            ],
        ];
    }
}
