<?php

namespace App\Services\Exchange;

use App\Models\Exchange\Exchange;
use Illuminate\Database\Eloquent\Collection;

class GetLatestTradeService
{
    public function getTrades(array $conditions, $take = TRADE_HISTORY_PER_PAGE): Collection
    {
        return Exchange::select([
            'price',
            'amount',
            'total',
            'order_type',
            'created_at as date',
        ])
            ->where($conditions)
            ->orderBy('created_at', 'desc')
            ->take($take)
            ->get();
    }
}
