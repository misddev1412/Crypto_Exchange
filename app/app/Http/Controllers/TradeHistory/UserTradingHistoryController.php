<?php

namespace App\Http\Controllers\TradeHistory;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\Exchange\Exchange;
use App\Services\Core\DataTableService;
use Illuminate\View\View;

class UserTradingHistoryController extends Controller
{
    public function index(User $user): View
    {
        $data['title'] = __('Trades');
        $data['userId'] = $user->id;

        $searchFields = [
            ['exchanges.coin_pair_id', __('Market')],
        ];

        $orderFields = [
            ['exchanges.created_at', __('Date')],
        ];

        $select = [
            'exchanges.*',
            'order.category',
            'order.maker_fee',
            'order.taker_fee',
            'coins.id as coin_id',
            'coins.symbol as coin_symbol',
            'coins.name as coin_name',
            'coins.type as coin_type',
            'base_coins.id as base_coin_id',
            'base_coins.symbol as base_coin_symbol',
            'base_coins.name as base_coin_name',
            'base_coins.type as base_type',
            'email',
        ];
        $filterFields = [
            ['order.category', __('Category'), order_categories()],
        ];

        $queryBuilder = Exchange::select($select)
            ->leftJoin('coin_pairs', 'exchanges.coin_pair_id', '=', 'coin_pairs.id')
            ->leftJoin('order', 'exchanges.order_id', '=', 'order.id')
            ->leftJoin('coins as coins', 'coin_pairs.coin_id', '=', 'coins.id')
            ->leftJoin('coins as base_coins', 'coin_pairs.base_coin_id', '=', 'base_coins.id')
            ->leftJoin('users', 'exchanges.user_id', '=', 'users.id')
            ->where('exchanges.user_id', $user->id)
            ->orderBy('id', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);
        return view('trading.admin.user_tradings', $data);
    }
}
