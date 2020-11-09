<?php

namespace App\Mail\Core;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $profile;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param $profile
     * @param $password
     */
    public function __construct($profile, $password)
    {
        $this->profile = $profile;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.user.account_created')->subject(__('Your account has been created on :company', ['company' => config('app.name')]));
    }
}
