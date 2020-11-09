<?php

namespace App\Broadcasts\Exchange;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExchangeBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payloadData;
    public $tradePair;


    public function __construct($tradePair, $payloadData)
    {
        $this->tradePair = $tradePair;
        $this->payloadData = $payloadData;
    }

    public function broadcastOn()
    {
        return new Channel(get_channel_prefix() . 'order.' . $this->tradePair);
    }

    public function broadcastAs()
    {
        return 'order.exchanged';
    }

    public function broadcastWith()
    {
        return $this->payloadData;
    }
}
