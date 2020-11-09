<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Services\Core\DataTableService;

class AdminUserOpenOrderController extends Controller
{
    public function index(User $user)
    {
        $searchFields = [
            ['trade_coin', __('Coin')],
            ['base_coin', __('Base Coin')],
            ['trade_pair', __('Coin Pair')],
            ['amount', __('Amount')],
            ['price', __('Price')],
        ];

        $orderFields = [
            ['trade_coin', __('Coin')],
            ['base_coin', __('Base Coin')],
            ['trade_pair', __('Coin Pair')],
            ['type', __('Type')],
            ['amount', __('Amount')],
            ['price', __('Price')],
        ];

        $filterFields = [
            ['type', __('Type'), order_type()],
        ];

        $queryBuilder = $user->orders()
            ->with('coin')
            ->statusOpen();

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['title'] = __('Open Orders: :user', ['user' => $user->profile->full_name]);
        return view('order.admin.open_orders', $data);
    }
}
