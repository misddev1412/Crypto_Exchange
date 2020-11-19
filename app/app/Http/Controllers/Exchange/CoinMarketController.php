<?php

    namespace App\Http\Controllers\Exchange;

    use App\Http\Controllers\Controller;
    use App\Models\Coin\CoinPair;
    use App\Services\Core\DataTableService;
    use App\Services\Exchange\CoinPairService;
    use Illuminate\Http\JsonResponse;
    use Illuminate\View\View;

    class CoinMarketController extends Controller
    {
        public $coinPairService;

        public function __construct(CoinPairService $coinPairService)
        {
            $this->coinPairService = $coinPairService;
        }

        public function getCoinMarket($baseCoin): JsonResponse
        {
            $baseCoins = cache()->rememberForever('baseCoinsV1', function () {
                $baseCoins = CoinPair::where('is_active', ACTIVE)->pluck('base_coin');

                $baseCoinsArray = [];

                foreach ($baseCoins as $baseCoin) {
                    $baseCoinsArray[$baseCoin] = [
                        'icon_url' => $this->_getCoinIcon($baseCoin),
                        'market_url' => route('exchange.get-coin-market', $baseCoin)
                    ];
                }

                return $baseCoinsArray;
            });

            $coinPairs = CoinPair::select('base_coin', 'trade_coin', 'name', 'last_price')
                ->where('is_active', ACTIVE)
                ->where('base_coin', $baseCoin)
                ->with('exchangeSummary', 'coin')
                ->get();

            $coinPairs = $coinPairs->map(function ($coinPair) {
                $formattedCoinPair = [
                    'trade_coin' => $coinPair->trade_coin,
                    'base_coin' => $coinPair->base_coin,
                    'trade_pair' => $coinPair->name,
                    'trade_pair_name' => $coinPair->trade_pair,
                    'trade_coin_name' => $coinPair->coin->name,
                    'latest_price' => $coinPair->last_price,
                    'low_price' => 0,
                    'high_price' => 0,
                    'trade_coin_volume' => 0,
                    'base_coin_volume' => 0,
                    'change' => 0,
                    'trade_coin_icon' => $this->_getCoinIcon($coinPair->trade_coin),
                    'base_coin_icon' => $this->_getCoinIcon($coinPair->base_coin),
                ];

                if ($coinPair->exchangeSummary !== null) {
                    $formattedCoinPair['low_price'] = $coinPair->exchangeSummary->low_price;
                    $formattedCoinPair['high_price'] = $coinPair->exchangeSummary->high_price;
                    $formattedCoinPair['trade_coin_volume'] = $coinPair->exchangeSummary->trade_coin_volume;
                    $formattedCoinPair['base_coin_volume'] = $coinPair->exchangeSummary->base_coin_volume;
                    $formattedCoinPair['change'] = bcmul(bcdiv(bcsub($coinPair->last_price, $coinPair->exchangeSummary->first_price), $coinPair->exchangeSummary->first_price), '100', 2);
                }

                return $formattedCoinPair;
            });

            $response = [
                'coin_pairs' => $coinPairs,
                'base_coins' => $baseCoins
            ];

            return response()->json($response);
        }

        public function _getCoinIcon($coin): string
        {
            $coinIconSlug = '__' . $coin . COIN_ICON_EXTENSION;

            return get_coin_icon($coinIconSlug);
        }
    }
