<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordChange extends Notification
{
    use Queueable;

   
    public $data;
   
    public $userMeta;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data, $userMeta)
    {
        $this->data = $data;
        $this->userMeta = $userMeta;
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

        $et = EmailTemplate::get_template('users-change-password-email');
        $subject = $et->subject != '' ? replace_shortcode($et->subject) : 'Confirm Password on '.$from_name;
        $greeting = $et->greeting != '' ? $et->greeting : 'Hey, '.$this->data->name;
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : null;

        if ($this->data->role == 'admin') {
            $action = 'admin.password.confirm';
        } elseif ($this->data->role == 'user') {
            $action = 'user.password.confirm';
        }

        $et->message = replace_with($et->message, '[[user_name]]', "<strong>".$this->data->name."</strong>");
        $message = ($et->message != '') ? str_replace("\n", "<br>", replace_shortcode($et->message)) : $et->message;
        $expire = Carbon::parse($this->userMeta->email_expire);
        $now = Carbon::now()->toDateTimeString();
        

        return (new MailMessage)
            ->greeting(replace_shortcode(replace_with($greeting, '[[user_name]]', $this->data->name)))
            ->line(replace_shortcode(replace_with($message, '[[user_name]]', "<strong>".$this->data->name."</strong>")))
            ->action('Confirm password change', route($action, $this->userMeta->email_token))
            ->line("This password reset link will expire in ".$expire->diffInMinutes($now)." minutes\n")
            ->line("If you did not request a password reset, no further action is required.")
            ->from($from_email, $from_name)
            ->subject($subject)
            ->markdown('mail.base', ['message'=>replace_with($message, '[[user_name]]', "<strong>".$this->data->name."</strong>"), 'user' => $this->data]);
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
