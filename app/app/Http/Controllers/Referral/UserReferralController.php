<?php

namespace App\Http\Controllers\Referral;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Services\Core\DataTableService;
use Illuminate\Support\Facades\Auth;

class UserReferralController extends Controller
{
    public function list()
    {
        $searchFields = [
            ['first_name', __('First Name'), 'profile'],
            ['last_name', __('Last Name'), 'profile'],
            ['email', __('Email')],
            ['username', __('Username')],
        ];

        $orderFields = [
            ['first_name', __('First Name'), 'profile'],
            ['last_name', __('Last Name'), 'profile'],
            ['email', __('Email')],
            ['username', __('Username')],
            ['created_at', __('Registration Date')],
        ];

        $data['title'] = __('My Referral Users');

        $queryBuilder = User::with('profile')
            ->where('referrer_id', Auth::id())
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('referral.user.index', $data);
    }
}
