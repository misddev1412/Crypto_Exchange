<?php
/**
 * CustomTransportManager
 *
 * This class used for send email with our credentials from database.
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Helpers;

use Illuminate\Mail\TransportManager;

class CustomTransportManager extends TransportManager
{

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @version 1.0.0
     * @since 1.0
     * @return array
     */
    public function __construct($app)
    {
        $this->app = $app;

        $this->app['config']['mail'] = [
            'driver' => email_setting("driver", env('MAIL_DRIVER', 'sendmail')),
            'host' => email_setting("host", env('MAIL_HOST', 'smtp.mailgun.org')),
            'port' => email_setting("port", env('MAIL_PORT', 587)),
            'from' => [
                'address' => email_setting("from_address", env('MAIL_FROM_ADDRESS', 'noreply@yourdomain.com')),
                'name' => email_setting("from_name", env('MAIL_FROM_NAME', 'TokenLite')),
            ],
            'encryption' => email_setting("encryption", env('MAIL_ENCRYPTION', 'tls')),
            'username' => email_setting("user_name", env('MAIL_USERNAME')),
            'password' => email_setting("password", env('MAIL_PASSWORD')),

            'sendmail' => '/usr/sbin/sendmail -bs',

            'markdown' => [
                'theme' => 'nio-mail',
                'paths' => [
                    resource_path('views/vendor/mail'),
                ],
            ],
        ];
    }
}
