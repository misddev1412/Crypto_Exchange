<?php

namespace App\Jobs\Withdrawal;

use App\Models\Withdrawal\WalletWithdrawal;
use App\Override\Logger;
use App\Services\Withdrawal\WithdrawalService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WithdrawalProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $withdrawal;
    public $timeout = 120;
    public $deleteWhenMissingModels = true;


    public function __construct(WalletWithdrawal $withdrawal)
    {
        $this->queue = 'withdrawal';
        $this->withdrawal = $withdrawal->withoutRelations();
    }

    public function handle()
    {
        //The process will not continue if status is not pending
        if ($this->withdrawal->status !== STATUS_PENDING) {
            return;
        }

        $withdrawalService = app(WithdrawalService::class, [$this->withdrawal]);

        if (!$withdrawalService->withdraw()) {
            $withdrawalService->cancel();
        }
    }

    public function failed(Exception $exception)
    {
        Logger::error($exception, "[FAILED][WithdrawalProcessJob]");
    }

}
