<?php

namespace App\Http\Controllers\TradeHistory;

use App\Http\Controllers\Controller;
use App\Models\Exchange\Exchange;
use App\Services\Core\DataTableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyTradeHistoryController extends Controller
{
    public function __invoke(): View
    {
        $data['title'] = __('Trades');

        $searchFields = [
            ['trade_pair', __('Market')],
        ];

        $orderFields = [
            ['exchanges', __('Date')],
        ];
        $filterFields = [
            ['order_type', __('Type'), order_type()],
        ];

        $queryBuilder = Exchange::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);
        return view('trading.user.index', $data);
    }
}
