<?php

namespace App\Http\Controllers\Exchange;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $conditions = $this->_conditions($request);

        return response()->json([
            'coinOrders' => $this->_getOrders($conditions),
            'totalCoinOrder' => $this->_getTotalOrder($conditions)
        ]);
    }

    public function _conditions($request): array
    {
        $conditions = [
            'trade_pair' => $request->coin_pair,
            'type' => $request->order_type,
            'status' => STATUS_PENDING
        ];

        if (!empty($request->last_price)) {
            $request->get('order_type') === ORDER_TYPE_SELL ?
                array_push($conditions, ['price', '>', $request->last_price]) :
                array_push($conditions, ['price', '<', $request->last_price]);
        }

        return $conditions;
    }

    public function _getOrders($conditions): Collection
    {
        return Order::where($conditions)
            ->select([
                'price',
                DB::raw('TRUNCATE(SUM(amount - exchanged), 8) as amount'),
                DB::raw('TRUNCATE((price*SUM(amount - exchanged)), 8) as total')
            ])
            ->whereIn('category', [ORDER_CATEGORY_LIMIT, ORDER_CATEGORY_STOP_LIMIT])
            ->when(
                $conditions['type'] === ORDER_TYPE_BUY,
                static function ($query) {
                    $query->orderBy('price', 'desc');
                },
                static function ($query) {
                    $query->orderBy('price', 'asc');
                }
            )
            ->groupBy('price')
            ->take(50)
            ->get();
    }

    public function _getTotalOrder($conditions): Order
    {
        return Order::where($conditions)
            ->select([
                DB::raw('TRUNCATE(SUM((amount - exchanged)*price), 8) as base_coin_total'),
                DB::raw('TRUNCATE(SUM(amount - exchanged), 8) as trade_coin_total')
            ])
            ->first();
    }
}
