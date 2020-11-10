<?php

namespace Database\Seeders;

use App\Models\Core\ApplicationSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class ApplicationSettingsTableSeeder extends Seeder
{
    public function run()
    {
        $date_time = date('Y-m-d H:i:s');
        $adminSettingArray = [
            'lang' => 'en',
            'lang_switcher' => ACTIVE,
            'lang_switcher_item' => 'short_code',
            'registration_active_status' => STATUS_ACTIVE,
            'default_role_to_register' => 'user',
            'require_email_verification' => ACTIVE,
            'company_name' => 'Trademen',
            'company_logo' => 'logo.png',
            'company_logo_light' => 'logo-light.png',
            'navigation_type' => 2,
            'side_nav_fixed' => 0,
            'no_header_layout' => 0,
            'favicon' => 'favicon.png',
            'maintenance_mode' => 0,
            'admin_receive_email' => 'youremail@gmail.com',
            'display_google_captcha' => INACTIVE,
            'exchange_maker_fee' => 0.1,
            'exchange_taker_fee' => 0.2,
            'is_admin_approval_required' => 0,
            'referral' => ACTIVE,
            'referral_percentage' => 2,
            'trading_price_tolerance' => 10,
            'footer_menu_title_1' => 'About Trademen',
            'footer_menu_1' => 'footer-nav-one',
            'footer_menu_title_2' => 'Products',
            'footer_menu_2' => 'footer-nav-two',
            'footer_menu_title_3' => 'Social',
            'footer_menu_3' => 'footer-nav-three',
            'footer_phone_number' => '+8801772473616',
            'footer_address' => 'Khulna, Bangladesh.',
            'footer_email' => 'codemenorg@gmail.com',
            'dashboard_coin_1' => 'BTC',
            'dashboard_coin_2' => 'USD',
            'dashboard_coin_3' => 'BTC',
            'dashboard_coin_4' => 'USD',
            'dashboard_coin_pair' => 'BTC_USD',
        ];

        $adminSetting = [];
        foreach ($adminSettingArray as $key => $value) {
            $adminSetting[] = [
                'slug' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
                'created_at' => $date_time,
                'updated_at' => $date_time
            ];
        }
        ApplicationSetting::insert($adminSetting);

        Cache::forever("appSettings", $adminSettingArray);
    }
}
