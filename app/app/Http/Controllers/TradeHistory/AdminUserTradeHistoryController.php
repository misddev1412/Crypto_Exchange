<?php

namespace App\Http\Controllers\TradeHistory;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\Exchange\Exchange;
use App\Services\Core\DataTableService;

class AdminUserTradeHistoryController extends Controller
{
    public function __invoke(User $user)
    {
        $searchFields = [
            ['id', __('Reference ID')],
            ['trade_coin', __('Coin')],
            ['base_coin', __('Base Coin')],
            ['trade_pair', __('Coin Pair')],
        ];

        $orderFields = [
            ['price', __('Price')],
            ['amount', __('Amount')],
            ['trade_pair', __('Coin Pair')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['order_type', __('Type'), order_type()],
            ['category', __('Category'), order_categories(), 'order'],
        ];

        $data['title'] = __('User Orders');

        $queryBuilder = $user->tradeHistories()
            ->with('order')
            ->orderByDesc('created_at');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        return view('order.admin.trade_history', $data);
    }
}
