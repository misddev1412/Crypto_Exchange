<?php

namespace App\Jobs\Withdrawal;

use App\Models\Withdrawal\WalletWithdrawal;
use App\Services\Withdrawal\WithdrawalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WithdrawalCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $withdrawal;
    public $deleteWhenMissingModels = true;

    public function __construct(WalletWithdrawal $withdrawal)
    {
        $this->queue = 'withdrawal-cancel';
        $this->withdrawal = $withdrawal->withoutRelations();
    }

    public function handle()
    {
        if ( !in_array($this->withdrawal->status, [STATUS_CANCELING, STATUS_PENDING]) ) {
            return false;
        }

        app(WithdrawalService::class, [$this->withdrawal])->cancel();
    }
}
