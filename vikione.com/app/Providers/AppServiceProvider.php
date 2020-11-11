<?php

namespace App\Providers;

use Config;
use Cookie;
use IcoData;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(is_https_active()){
            URL::forceScheme('https');
        }
        
        Schema::defaultStringLength(191);

        if ( application_installed(true)) {

            $check_dt = \IcoHandler::checkDB();
            if(empty($check_dt) || count($check_dt) < 14){           
                $settings = Setting::all();
                foreach ($settings as $setting) {
                    if (starts_with($setting->field, 'site_')) {
                        Config::set('settings.'.$setting->field, $setting->value);
                    }
                }
                config([
                    'app.name' => get_setting('site_name', env('APP_NAME', 'TokenLite')),
                    'app.installed' => get_setting('env_ptype', 0),
                    // in case overwrite values inside config/services.php
                    'services.facebook.client_id'     => get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')),
                    'services.facebook.client_secret' => get_setting('site_api_fb_secret', env('FB_CLIENT_SECRET', '')),

                    'services.google.client_id'     => get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')),
                    'services.google.client_secret' => get_setting('site_api_google_secret', env('GOOGLE_CLIENT_SECRET', '')),

                    'app.timezone' => get_setting('site_timezone', 'UTC'),
                    'icoapp.supported_languages' => get_lang(),
                    'icoapp.show_languages_switcher' => (get_setting('languages_switcher', 0)) ? true : false,
                ]); 
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        $lang  = 'en';

        if (Cookie::get('app_language') !== null){
            try{
                $lang = \Crypt::decryptString(Cookie::get('app_language', 'en'));
            }catch(\Exception $e){
                //nohting
            }

        }

        Config::set('app.locale', $lang);
        $this->registerModuleServiceProviders();     
    }

    public function registerModuleServiceProviders()
    {
        $modules = nio_module()->getModules() ?? [];
        foreach ($modules as $module) {
            if( is_dir(app_path('NioModules/'.$module)) ){
                $moduleNS = nio_module()->getNamespace($module);
                $moduleClass = $moduleNS."{$module}ServiceProvider";
                if(class_exists($moduleClass)){
                    $this->app->register($moduleClass);
                }
            }
        }
    }
}
