<?php
/**
 * KYC Status
 *
 * Send Email to user about his/her KYC Application status
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

class KycStatus extends Notification implements ShouldQueue
{
    use Queueable;

    public $kuser;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($kuser)
    {
        $this->kuser = $kuser;
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

        $status = isset($this->kuser->status) ? $this->kuser->status : 'pending';
        $status = ($status == 'pending') ? 'submitted' : $status;
        $sub = str_replace(['pending'], ['Submitted'], $status);
        $et_status = str_replace(['submitted', 'pending'], ['submit', 'submit'], $status);
        $name = $this->kuser->firstName . ' ' . $this->kuser->lastName;
        $notes = (($this->kuser->status == 'missing' || $this->kuser->status == 'rejected') && $this->kuser->notes != null) ? $this->kuser->notes : 'Wrong document/information';
        $et = EmailTemplate::get_template('kyc-' . $et_status . '-email');
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $subject = $et->subject != '' ? replace_shortcode(replace_with($et->subject, '[[status]]', $status)) : 'KYC Application: ' . ucfirst($status);
        $greeting = $et->greeting != '' ? replace_shortcode(replace_with($et->greeting, '[[user_name]]', $name)) : 'Hi ' . $name . ',';
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : null;
        $et->message = $et->message != '' ? replace_shortcode($et->message) : 'Thank you for submitting your verification request. We will review your information and get back to you as soon as possible.';
        $et->message = ($et_status == 'missing' || $et_status == 'rejected') ? replace_with($et->message, '[[message]]', "<strong>" . $notes . "</strong>") : $et->message;
        $et->name = $name;

        return (new MailMessage)
            ->greeting(replace_with($greeting, '[[user_name]]', "<strong>" . $name . "</strong>"))
            ->salutation($regards)
            ->from($from_email, $from_name)
            ->subject(replace_with($subject, '[[user_name]]', $name))
            ->markdown('mail.kyc.' . $status, ['user' => $this->kuser, 'status' => $status, 'template' => $et]);
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
