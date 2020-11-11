<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Refund extends Notification
{
    use Queueable;

    public $refund;
    public $transaction;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($refund, $transaction)
    {
        $this->refund = $refund;
        $this->transaction = $transaction;
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', get_setting('token_decimal_max', 8));
            ini_set('serialize_precision', -1);
        }
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
        $template = EmailTemplate::get_template('order-refund-token');
        $et = $this->replace_shortcode($template);
        
        return (new MailMessage)
            ->from($from_email, $from_name)
            ->subject($et->subject)
            ->greeting($et->greeting)
            ->salutation($et->regards)
            ->markdown('mail.base')->with(['__message'=>$et->message]);
    }

    public function replace_shortcode($template)
    {
        $template->subject = replace_shortcode($template->subject);
        $template->greeting = str_replace('[[user_name]]', $this->refund->tnxUser->name, $template->greeting);
        $template->regards = $template->regards == true ? replace_shortcode(get_setting('site_mail_footer')) : null;
        $message = replace_shortcode($template->message);
        $message = str_replace('[[refund_details]]', $this->refund_details(), $message);
        $message = str_replace('[[order_details]]', $this->order_details(), $message);
        $template->message = $this->replace_msg($message);

        return $template;
    }
    public function refund_details()
    {
        $table = '<table class="table order">';
        $table .= '<thead><th colspan="3">Refund details:</th></thead>';
        $table .= '<tbody class="text-left">';
        
        $table .= '<tr><td width="150">Refund ID</td><td width="15">:</td><td><strong>'.$this->refund->tnx_id.'</strong></td></tr>';
        $table .= '<tr><td>Refund Token</td><td>:</td><td><strong>'.$this->refund->total_tokens.' '.token_symbol().'</strong></td></tr>';
        $table .= '<tr><td>Refund Amount</td><td>:</td><td><strong>'.$this->refund->amount.' '.strtoupper($this->refund->currency).'</strong></td></tr>';
        // $table .= '<tr><td>Refund Against</td><td>:</td><td>#'.$this->transaction->tnx_id.'</td></tr>';
        if(!empty($this->refund->extra)){
            $table .= '<tr><td>Refund Note</td><td>:</td><td>[[refund_note]]</td></tr>';
        }


        $table .= '</tbody></table>';
        return $table;
    }
    public function order_details()
    {
        $if_bonus = ($this->transaction->total_bonus > 0) ? ' (included bonus '.$this->transaction->total_bonus.' '.token_symbol().')' : '';

        $table = '<table class="table order">';
        $table .= '<thead><th colspan="3">Refund against order ('.$this->transaction->tnx_id.'):</th></thead>';
        $table .= '<tbody class="text-left">';
        
        $table .= '<tr><td width="150">Total Token</td><td width="15">:</td><td><strong>'.$this->transaction->total_tokens.' '.token_symbol().'</strong>'.$if_bonus.'</td></tr>';
        $table .= '<tr><td>Pay Amount</td><td>:</td><td><strong>'.$this->transaction->amount.' '.strtoupper($this->transaction->currency).'</strong></td></tr>';
        $table .= '<tr><td>Payment Method</td><td>:</td><td>'.ucfirst($this->transaction->payment_method).'</td></tr>';
        $table .= '<tr><td>Purchase at</td><td>:</td><td>'._date($this->transaction->tnx_time).'</td></tr>';

        $table .= '</tbody></table>';
        return $table;
    }
    public function replace_msg($message)
    {
        $extra = json_decode($this->refund->extra);
        $note = isset($extra->message) ? $extra->message : $this->refund->details;
        return str_replace(
            ['[[refund_note]]'],
            [$note],
            $message
        );
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
