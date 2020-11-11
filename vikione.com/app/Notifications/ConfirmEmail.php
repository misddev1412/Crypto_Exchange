<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmail extends Notification
{
    use Queueable;

    // Set Data
    public $user;
    public $extra;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $extra=null)
    {
        $this->user = $user;
        $this->extra = $extra;
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
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function toMail($notifiable)
    {
        $from_name = email_setting('from_name', get_setting('site_name'));
        $from_email = email_setting('from_email', get_setting('site_email'));

        $support = get_setting('site_support_email') != '' ? get_setting('site_support_email') : $from_email;

        $et = EmailTemplate::get_template('users-confirm-password-email');
        $subject = $et->subject != '' ? replace_shortcode($et->subject) : 'Confirm Email on '.$from_name;
        $greeting = $et->greeting != '' ? $et->greeting : 'Hey, '.$user->name;
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : null;

        $et->message = replace_with($et->message, '[[user_name]]', "<strong>".$this->user->name."</strong>");
        $message = ($et->message != '') ? str_replace("\n", "<br>",replace_shortcode($et->message)) : $et->message;

        if ($this->user->meta->email_token == NULL) {
            $this->user->meta->email_token = str_random(65);
            $this->user->meta->email_expire = now()->addMinutes(75);
            $this->user->meta->save();
        }
        $extra = (isset($this->extra->password) ? 'Your Password is : **'. $this->extra->password . '**' : '---');
        return (new MailMessage)
            ->greeting(replace_shortcode(replace_with($greeting, '[[user_name]]', $this->user->name)))
            ->line(replace_shortcode(replace_with($message, '[[user_name]]', "<strong>".$this->user->name."</strong>")))
            ->action('Confirm Email Address', route('verify.email', ['id'=>$this->user->id, 'token'=>$this->user->meta->email_token]))
            ->line($extra)
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
