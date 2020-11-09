<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Wallet\Wallet;
use App\Services\Core\DataTableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserWalletController extends Controller
{

    public function index(): View
    {
        $searchFields = [
            ['symbol', __('Wallet'),],
            ['name', __('Wallet Name'), 'coin'],
        ];

        $orderFields = [
            ['symbol', __('Wallet')],
            ['name', __('Wallet Name'), 'coin'],
            ['primary_balance', __('Primary Balance')],
        ];

        $filterFields = [
            ['primary_balance', __('Balance'), 'preset', null,
                [
                    [__('Hide 0(zero) balance'), '>', 0],
                ]
            ],
        ];


        $queryBuilder = Wallet::with('coin:symbol,name,icon')
            ->withOnOrderBalance()
            ->where('user_id', Auth::id())
            ->withoutSystemWallet()
            ->orderBy('primary_balance', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['title'] = __('My Wallet');
        return view('wallets.user.index', $data);
    }
}
