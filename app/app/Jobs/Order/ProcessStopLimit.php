<?php

namespace App\Jobs\Order;

use App\Broadcasts\Exchange\OrderBroadcast;
use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessStopLimit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $coinPair;
    public float $price;

    public function __construct(string $coinPair, float $price)
    {
        $this->queue = 'stop-limit';
        $this->connection = 'redis-long-running';
        $this->coinPair = $coinPair;
        $this->price = $price;
    }

    public function handle()
    {
        $stopLimitOrders = Order::where('category', ORDER_CATEGORY_STOP_LIMIT)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('type', ORDER_TYPE_SELL)
                        ->where('stop_limit', '>=', $this->price);
                })->orWhere(function ($q) {
                    $q->where('type', ORDER_TYPE_BUY)
                        ->where('stop_limit', '<=', $this->price);
                });
            })
            ->where('trade_pair', $this->coinPair)
            ->where('status', STATUS_INACTIVE);

        foreach ($stopLimitOrders->cursor() as $stopLimitOrder) {
            $stopLimitOrder->status = STATUS_PENDING;
            $stopLimitOrder->save();
            OrderBroadcast::broadcast($stopLimitOrder);
        }
    }
}
