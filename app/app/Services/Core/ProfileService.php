<?php

namespace App\Services\Core;

use App\Http\Requests\Core\{PasswordUpdateRequest, UserAvatarRequest};
use App\Models\Core\Notification;
use App\Models\Exchange\Exchange;
use App\Models\Order\Order;
use App\Models\Wallet\Wallet;
use Illuminate\Support\Facades\{Auth, Hash};

class ProfileService
{
    public function profile()
    {
        return ['user' => Auth::user()->load('role')];
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $update = ['password' => Hash::make($request->new_password)];

        if (Auth::user()->update($update)) {
            $notification = ['user_id' => Auth::id(), 'message' => __("You just changed your account's password.")];
            Notification::create($notification);

            return [
                RESPONSE_STATUS_KEY => true,
                RESPONSE_MESSAGE_KEY => __('Password has been changed successfully.')
            ];
        }

        return [
            RESPONSE_STATUS_KEY => false,
            RESPONSE_MESSAGE_KEY => __('Failed to change password.')
        ];
    }

    public function avatarUpload(UserAvatarRequest $request)
    {
        $uploadedAvatar = app(FileUploadService::class)->upload($request->file('avatar'), config('commonconfig.path_profile_image'), 'avatar', 'user', Auth::id(), 'public', 300, null, false);

        if ($uploadedAvatar) {
            $parameters = ['avatar' => $uploadedAvatar];

            if (Auth::user()->update($parameters)) {
                return [
                    RESPONSE_STATUS_KEY => RESPONSE_TYPE_SUCCESS,
                    RESPONSE_MESSAGE_KEY => __('Avatar has been uploaded successfully.'),
                    'avatar' => get_avatar($uploadedAvatar),
                ];
            }
        }

        return [
            RESPONSE_STATUS_KEY => RESPONSE_TYPE_ERROR,
            RESPONSE_MESSAGE_KEY => __('Failed to upload the avatar.')
        ];
    }

    public function routesForAdmin($userId)
    {
        $userRelatedInfo = $this->userRelatedInfo($userId);
        $info = [
            'walletRouteName' => 'admin.users.wallets.index',
            'walletRoute' => route('admin.users.wallets.index', ['user' => $userId]),
            'openOrderRouteName' => 'admin.users.order.open',
            'openOrderRoute' => route('admin.users.order.open', ['user' => $userId]),
            'tradeHistoryRouteName' => 'admin.users.trading-history',
            'tradeHistoryRoute' => route('admin.users.trading-history', ['user' => $userId]),
        ];

        return array_merge($userRelatedInfo, $info);
    }

    public function userRelatedInfo($userId)
    {
        $totalWallets = Wallet::where(['user_id' => $userId])->count();
        $totalOpenOrders = Order::where(['user_id' => $userId, 'status' => STATUS_PENDING])->count();
        $totalTrades = Exchange::where(['user_id' => $userId])->count();
        return [
            'totalWallets' => $totalWallets,
            'totalOpenOrders' => $totalOpenOrders,
            'totalTrades' => $totalTrades,
        ];
    }

    public function routesForUser($userId)
    {
        $userRelatedInfo = $this->userRelatedInfo($userId);

        $info = [
            'walletRouteName' => 'user.wallets',
            'walletRoute' => route('user.wallets', ['user' => $userId]),
            'openOrderRouteName' => 'trader.order.open-order',
            'openOrderRoute' => route('trader.order.open-order'),
            'tradeHistoryRouteName' => 'reports.trader.trades',
            'tradeHistoryRoute' => route('reports.trader.trades'),
        ];

        return array_merge($userRelatedInfo, $info);
    }
}
