<?php

namespace App\Jobs\Withdrawal;

use App\Override\Logger;
use App\Services\Withdrawal\WithdrawalService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class WithdrawalConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $withdrawal;
    private $payload;


    public function __construct($withdrawal, $payload)
    {
        $this->withdrawal = $withdrawal->withoutRelations();
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            if ($this->payload['status'] === STATUS_COMPLETED) {
                $this->withdrawal->update([
                    'status' => $this->payload['status'],
                    'txn_id' => $this->payload['txn_id']
                ]);
            } else if ($this->payload['status'] === STATUS_FAILED) {
                app(WithdrawalService::class, [$this->withdrawal])->cancel();
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][WithdrawalConfirmationJob]");
            DB::rollBack();
        }
        DB::commit();
    }
}
