<?php

namespace App\Notifications;

use App\PayModule\Module;
use Illuminate\Bus\Queueable;
use App\Models\EmailTemplate as ET;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TnxStatus extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tnx_data = null;
    protected $template = null;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tnx_data, $template)
    {
        $this->tnx_data = $tnx_data;
        $this->template = $template;
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

        $template = ET::get_template('order-'.$this->template);
        $transaction = $this->tnx_data;
        $user = $this->tnx_data->tnxUser;

        $template->message = $this->replace_shortcode($template->message);
        $template->regards = ($template->regards == 'true' ? get_setting('site_mail_footer', "Best Regards, \n[[site_name]]") : '');
        
        return (new MailMessage)
                    ->greeting($this->replace_shortcode($template->greeting))
                    ->salutation($this->replace_shortcode($template->regards))
                    ->from($from_email, $from_name)
                    ->subject($this->replace_shortcode($template->subject))
                    ->markdown('mail.transaction', compact('template', 'transaction', 'user'));
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

    /**
     * Get the short-code and replace with data.
     *
     * @param  mixed  $code
     * @return void
     */
    public function replace_shortcode($code)
    {
        $shortcode =array(
            "\n",
            '[[token_name]]',
            '[[token_symbol]]',
            '[[site_name]]',
            '[[site_email]]',
            '[[site_url]]',

            '[[order_details]]',
            '[[order_id]]',
            '[[support_email]]',
            '[[site_login]]',
            '[[user_name]]',
            '[[user_email]]',
            '[[payment_amount]]',
            '[[payment_from]]',
            '[[payment_gateway]]',
            '[[total_tokens]]',
        );
        $replace = array(
            "<br>",
            token('name'),
            token('symbol'),
            site_info('name', false),
            site_info('email', false),
            url('/'),

            $this->get_blade('order_details', $this->tnx_data),
            $this->tnx_data->tnx_id,
            get_setting('site_support_email'),
            $this->get_blade('button', ['url'=>url('/login'), 'title'=>'Login Here']),
            $this->tnx_data->tnxUser->name,
            $this->tnx_data->tnxUser->email,
            $this->tnx_data->amount.' '.strtoupper($this->tnx_data->currency),
            ( $this->tnx_data->payment_method == 'bank' ? '(as mentioned above)' : $this->tnx_data->payment_to),
            ucfirst($this->tnx_data->payment_method),
            $this->tnx_data->total_tokens.' '.token_symbol(),
        );
        $return = str_replace($shortcode, $replace, $code);
        return $return;
    }

    public function get_blade($name='', $data='')
    {
        $blade = '';
        if ($name == 'button' && $data != null) {
            $blade = '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center"><table border="0" cellpadding="0" cellspacing="0"><tr><td><a href="'.(isset($data['url']) ? $data['url'] : url('/')).'" class="button button-green" target="_blank">'.(isset($data['title']) ? $data['title'] : site_info('name')).'</a></td></tr></table></td></tr></table>';
        }

        $currency = strtolower($this->tnx_data->currency);
        if ($name == 'order_details') {
            $pay_address = (new Module())->email_data($this->tnx_data);
            $blade = '<table class="table order"><thead><th colspan="3">Order details are follows:</th></thead><tbody class="text-left"><tr><td width="150">Order ID</td><td width="15">:</td><td><strong>#'.$this->tnx_data->tnx_id.'</strong></td></tr><tr><td>ICO Stage</td><td>:</td><td><strong>'.$this->tnx_data->ico_stage->name.'</strong></td></tr><tr><td>Token Number</td><td>:</td><td><strong>'.$this->tnx_data->tokens.' '.token_symbol().'</strong></td></tr><tr><td>Bonus </td><td>:</td><td><strong>'.$this->tnx_data->total_bonus.' '.token_symbol().'</strong> </td></tr><tr><td>Total Token</td><td>:</td><td><strong>'.$this->tnx_data->total_tokens.' '.token_symbol().'</strong> </td></tr><tr><td>Payment Amount</td><td>:</td><td><strong>'.$this->tnx_data->amount.' '.strtoupper($this->tnx_data->currency).'</strong></td></tr><tr><td>Payment Status</td><td>:</td><td><strong>'.ucfirst($this->tnx_data->status).'</strong></td></tr><tr><td>Payment Method</td><td>:</td><td><strong>'.ucfirst($this->tnx_data->payment_method).'</strong></td></tr>'.((!str_contains($this->template, 'admin') && ($this->tnx_data->status == 'pending' || $this->tnx_data->status == 'onhold')) ? $pay_address : '').'</tbody></table>';
        }
        return $blade;
    }
}
