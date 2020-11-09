<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Services\Core\DataTableService;
use Illuminate\Support\Facades\Auth;

class UserOpenOrderController extends Controller
{
    public function index()
    {
        $searchFields = [
            ['id', __('Reference ID')],
            ['user_id', __('User')],
            ['price', __('Price')],
            ['amount', __('Amount')],
            ['trade_coin', __('Coin')],
            ['base_coin', __('Base Coin')],
            ['coin_pair', __('Coin Pair')],
        ];

        $orderFields = [
            ['price', __('Price')],
            ['amount', __('Amount')],
            ['coin_pair', __('Coin Pair')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['category', __('Category'), order_categories()],
            ['type', __('Type'), order_type()],
        ];

        $data['title'] = __('My Orders');

        $queryBuilder = Order::where('user_id', Auth::id())
            ->where('status', STATUS_PENDING)
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        return view('order.user.open_orders', $data);
    }
}
