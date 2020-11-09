<?php

namespace App\Jobs\Deposit;

use App\Services\Deposit\DepositService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DepositProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $depositData;

    public function __construct(array $depositData)
    {
        $this->depositData = $depositData;
    }

    public function handle(DepositService $depositService)
    {
        $depositService->deposit($this->depositData);
    }
}
