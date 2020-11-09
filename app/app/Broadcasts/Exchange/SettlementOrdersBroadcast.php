<?php

namespace App\Broadcasts\Exchange;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettlementOrdersBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tradePair;
    public $payload;

    public function __construct($tradePair, $payload)
    {
        $this->tradePair = $tradePair;
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return new Channel(get_channel_prefix() . 'order.' . $this->tradePair);
    }

    public function broadcastAs()
    {
        return 'order.settlement';
    }

    public function broadcastWith()
    {
        return $this->payload;
    }
}
