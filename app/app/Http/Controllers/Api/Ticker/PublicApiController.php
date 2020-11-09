<?php

namespace App\Http\Controllers\Api\Ticker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PublicApiRequest;
use App\Http\Requests\ChartData\ChartDataRequest;
use App\Models\Coin\CoinPair;
use App\Models\Exchange\Exchange;
use App\Models\Order\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicApiController extends Controller
{
    public function __invoke(PublicApiRequest $request)
    {
        switch ($request->get('command')) {
            case 'returnTicker' :
                return $this->returnTicker($request);
            case 'returnOrderBook' :
                return $this->returnOrderBook($request);
            case 'returnTradeHistory' :
                return $this->returnTradeHistory($request);
            case 'returnChartData' :
                return $this->returnChartData($request);
            default:
                return [];
        }
    }

    public function returnTicker($request)
    {
        $coinPairs = CoinPair::select('base_coin', 'trade_coin', 'name', 'last_price')
            ->where('is_active', ACTIVE)
            ->when($request->get('tradePair'), function ($query) use($request) {
                $query->where('name', $request->tradePair);
            })
            ->with('exchangeSummary', 'coin')
            ->get();

        $formattedCoinPair = [];

        foreach($coinPairs as $coinPair) {
            $formattedCoinPair[$coinPair->name] = [
                'last' => $coinPair->last_price,
                'low24hr' => "0",
                'high24hr' => "0",
                'change' => "0",
                'tradeVolume' => "0",
                'baseVolume' => "0",
            ];

            if ($coinPair->exchangeSummary !== null) {
                $formattedCoinPair[$coinPair->name]['low24hr'] = $coinPair->exchangeSummary->low_price;
                $formattedCoinPair[$coinPair->name]['high24hr'] = $coinPair->exchangeSummary->high_price;
                $formattedCoinPair[$coinPair->name]['tradeVolume'] = $coinPair->exchangeSummary->trade_coin_volume;
                $formattedCoinPair[$coinPair->name]['baseVolume'] = $coinPair->exchangeSummary->base_coin_volume;
                $formattedCoinPair[$coinPair->name]['change'] = bcmul(bcdiv(bcsub($coinPair->last_price, $coinPair->exchangeSummary->first_price), $coinPair->exchangeSummary->first_price), '100', 2);
            }
        }

        $response = $request->has('tradePair') ?  $formattedCoinPair[$request->get('tradePair')] : $formattedCoinPair;

        return response()->json($response);
    }

    public function returnOrderBook($request)
    {
        $conditions = [
            'trade_pair' => $request->get('tradePair'),
            'status' => STATUS_PENDING
        ];

        $bidOrders = Order::where($conditions)
            ->select([
                'price',
                DB::raw('TRUNCATE(SUM(amount - exchanged), 8) as amount'),
                DB::raw('TRUNCATE((price*SUM(amount - exchanged)), 8) as total')
            ])
            ->where('type', ORDER_TYPE_BUY)
            ->whereIn('category', [ORDER_CATEGORY_LIMIT, ORDER_CATEGORY_STOP_LIMIT])
            ->groupBy('price')
            ->orderByDesc('price')
            ->take(50)
            ->get();

        $askOrders =  Order::where($conditions)
            ->select([
                'price',
                DB::raw('TRUNCATE(SUM(amount - exchanged), 8) as amount'),
                DB::raw('TRUNCATE((price*SUM(amount - exchanged)), 8) as total')
            ])
            ->where('type', ORDER_TYPE_SELL)
            ->whereIn('category', [ORDER_CATEGORY_LIMIT, ORDER_CATEGORY_STOP_LIMIT])
            ->groupBy('price')
            ->orderBy('price')
            ->take(50)
            ->get();

        $response = [
            'asks' => $askOrders,
            'bids' => $bidOrders,
        ];

        return response()->json($response);
    }

    public function returnTradeHistory($request)
    {
        $conditions = [
            'trade_pair' => $request->get('tradePair'),
            'is_maker' => ACTIVE
        ];

        $response = Exchange::select([
            'price',
            'amount',
            'total',
            'order_type as type',
            'created_at as date',
        ])
            ->where($conditions)
            ->when($request->has('start') && $request->has('end'), function ($query) use($request) {
                $query->whereBetween('created_at', [
                    date('Y-m-d H:i:s',  $request->get('start')),
                    date('Y-m-d H:i:s',  $request->get('end'))]
                );
            })
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        return response()->json($response);
    }

    public function returnChartData($request)
    {
        $interval = $request->get('interval', 300);
        $tradePair = $request->get('tradePair');
        $start = (int)$request->get('start');
        $end = (int)$request->get('end');

        $response = Exchange::query()
            ->selectRaw('floor(min(UNIX_TIMESTAMP(created_at)) / ? ) * ?  as date', [$interval, $interval])
            ->selectRaw('MIN(price) as low, MAX(price) as high')
            ->selectRaw('SUM(amount) as volume')
            ->selectRaw("SUBSTRING_INDEX(MIN(CONCAT(created_at, '_', price)), '_', -1) as open")
            ->selectRaw("SUBSTRING_INDEX(MAX(CONCAT(created_at, '_', price)), '_', -1) as close")
            ->where('trade_pair', $tradePair)
            ->where('is_maker', ACTIVE)
            ->whereRaw('UNIX_TIMESTAMP(created_at) > ?', $start)
            ->when($request->has('end'), function($query) use($end) {
                $query->whereRaw('UNIX_TIMESTAMP(created_at) < ?', $end);
            })
            ->groupByRaw('FLOOR(UNIX_TIMESTAMP(created_at) / ?)', [$interval])
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($response);
    }
}
