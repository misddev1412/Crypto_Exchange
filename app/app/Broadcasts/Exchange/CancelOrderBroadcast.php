<?php

namespace App\Broadcasts\Exchange;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CancelOrderBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $previousStatus;

    public function __construct($order, $previousStatus)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
    }

    public function broadcastOn()
    {
        return new Channel(get_channel_prefix() . 'order.' . $this->order->trade_pair);
    }

    public function broadcastWhen()
    {
        return $this->order->category !== ORDER_CATEGORY_MARKET;
    }

    public function broadcastAs()
    {
        return 'order.canceled';
    }

    public function broadcastWith()
    {
        return [
            'category' => $this->order->category,
            'type' => $this->order->type,
            'previous_status' => $this->previousStatus,
            'price' => $this->order->price,
            'amount' => $this->order->canceled,
            'total' => bcmul($this->order->price, $this->order->canceled),
            'trade_pair' => $this->order->trade_pair,
        ];
    }
}
