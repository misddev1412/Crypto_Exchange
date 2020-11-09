<?php

namespace App\Http\Controllers\Exchange;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyOpenOrderController extends Controller
{
    public function __invoke(Request $request)
    {
        $getUserOpenOrders = $this->_getUserOpenOrders($request);

        return response()->json($getUserOpenOrders);
    }

    public function _getUserOpenOrders(Request $request)
    {
        return Order::select([
            'id as order_id',
            'price',
            'amount',
            'exchanged',
            DB::raw('TRUNCATE(amount - exchanged,8) as open_amount'),
            DB::raw('TRUNCATE(amount * price,8) as total'),
            'type as order_type',
            'stop_limit',
            'created_at as date'
        ])
            ->where('user_id', auth()->id())
            ->where('trade_pair', $request->coin_pair)
            ->whereIn('category', [ORDER_CATEGORY_LIMIT, ORDER_CATEGORY_STOP_LIMIT])
            ->whereIn('status', [STATUS_PENDING, STATUS_INACTIVE])
            ->latest()
            ->take(MY_OPEN_ORDER_PER_PAGE)
            ->get();
    }
}
