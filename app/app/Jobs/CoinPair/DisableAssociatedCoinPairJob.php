<?php

namespace App\Jobs\CoinPair;

use App\Models\Coin\Coin;
use App\Models\Coin\CoinPair;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DisableAssociatedCoinPairJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $coin;

    public function __construct(Coin $coin)
    {
        $this->coin = $coin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CoinPair::where(function ($query){
                $query->where('trade_coin', $this->coin->symbol)
                    ->orWhere('base_coin', $this->coin->symbol);
            })
            ->where('is_active', ACTIVE)
            ->where('is_default', INACTIVE)
            ->update(['is_active' => INACTIVE]);
    }
}
