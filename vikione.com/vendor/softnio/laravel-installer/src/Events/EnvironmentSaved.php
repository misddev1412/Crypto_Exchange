<?php

namespace Softnio\LaravelInstaller\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class EnvironmentSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $request;

    /**
     * Create a new event instance.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
