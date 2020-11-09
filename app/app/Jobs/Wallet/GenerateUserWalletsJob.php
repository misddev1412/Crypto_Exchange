<?php

namespace App\Jobs\Wallet;

use App\Models\Coin\Coin;
use App\Models\Core\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateUserWalletsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->queue = 'default_long';
        $this->connection = 'redis-long-running';
        $this->user = $user;
    }

    public function handle(): void
    {
        $coins = Coin::all();

        $walletInstances = [];
        foreach ($coins as $coin) {
            if ($this->user->is_super_admin == ACTIVE) {
                $walletInstances[] = [
                    'symbol' => $coin->symbol,
                    'is_system_wallet' => ACTIVE,
                ];
            }

            $walletInstances[] = [
                'symbol' => $coin->symbol,
                'is_system_wallet' => INACTIVE,
            ];
        }

        if (!empty($walletInstances)) {
            $this->user->wallets()->createMany($walletInstances);
        }
    }
}
