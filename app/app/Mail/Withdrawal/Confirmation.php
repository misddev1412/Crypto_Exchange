<?php

namespace App\Mail\Withdrawal;

use App\Models\Withdrawal\WalletWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Confirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var WalletWithdrawal
     */
    public $withdrawal;

    /**
     * Create a new message instance.
     *
     * @param WalletWithdrawal $withdrawal
     */
    public function __construct(WalletWithdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.withdrawal.confirmation')
            ->subject("Withdrawal Confirmation");
    }
}
