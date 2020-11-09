<?php

namespace App\Jobs\Order;

use App\Models\Order\Order;
use App\Services\Orders\ProcessLimitOrderService;
use App\Services\Orders\ProcessMarketOrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    public function __construct(Order $order)
    {
        $this->queue = 'exchange';
        $this->order = $order->withoutRelations();
    }

    public function handle()
    {
        //If the order status is not equal to pending then the process stop.
        if ($this->order->status != STATUS_PENDING) {
            return;
        }

        if ($this->order->category === ORDER_CATEGORY_MARKET) {
            app(ProcessMarketOrderService::class, [$this->order])->process();
        } else {
            app(ProcessLimitOrderService::class, [$this->order])->process();
        }
    }
}
