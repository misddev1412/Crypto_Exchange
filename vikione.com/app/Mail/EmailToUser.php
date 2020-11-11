<?php
/**
 * EmailToUser
 *
 * Send Email to specific user by Laravel Mail
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailToUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function build()
    {
        $from_name = email_setting('from_name', get_setting('site_name'));
        $from_email = email_setting('from_email', get_setting('site_email'));

        $et = EmailTemplate::get_template('send-user-email');
        $subject = $this->data->subject != '' ? $this->data->subject : $et->subject;
        $subject = replace_with($subject, '[[user_name]]', $this->data->user->name);
        $subject = $subject != '' ? replace_shortcode($subject) : 'Email From ' . $from_name;
        $greeting = $this->data->greeting != '' ? $this->data->greeting : $et->greeting;
        $greeting = replace_with($greeting, '[[user_name]]', "<strong>" . $this->data->user->name . "</strong>");
        $et->regards = ($et->regards == 'true' ? get_setting('site_mail_footer') : null);
        $regards = $et->regards != '' ? replace_shortcode($et->regards) : 'Best Regards, <br>' . $from_name;

        return $this->from($from_email, $from_name)
            ->subject($subject)
            ->markdown('mail.to_user', ['user' => $this->data->user, 'message' => $this->data, 'template' => $et, 'greeting' => $greeting, 'salutation' => $regards]);
    }
}
