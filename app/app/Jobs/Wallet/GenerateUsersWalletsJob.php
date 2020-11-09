<?php

namespace App\Jobs\Wallet;

use App\Models\Coin\Coin;
use App\Models\Wallet\Wallet;
use App\Models\Core\User;
use App\Override\Logger;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GenerateUsersWalletsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $coin;

    public function __construct(Coin $coin)
    {
        $this->queue = 'default_long';
        $this->connection = 'redis-long-running';
        $this->coin = $coin;
    }

    public function handle()
    {
        // Retrieve super admin user
        $admin = User::superAdmin()->first();

        if (empty($admin)) {
            return;
        }

        //Retrieve system wallet
        $systemWallet = Wallet::where('user_id', $admin->id)
            ->where('symbol', $this->coin->symbol)
            ->where('is_system_wallet', ACTIVE)
            ->first();

        if (empty($systemWallet)) {
            Wallet::create([
                'user_id' => $admin->id,
                'symbol' => $this->coin->symbol,
                'is_system_wallet' => ACTIVE,
            ]);

            $attribute = [];
            foreach (User::cursor() as $user) {
                $attribute[] = [
                    'id' => Str::uuid()->toString(),
                    'user_id' => $user->id,
                    'symbol' => $this->coin->symbol,
                    'is_system_wallet' => INACTIVE,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ];
            }

            foreach (array_chunk($attribute, 1000) as $item) {
                Wallet::insert($item);
            }
        }
    }

    public function failed(Exception $exception)
    {
        Logger::error($exception, "[FAILED][GenerateUsersWalletsJob]");
    }
}
