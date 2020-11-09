<?php

namespace App\Http\Controllers\Withdrawal;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\Wallet\Wallet;
use App\Services\Core\DataTableService;
use Illuminate\View\View;

class AdminUserWithdrawalController extends Controller
{
    public function index(User $user, Wallet $wallet): View
    {
        $data['userId'] = $user;

        $searchFields = [
            ['id', __('Reference ID')],
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['txn_id', __('Txn ID')],
            ['symbol', __('Wallet')]
        ];

        $orderFields = [
            ['created_at', __('Date')],
            ['symbol', __('Wallet')]
        ];

        $filterFields = [
            ['status', __('Status'), transaction_status()],
        ];

        $queryBuilder = $wallet->withdrawals()
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['title'] = __('Withdrawal History: :user', ['user' => $user->profile->full_name]);
        return view('withdrawal.user.user_withdrawal_history', $data);
    }
}
