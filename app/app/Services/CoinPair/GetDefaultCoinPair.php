<?php


    namespace App\Services\CoinPair;


    use App\Models\Coin\CoinPair;
    use Illuminate\Support\Facades\Cookie;

    class GetDefaultCoinPair
    {
        public function getCoinPair($pair)
        {
            if (!empty($pair)) {
                return $this->_getCoinPair($pair);
            }

            $cookieCoinPair = Cookie::get('coinPair');

            if (auth()->check() && !empty($cookieCoinPair)) {
                return $this->_getCoinPair($cookieCoinPair);
            } elseif (auth()->check() && !empty(auth()->user()->preference->default_coin_pair)) {
                Cookie::forever('coinPair', auth()->user()->preference->default_coin_pair);
                return $this->_getCoinPair(auth()->user()->preference->default_coin_pair);
            }

            return $this->_getCoinPair();
        }

        public function _getCoinPair($tradePair = null)
        {
            return CoinPair::where('is_active', ACTIVE)
                ->when($tradePair, function ($query) use ($tradePair) {
                    $query->where('name', $tradePair);
                })
                ->when(empty($tradePair), function ($query) {
                    $query->where('is_default', ACTIVE);
                })
                ->first();
        }
    }
