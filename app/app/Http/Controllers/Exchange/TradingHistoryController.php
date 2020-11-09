<?php

namespace App\Http\Controllers\Exchange;

use App\Http\Controllers\Controller;
use App\Services\Exchange\GetLatestTradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TradingHistoryController extends Controller
{
    public $getLatestTradeService;

    public function __construct(GetLatestTradeService $getLatestTradeService)
    {
        $this->getLatestTradeService = $getLatestTradeService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $tradeHistories = $this->getLatestTradeService->getTrades($this->_conditions($request));

        return response()->json($tradeHistories);
    }

    public function _conditions($request): array
    {
        return [
            'trade_pair' => $request->coin_pair,
            'is_maker' => ACTIVE
        ];
    }
}
