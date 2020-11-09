<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Services\Core\DataTableService;
use Illuminate\View\View;

class AdminUserWalletController extends Controller
{
    public function index(User $user): View
    {
        $searchFields = [
            ['symbol', __('Symbol'), 'coin'],
            ['name', __('Name'), 'coin'],
        ];

        $orderFields = [
            ['symbol', __('Symbol')],
            ['name', __('Wallet Name')],
            ['primary_balance', __('Primary Balance')],
        ];

        $filterFields = [
            ['primary_balance', __('Balance'), 'preset', null,
                [
                    [__('Hide 0(zero) balance'), '>', 0],
                ]
            ],
        ];

        $queryBuilder = $user->wallets()
            ->with('coin','user')
            ->withoutSystemWallet()
            ->orderBy('primary_balance', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['title'] = __('User Wallet: :user', ['user' => $user->profile->full_name]);
        return view('wallets.admin.user_wallets', $data);
    }
}
