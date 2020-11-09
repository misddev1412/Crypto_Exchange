<?php

namespace App\Http\Controllers\Exchange;

use App\Http\Controllers\Controller;
use App\Models\Coin\CoinPair;
use App\Services\Exchange\CoinPairService;
use Carbon\Carbon;

class PairDetailsController extends Controller
{
    protected $coinPair;

    public function get24HrPairDetail(CoinPair $coinPair)
    {
        $this->coinPair = $coinPair->getRawOriginal("name");
        $get24hrPairData = $this->getFirstCoinPairDetailByConditions();

        if (empty($get24hrPairData)) {
            return false;
        }

        $pair24HrDetail = $this->_details($get24hrPairData);

        return response()->json($pair24HrDetail);
    }

    public function getFirstCoinPairDetailByConditions(): CoinPair
    {
        $coinPair = app(CoinPairService::class)->getPair($this->_conditions())->first();

        $date = Carbon::now()->subDay()->timestamp;
        app(CoinPairService::class)->_generateExchangeSummary($coinPair, $date);

        return $coinPair;
    }

    public function _conditions(): array
    {
        return [
            'coin_pairs.name' => $this->coinPair,
            'coin_pairs.is_active' => ACTIVE,
            'coins.is_active' => ACTIVE,
            'base_coins.is_active' => ACTIVE,
            'base_coins.exchange_status' => ACTIVE,
            'coins.exchange_status' => ACTIVE,
        ];
    }

    public function _details($get24hrPairData): array
    {
        return [
            'baseCoin' => $get24hrPairData->base_coin_symbol,
            'coin' => $get24hrPairData->trade_coin_symbol,
            'lastPrice' => $get24hrPairData->last_price,
            'change24hrInPercent' => $get24hrPairData->change_24,
            'high24hr' => $get24hrPairData->high_24,
            'low24hr' => $get24hrPairData->low_24,
            'baseVolume' => $get24hrPairData->exchanged_base_coin_volume_24,
            'coinVolume' => $get24hrPairData->exchanged_coin_volume_24
        ];
    }
}

