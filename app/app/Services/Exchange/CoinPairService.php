<?php


namespace App\Services\Exchange;


use App\Models\Coin\CoinPair;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class CoinPairService
{
    public function getPair($conditions)
    {
        return CoinPair::where($conditions)
            ->leftJoin('coins as base_coins', 'coin_pairs.base_coin', '=', 'base_coins.symbol')
            ->leftJoin('coins as coins', 'coin_pairs.trade_coin', '=', 'coins.symbol')
            ->select($this->_selectData());
    }

    public function _selectData(): array
    {
        return [
            'coin_pairs.name',
            'coins.symbol as trade_coin_symbol',
            'coins.name as trade_coin_name',
            'coins.type as trade_coin_name_type',
            'coins.icon as trade_coin_icon',
            // base item
            'base_coins.symbol as base_coin_symbol',
            'base_coins.name as base_coin_name',
            'base_coins.type as base_coin_type',
            'base_coins.icon as base_coin_icon',
            // 24hr pair detail
            'last_price',
            'exchange_24',
            //summary
            'coin_pairs.base_coin_buy_order_volume',
            'coin_pairs.coin_buy_order_volume',
            'coin_pairs.base_coin_sale_order_volume',
            'coin_pairs.coin_sale_order_volume',
            'coin_pairs.exchanged_buy_total',
            'coin_pairs.exchanged_sale_total',
            'coin_pairs.exchanged_amount',
            'coin_pairs.exchanged_maker_total',
            'coin_pairs.exchanged_buy_fee',
            'coin_pairs.exchanged_sale_fee',
            'coin_pairs.is_active',
            'coin_pairs.is_default',
            'coin_pairs.created_at',
        ];
    }

    public function _generateExchangeSummary(&$coinPair, $date): void
    {
        $exchange24 = $coinPair->exchange_24;
        $coinPair->exchanged_coin_volume_24 = 0;
        $coinPair->exchanged_base_coin_volume_24 = 0;
        $coinPair->high_24 = 0;
        $coinPair->low_24 = 0;
        $coinPair->change_24 = 0;
        $coinPair->trade_pair = $coinPair->name;
        $coinPair->trade_coin_icon = get_coin_icon($coinPair->trade_coin_icon);
        $coinPair->base_coin_icon = get_coin_icon($coinPair->base_coin_icon);

        if (!empty($exchange24)) {
            foreach ($exchange24 as $time => $data) {
                if ($date > $time) {
                    unset($exchange24[$time]);
                } else {
                    break;
                }
            }

            if (!empty($exchange24)) {
                $firstPrice = Arr::first(($exchange24))['price'];
                $lastPrice = Arr::first(($exchange24))['price'];

                $coinPair->exchanged_coin_volume_24 = array_sum(array_column($exchange24, 'amount'));
                $coinPair->exchanged_base_coin_volume_24 = array_sum(array_column($exchange24, 'total'));

                $coinPair->high_24 = max(array_column($exchange24, 'price'));
                $coinPair->low_24 = min(array_column($exchange24, 'price'));
                $coinPair->change_24 = bcmul(bcdiv(bcsub($lastPrice, $firstPrice), $firstPrice), '100');
            }
        }
    }

    public function getCoinMarket(): array
    {
        $coinMarkets = $this->_getAllcoinPairDetailByConditions();
        $baseCoins = [];

        foreach( $coinMarkets as $coinMarket )
        {
            $baseCoins[$coinMarket->base_coin_symbol] = get_coin_icon($coinMarket->base_coin_icon);
        }

        return [
            'coins' => $coinMarkets->toArray(),
            'baseCoins' => $baseCoins,
        ];
    }

    public function _getAllCoinPairDetailByConditions(): Collection
    {
        $coinPairs = $this->getPair($this->_conditions())->get();
        $date = Carbon::now()->subDay()->timestamp;

        foreach ($coinPairs as $coinPair) {
            $this->_generateExchangeSummary($coinPair, $date);
        }

        return $coinPairs;
    }

    public function _conditions(): array
    {
        return [
            'coin_pairs.is_active' => ACTIVE,
            'coins.is_active' => ACTIVE,
            'base_coins.is_active' => ACTIVE,
            'base_coins.exchange_status' => ACTIVE,
            'coins.exchange_status' => ACTIVE,
        ];
    }

    public function refactorCoinPair(&$coinPairs): void
    {
        $date = Carbon::now()->subDay()->timestamp;
        foreach ($coinPairs as $coinPair) {
            $this->_generateExchangeSummary($coinPair, $date);
        }
    }
}
