<?php

namespace App\Http\Controllers\Exchange;

use App\Http\Controllers\Controller;
use App\Services\Exchange\GetLatestTradeService;
use Illuminate\Http\Request;

class UserTradesController extends Controller
{
    public $getLatestTradeService;

    public function __construct(GetLatestTradeService $getLatestTradeService)
    {
        $this->getLatestTradeService = $getLatestTradeService;
    }

    public function __invoke(Request $request)
    {
        $tradeHistories = $this->getLatestTradeService->getTrades($this->_conditions($request), 10);

        return response()->json($tradeHistories);
    }

    public function _conditions($request): array
    {
        return [
            'trade_pair' => $request->coin_pair,
            'user_id' => auth()->id()
        ];
    }
}
