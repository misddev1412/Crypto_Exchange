<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailTemplate;

class VerifyTransaction extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
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
        $et = EmailTemplate::get_template('verify-code-transaction');
        $subject = $et->subject != '' ? replace_shortcode($et->subject) : 'Confirm Email on ' . $from_name;
        $greeting = $et->greeting != '' ? $et->greeting : 'Hey, ' . $user->name;
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : null;

        $et->message = replace_with($et->message, '[[user_name]]', "<strong>" . $this->user->name . "</strong>");
        $message = ($et->message != '') ? str_replace("\n", "<br>", replace_shortcode($et->message)) : $et->message;

        return (new MailMessage)
            ->greeting(replace_shortcode(replace_with($greeting, '[[user_name]]', $this->user->name)))
            ->line(replace_shortcode(replace_with($message, '[[code]]', "<strong>". $this->code ."</strong><br/>")))
            ->line('Thank you for using our application!')
            ->from($from_email, $from_name)
            ->subject($subject)
            ->markdown('mail.base', ['message' => replace_with($message, '[[user_name]]', "<strong>" . $this->user->name . "</strong>"), 'user' => $this->user]);
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
