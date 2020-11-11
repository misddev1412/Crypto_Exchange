<?php
/**
 * EmailTemplate Model
 *
 * Used for Email template
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use IcoData;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'email_templates';

    /*
     * All Templates Name
     */
    protected static $names = [
        'welcome-email', 'send-user-email', 'users-confirm-password-email', 'users-change-password-email', 'users-unusual-login-email', 'users-reset-password-email', 'kyc-approved-email', 'kyc-rejected-email', 'kyc-missing-email', 'kyc-submit-email', 'order-submit-user', 'order-successful-user', 'order-rejected-user', 'order-placed-admin', 'order-canceled-admin',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'subject', 'message', 'greeting', 'regards',
    ];

    /**
     * Get the email template
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function get_template($name)
    {
        $template = self::where('slug', $name)->orWhere('id', $name)->first();
        if (!$template) {
            $template = IcoData::default_email_template($name);
            if(!$template) {
                $template = (object) [
                    'name' => str_replace('-', ' ', $name),
                    'slug' => $name,
                    'subject' => "Email From ".site_info(),
                    'greeting' => "Hello",
                    'message' => "",
                    'regards' => true,
                ];
            }
        }

        return $template;
    }
}
