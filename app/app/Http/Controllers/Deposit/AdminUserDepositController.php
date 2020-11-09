<?php

namespace App\Http\Controllers\Deposit;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\Wallet\Wallet;
use App\Services\Core\DataTableService;

class AdminUserDepositController extends Controller
{
    public function index(User $user, Wallet $wallet)
    {
        $searchFields = [
            ['id', __('Reference ID')],
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['bank_name', __('Bank'), 'bankAccount'],
            ['txn_id', __('Transaction ID')],
            ['symbol', __('Wallet')],
        ];

        $orderFields = [
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['symbol', __('Wallet')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['status', __('Status'), transaction_status()],
        ];

        $queryBuilder = $wallet->deposits()
            ->with("bankAccount")
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['title'] = __('Deposit History: :user', ['user' => $user->profile->full_name]);
        return view('deposit.admin.user_deposit_history', $data);
    }
}
