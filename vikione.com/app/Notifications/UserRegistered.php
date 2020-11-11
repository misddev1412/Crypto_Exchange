<?php
/**
 * KYC Status
 *
 * Send Welcome Email to user when he/she confirm his/her email
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification implements ShouldQueue
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
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function toMail($notifiable)
    {
        $from_name = email_setting('from_name', get_setting('site_name'));
        $from_email = email_setting('from_email', get_setting('site_email'));

        $support = get_setting('site_support_email') != '' ? get_setting('site_support_email') : $from_email;

        $et = EmailTemplate::get_template('welcome-email');

        $et->subject = replace_with($et->subject, '[[user_name]]', $this->user->name);
        $et->greeting = replace_with($et->greeting, '[[user_name]]', "<strong>" . $this->user->name . "</strong>");
        $et->message = replace_with($et->message, '[[user_name]]', "<strong>" . $this->user->name . "</strong>");

        $subject = $et->subject != '' ? replace_shortcode($et->subject) : 'Welcome to ' . $from_name;
        $greeting = $et->greeting != '' ? replace_shortcode($et->greeting) : 'Hi ' . $this->user->name . ",";
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : null;
        $msg = $et->message != '' ? replace_shortcode($et->message) : 'Welcome to ' . $from_name;

        return (new MailMessage)
            ->greeting(replace_with($greeting, '[[user_name]]', $this->user->name))
            ->line($msg)
            ->action('Login into Account', url('/login'))
            ->salutation($regards)
            ->from($from_email, $from_name)
            ->subject($subject)
            ->markdown('mail.welcome', ['user' => $this->user, 'template' => $et]);
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
