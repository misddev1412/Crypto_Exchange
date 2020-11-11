<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\EmailTemplate;
use Jenssegers\Agent\Agent;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UnusualLogin extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $from_name = email_setting('from_name', get_setting('site_name'));
        $from_email = email_setting('from_email', get_setting('site_email'));

        $support = get_setting('site_support_email') != '' ? get_setting('site_support_email') : $from_email;

        $et = EmailTemplate::get_template('users-unusual-login-email');
        $subject = $et->subject != '' ? replace_shortcode($et->subject) : 'Unusual Login Attempt on '.$from_name;
        $greeting = $et->greeting != '' ? $et->greeting : 'Hey, '.$this->user->name;
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : null;

        $et->message = replace_with($et->message, '[[user_name]]', "<strong>".$this->user->name."</strong>");
        $et->message = replace_with($et->message, '[[site_name]]', "<strong>".$from_name."</strong>");
        $message = ($et->message != '') ? str_replace("\n", "<br>", replace_shortcode($et->message)) : $et->message;
        $agent = new Agent();

        return (new MailMessage)
            ->greeting(replace_shortcode(replace_with($greeting, '[[user_name]]', $this->user->name)))
            ->line($message)
            ->line("### Device information")
            ->line("Device name: ".$agent->device().'/'.$agent->platform().'-'.$agent->version($agent->platform()))
            ->line("<br>browser : ".$agent->browser().'/'.$agent->version($agent->browser()))
            ->line("<br>IP : ".request()->ip()."<br><br>")
            ->line("<br>If it was not done by you then please login and reset your password!!")
            ->action('Login', route('login'))
            ->from($from_email, $from_name)
            ->subject($subject)
            ->markdown('mail.base', ['message'=>replace_with($message, '[[user_name]]', "<strong>".$this->user->name."</strong>"), 'user' => $this->user]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
