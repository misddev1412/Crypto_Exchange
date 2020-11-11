<?php
namespace App\Notifications;

/**
 * ResetPassword
 *
 * Send Email to user if admin reseted his/her password.
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetByAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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

        $et = EmailTemplate::get_template('users-reset-password-email');
        $subject = $et->subject != '' ? replace_shortcode($et->subject) : 'Password Reset on ' . $from_name;

        $greeting = $et->greeting != '' ? $et->greeting : 'Hey, ' . $data->name;
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : null;

        $message = $et->message != '' ? str_replace("\n", "<br>", replace_shortcode($et->message)) : '';

        return (new MailMessage)
            ->greeting('Hi, ' . $this->data->name)
            ->line($message)
            ->line('Your New Password is : <strong>' . $this->data->new_password . '<strong>')
            ->action('Click to Login', url('/login'))
            ->from($from_email, $from_name)
            ->subject($subject)
            ->markdown('mail.reset_password', ['message' => $message, 'data' => $this->data]);
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
