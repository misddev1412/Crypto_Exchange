<?php
/**
 * EMail Service Provider
 *
 * Apply our email settings to config
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Providers;

use App\Helpers\CustomTransportManager;
use Illuminate\Mail\MailServiceProvider;

class EMailServiceProvider extends MailServiceProvider
{
    protected function registerSwiftTransport()
    {
        $this->app->singleton('swift.transport', function ($app) {
            return new CustomTransportManager($app);
        });
    }
}
