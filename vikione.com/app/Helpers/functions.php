<?php
use Carbon\Carbon;
use App\Models\KYC;
use App\Models\User;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Referral;
use App\Models\IcoStage;
use App\Models\IcoMeta;
use App\Models\GlobalMeta;
use App\Models\Language;
use App\Models\Transaction;
use App\Helpers\IcoHandler;
use App\Models\EmailTemplate;
use App\Models\PaymentMethod;
use App\Helpers\TokenCalculate;
use App\Notifications\TnxStatus;
use Illuminate\Support\HtmlString;
use App\Helpers\AddressValidation;
use Illuminate\Support\Facades\Notification;

/**
 * Custom Helper Functions
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.5
 * @since 1.0
 * @return void
 */

/* @function application_installed()  @version v1.0  @since 1.0 */
if (!function_exists('application_installed')) {
    function application_installed($full_check = false)
    {
        if(file_exists(storage_path('installed'))){
            if($full_check === true){
                try {
                    \DB::connection()->getPdo();
                    return  true;
                } catch (\Exception $e) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}

/* @function get_site()  @version v1.0  @since 1.1 */
if (!function_exists('get_site')) {
    function get_site()
    {
        $host = str_replace('www.', '', request()->getHost());
        $path = str_replace('/index.php', '', request()->getScriptName());
        if($path == "") {
            $path = "/";
        }
        return $host.$path;
    }
}

/* @function is_who()  @version v1.0  @since 1.0.2 */
if (!function_exists('is_who')) {
    function is_who($role=null)
    {
        $user = (auth()->check()) ? auth()->user() : false;
        $user_role = ($user) ? $user->role : false;

        $return = $user_role;

        if (!empty($role)) {
            $return = ($role == $user_role) ? true : false;
        }

        return $return;
    }
}

/* @function is_admin()  @version v1.0  @since 1.0.2 */
if (!function_exists('is_admin')) {
    function is_admin()
    {
        return is_who('admin');
    }
}

/* @function is_user()  @version v1.0  @since 1.0.2 */
if (!function_exists('is_user')) {
    function is_user()
    {
        return is_who('user');
    }
}

/* @function is_demo_user()  @version v1.0  @since 1.0.6 */
if (!function_exists('is_demo_user')) {
    function is_demo_user()
    {
        $user = (auth()->check()) ? auth()->user() : false;
        return (!empty($user) && $user->type=='demo') ? true : false;
    }
}

/* @function is_demo_preview()  @version v1.0  @since 1.1.0 */
if (!function_exists('is_demo_preview')) {
    function is_demo_preview()
    {
        return env('DEMO_ONLY', false);
    }
}

/* @function site_info()  @version v1.2  @since 1.0 */
if (!function_exists('site_info')) {
    function site_info($output = 'name')
    {
        $apps = config('app.name');
        $name = get_setting('site_name', $apps);
        $desc = get_setting('site_description');
        $email = get_setting('site_email');
        $author = get_setting('site_author', $apps);
        $api_key = get_setting('site_api_key');
        $app_url = config('app.url');
        $base_url = url('/');

        $infos = [
            'apps' => $apps,
            'author' => $author,
            'name' => $name,
            'desc' => $desc,
            'email' => $email,
            'apikey' => $api_key,
            'url' => $base_url,
            'url_only' => str_replace(['https://', 'http://'], '', $base_url),
            'url_app' => $app_url,
        ];

        $output = (empty($output)) ? 'name' : $output;
        $return = ( ($output=='all') ? $infos : ((isset($infos[$output])) ? $infos[$output] : '') );

        return $return;
    }
}

/* @function site_logo()  @version v1.1  @since 1.0 */
if (!function_exists('site_logo')) {
    function site_logo($type = '', $ver = 'dark', $echo = false)
    {
        if (empty($type)) {
            $type = 'default';
        }

        if ($type != 'default' && $type != 'retina' && $type != 'mail') {
            return false;
        }

        $default_dark = [
            'default' => 'images/logo.png',
            'retina' => 'images/logo2x.png',
            'mail' => 'images/logo-mail.png',
        ];

        $default_light = [
            'default' => 'images/logo-light.png',
            'retina' => 'images/logo-light2x.png',
            'mail' => 'images/logo-mail.png',
        ];

        $default = ($ver == 'light') ? $default_light : $default_dark;

        $output = asset($default[$type]);

        if ($echo === true) {
            echo $output;
        } else {
            return $output;
        }
    }
}


/* @function site_favicon()  @version v1.0  @since 1.0.2 */
if (!function_exists('site_favicon')) {
    function site_favicon()
    {
        $fav_icon = asset('favicon.ico');
        $fav_png1 = asset('favicon.png');
        $fav_png2 = asset('images/favicon.png');

        $favicon = (file_exists($fav_icon)) ? $fav_icon : ((file_exists($fav_png1)) ? $fav_png1 : $fav_png2);

        return $favicon;
    }
}

/* @function site_whitelabel()  @version v1.1  @since 1.0.2 */
if (!function_exists('site_whitelabel')) {
    function site_whitelabel($type=null)
    {
        $return     = false;
        $nio_cool   = nio_feature();
        $nio_apps   = app_info('corename');
        $nio_author = app_info('author');
        $nio_sname  = app_info('name').' Application';

        $apps       = site_info('apps');
        $author     = site_info('author');
        $sname      = site_info('name');

        $whitelabel_exten = [
            'admin' => false,
            'apps' => $nio_apps,
            'author' => (is_admin()) ? $nio_author : $author,
            'name' => (is_admin()) ? $nio_sname : $sname,
            'title' => (is_admin()) ? $nio_sname : $sname,
            'logo' => site_logo('default', 'dark'),
            'logo2x' => site_logo('retina', 'dark'),
            'logo-light' => site_logo('default', 'light'),
            'logo-light2x' => site_logo('retina', 'light'),
            'copyright' => site_copyrights(),
        ];

        $whitelabel_none = [
            'admin' => true,
            'apps' => $apps,
            'author' => $author,
            'name' => $sname,
            'title' => $sname,
            'logo' => site_logo('default', 'dark'),
            'logo2x' => site_logo('retina', 'dark'),
            'logo-light' => site_logo('default', 'light'),
            'logo-light2x' => site_logo('retina', 'light'),
            'copyright' => site_copyrights(),
        ];

        $whitelabel = ($nio_cool=='cool' && env_file(3, 2)) ? $whitelabel_none : $whitelabel_exten;

        $return = (isset($whitelabel[$type]) && $whitelabel[$type]) ? $whitelabel[$type] : '';
        
        return $return;
    }
}

/* @function base_currency()  @version v1.0  @since 1.0 */
if (!function_exists('base_currency')) {
    function base_currency($upper = false)
    {
        $return = (get_setting('site_base_currency')) ? strtolower(get_setting('site_base_currency')) : 'usd';
        if ($upper == true) {
            return strtoupper($return);
        }
        return $return;
    }
}

/* @function html_string()  @version v1.0  @since 1.0.2 */
if (!function_exists('html_string')) {
    function html_string($html_string)
    {
        return new HtmlString($html_string);
    }
}

/* @function def_datetime()  @version v1.0  @since 1.0 */
if (!function_exists('def_datetime')) {
    function def_datetime($get = '')
    {
        if (!$get) {
            return false;
        }

        $data = [
            'date' => '2000-01-01',
            'time_s' => '00:00:00',
            'time_e' => '23:59:00',
        ];
        $return = [
            'date' => $data['date'],
            'time' => $data['time_s'],
            'time_s' => $data['time_s'],
            'time_e' => $data['time_e'],
            'datetime' => $data['date'] . ' ' . $data['time_s'],
            'datetime_s' => $data['date'] . ' ' . $data['time_s'],
            'datetime_e' => $data['date'] . ' ' . $data['time_e'],
        ];
        return $return[$get];
    }
}

/* @function show_str()  @version v1.0  @since 1.0 */
if (!function_exists('show_str')) {
    function show_str($string, $length = 5)
    {
        return IcoHandler::string_compact($string, $length);
    }
}

/* @function has_wallet()  @version v1.0  @since 1.0 */
if (!function_exists('has_wallet')) {
    function has_wallet($get = false)
    {
        return IcoHandler::check_user_wallet($get);
    }
}

/* @function token_wallet()  @version v1.0  @since 1.1.1 */
if (!function_exists('token_wallet')) {
    function token_wallet()
    {
        $user = auth()->user();
        $wallets = array();
        $wallet = field_value_text('token_wallet_opt', 'wallet_opt');
        if($wallet) {
            foreach ($wallet as $wal) { 
                $wallets[$wal] = ucfirst($wal); 
            }
        }

        $custom = field_value_text('token_wallet_custom');
        if($custom['cw_name'] != '' && $custom['cw_text'] != ''){
            $wallets[$custom['cw_name']] = $custom['cw_text'];
        }

        return (!empty($wallets)) ? $wallets : false;
    }
}

/* @function manual_payment()  @version v1.0  @since 1.0 */
if (!function_exists('manual_payment')) {
    function manual_payment($type, $ext = '', $active = true)
    {
        return IcoHandler::get_manual_payment($type, $ext, $active);
    }
}

/* @function app_info()  @version v1.0  @since 1.0 */
if (!function_exists('app_info')) {
    function app_info($output = '')
    {
        return IcoHandler::panel_info($output);
    }
}

/* @function app_key()  @version v1.0  @since 1.1.0 */
if (!function_exists('app_key')) {
    function app_key($t='lkey', $true=false)
    {
        $return = ($t=='w'||$t=='2'||$t=='credible') ? gws('tokenlite_credible') : gws('nio_lkey');

        if($true==true||$true==1) {
            return (empty($return)) ? false : true;
        }
        return $return;
    }
}

/* @function env_file()  @version v1.0  @since 1.1.0 */
if (!function_exists('env_file')) {
    function env_file($et='code', $comp=null)
    {   
        $pcode = gws('env_pcode');
        $uname = gws('env_uname');
        $ptype = gws('env_ptype');

        $return = $pcode;
        if($et=='n'||$et=='2'||$et=='name') {
            $return = $uname;
        } elseif($et=='t'||$et=='3'||$et=='type') {
            $return = (!empty($ptype) ? substr($ptype, 0, 1) : false);
        } elseif($et=='ptype') {
            $return = (!empty($ptype) ? $ptype : false);
        }

        if(!empty($comp)) {
            return ($comp==$return) ? true : false;
        }
        return $return;
    }
}

/* @function css_class()  @version v1.0  @since 1.0 */
if (!function_exists('css_class')) {
    function css_class($str = '', $key = '', $args = array())
    {
        return IcoHandler::css_class_generate($str, $key, $args);
    }
}

/* @function token()  @version v1.0  @since 1.0 */
if (!function_exists('token')) {
    function token($params = '')
    {
        return IcoHandler::get_token_settings($params);
    }
}

/* @function token_symbol()  @version v1.0  @since 1.0 */
if (!function_exists('token_symbol')) {
    function token_symbol()
    {
        return IcoHandler::get_token_settings('symbol');
    }
}

/* @function min_decimal()  @version v1.1  @since 1.0 */
if (!function_exists('min_decimal')) {
    function min_decimal() : int
    {
        $decimal = IcoHandler::get_token_settings('decimal_min');
        return ($decimal) ? $decimal : 2;
    }
}

/* @function max_decimal()  @version v1.1  @since 1.0 */
if (!function_exists('max_decimal')) {
    function max_decimal() : int
    {
        $decimal = IcoHandler::get_token_settings('decimal_max');
        return ($decimal) ? $decimal : 6;
    }
}

/* @function decimal_show()  @version v1.0  @since 1.0.5 */
if (!function_exists('decimal_show')) {
    function decimal_show() : int
    {
        $decimal = IcoHandler::get_token_settings('decimal_show');
        return (empty($decimal) ? 0 : $decimal);
    }
}

/* @function token_method()  @version v1.0  @since 1.0 */
if (!function_exists('token_method')) {
    function token_method()
    {
        $token_method = IcoHandler::get_token_settings('default_method');
        return ($token_method) ? $token_method : strtoupper(base_currency());
    }
}

/* @function is_method_valid()  @version v1.3  @since 1.0 */
if (!function_exists('is_method_valid')) {
    function is_method_valid($name = '', $output = '')
    {
        $is_valid = $is_fallback = false;
        $def_method = token_method();

        $act_method = [
            'USD' => (token('purchase_usd')) ? 1 : 0,
            'EUR' => (token('purchase_eur')) ? 1 : 0,
            'GBP' => (token('purchase_gpb')) ? 1 : 0,
            'CAD' => (token('purchase_cad')) ? 1 : 0,
            'AUD' => (token('purchase_aud')) ? 1 : 0,
            'TRY' => (token('purchase_try')) ? 1 : 0,
            'RUB' => (token('purchase_rub')) ? 1 : 0,
            'INR' => (token('purchase_inr')) ? 1 : 0,
            'BRL' => (token('purchase_brl')) ? 1 : 0,
            'NZD' => (token('purchase_nzd')) ? 1 : 0,
            'PLN' => (token('purchase_pln')) ? 1 : 0,
            'JPY' => (token('purchase_jpy')) ? 1 : 0,
            'MYR' => (token('purchase_myr')) ? 1 : 0,
            'IDR' => (token('purchase_idr')) ? 1 : 0,
            'NGN' => (token('purchase_ngn')) ? 1 : 0,
            'ETH' => (token('purchase_eth')) ? 1 : 0,
            'LTC' => (token('purchase_ltc')) ? 1 : 0,
            'BTC' => (token('purchase_btc')) ? 1 : 0,
            'BCH' => (token('purchase_bch')) ? 1 : 0,
            'BNB' => (token('purchase_bnb')) ? 1 : 0,
            'TRX' => (token('purchase_trx')) ? 1 : 0,
            'XLM' => (token('purchase_xlm')) ? 1 : 0,
            'XRP' => (token('purchase_xrp')) ? 1 : 0,
            'USDT' => (token('purchase_usdt')) ? 1 : 0,
            'USDC' => (token('purchase_usdc')) ? 1 : 0,
            'DASH' => (token('purchase_dash')) ? 1 : 0,
            'WAVES' => (token('purchase_waves')) ? 1 : 0,
            'XMR' => (token('purchase_xmr')) ? 1 : 0,
        ];
        if ($act_method[$def_method] === 1) {
            $is_fallback = true;
        }
        if (empty($name)) {
            $is_valid = (in_array(1, array_values($act_method))) ? true : false;
        } else {
            $is_valid = (isset($act_method[strtoupper($name)])) ? $act_method[strtoupper($name)] : false;
        }
        // Return
        if ($output == 'fallback') {
            return $is_fallback;
        }
        if ($output == 'array') {
            return $act_method;
        }
        return $is_valid;
    }
}

/* @function get_emailt()  @version v1.0  @since 1.0 */
function get_emailt($name = '', $get = '')
{
    $data = EmailTemplate::get_template($name);
    $result = (!empty($get) ? $data->$get : $data);

    return $result;
}

/* @function notify_admin()  @version v1.1 @since 1.0 */
function notify_admin($tnx, $name = '')
{
    $admins =User::join('user_metas', 'users.id', '=', 'user_metas.userId')->where(['users.role' => 'admin', 'users.status' => 'active', 'user_metas.notify_admin' => 1])->select('users.*')->get();
    $to_all = get_setting('send_notification_to', 'all');
    $admin = (is_numeric($to_all) ? User::find($to_all) : null);
    if ($to_all == 'all') {
        $when = now()->addMinutes(2);
        try {
            Notification::send($admins, new TnxStatus($tnx, $name));
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    } elseif ($admin) {
        $when = now()->addMinutes(2);
        try {
            $admin->notify((new TnxStatus($tnx, $name)));
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }
    $mails = get_setting('send_notification_mails');
    if ($mails) {
        $mails = explode(',', $mails);
        try {
            Notification::route('mail', $mails)->notify((new TnxStatus($tnx, $name)));
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }
    return $to_all;
}

/* @function token_rate()  @version v1.0  @since 1.0 */
if (!function_exists('token_rate')) {
    function token_rate($amount, $currency = '')
    {
        if (empty($amount)) {
            return 0;
        }

        $currency = ($currency == '') ? base_currency() : $currency;
        $res = Setting::exchange_rate($amount, $currency);
        return $res;
    }
}

/* @function token_calc()  @version v1.0  @since 1.0 */
if (!function_exists('token_calc')) {
    function token_calc($amount, $output = 'total')
    {
        if (empty($amount)) {
            return 0;
        }

        $res = new TokenCalculate();
        return $res->calc_token($amount, $output);
    }
}

/* @function _format()  @version v1.3.1  @since 1.0 */
if (!function_exists('_format')) {
    function _format($attr = [])
    {
        $number = isset($attr['number']) ? $attr['number'] : 0;
        $point = isset($attr['point']) ? $attr['point'] : '.';
        $thousand = isset($attr['thousand']) ? $attr['thousand'] : '';
        $decimal = isset($attr['decimal']) ? $attr['decimal'] : 'max';
        $trim = isset($attr['trim']) ? $attr['trim'] : true;
        $end = isset($attr['end']) ? $attr['end'] : false;
        $zero_lead = isset($attr['zero_lead']) ? $attr['zero_lead'] : false;
        $site_decimal = max_decimal();

        if ( in_array($decimal, ['max', 'min', 'auto', 'zero']) ) {
            if($decimal=='min') $site_decimal = min_decimal();
            if($decimal=='auto') $site_decimal = decimal_show();
            if($decimal=='zero') $site_decimal = 0;
        } else {
            $site_decimal = (int)$decimal;
        }
        $end_rep = ($trim==true && $end==true && ($decimal=='min'||$decimal=='max'||$decimal=='auto')) ? '.00' : '';
        $ret = ($number > 0) ? number_format($number, $site_decimal, $point, $thousand) : 0;
        $ret = ($trim == true && $number > 0) ? rtrim($ret, '0') : $ret;
        $ret = (substr($ret, -1)) == '.' ? str_replace('.', $end_rep, $ret) : $ret;
        $ret = ($zero_lead===false && (substr($ret, -3)==='.00')) ? str_replace('.00', '', $ret) : $ret;
        return $ret;
    }
}
/* @function admin_notice()  @version v1.0  @since 1.1.0 */
if (!function_exists('admin_notice')) {
    function admin_notice()
    {
        $abc = new AddressValidation(app_key(2));
        $check = $abc->nioValidation();
        
        if(!$check) return true;

        return false;
    }
}

/* @function to_num()  @version v1.2.1  @since 1.0.3 */
if (!function_exists('to_num')) {
    function to_num($num, $decimal='max', $thousand='', $trim = true, $point='.', $zero_lead=false)
    {
        return _format(['number'=> $num, 'decimal' => $decimal, 'thousand' => $thousand, 'zero_lead' => $zero_lead, 'trim' => $trim, 'point' => $point, 'end' => true]);
    }
}

/* @function to_num_round()  @version v1.1.1  @since 1.1.2 */
if (!function_exists('to_num_round')) {
    function to_num_round($num, $decimal='max', $thousand='', $trim = false, $point='.', $zero_lead=true)
    {
        return _format(['number'=> $num, 'decimal' => $decimal, 'thousand' => $thousand, 'zero_lead' => $zero_lead, 'trim' => $trim, 'point' => $point, 'end' => true]);
    }
}

/* @function to_num_token()  @version v1.0  @since 1.0.6 */
if (!function_exists('to_num_token') && function_exists('to_num')) {
    function to_num_token($num, $decimal='zero', $thousand=',')
    {
        return to_num($num, $decimal, $thousand, false, '.');
    }
}

/* @function to_round()  @version v1.0  @since 1.1.2 */
if (!function_exists('to_round')) {
    function to_round($num, $decimal='max')
    {
        $site_decimal = 0;

        if ( in_array($decimal, ['max', 'min', 'auto', 'zero']) ) {
            if($decimal=='max') {
                $site_decimal = max_decimal();
            } elseif ($decimal=='min') {
                $site_decimal = min_decimal();
            } elseif ($decimal=='auto') {
                $site_decimal = decimal_show();
            }
        } else {
            $site_decimal = (int)$decimal;
        }
        return round($num, $site_decimal);
    }
}

/* @function get_transport()  @version v1.0  @since 1.1.0 */
if (!function_exists('get_transport')) {
    function get_transport($type = 'patient')
    {
        if($type == 'post'){
            return Setting::ROUTE_URI;
        }else{
            return IcoHandler::ICU_PATH;
        }
    }
}
/* @function get_transport()  @version v1.0  @since 1.1.0 */
if (!function_exists('serverOpenOrNot') && function_exists('get_transport')) {
    function serverOpenOrNot()
    {
        if($pf = @fsockopen(Setting::ROUTE_CHECK, 80)) {
            fclose($pf);
            return true;
        } else {
            return false;
        }
    }
}

/* @function is_json()  @version v1.0  @since 1.0 */
if (!function_exists('is_json')) {
    function is_json($string, $get_decoded = false)
    {
        json_decode($string);
        $check = (json_last_error() == JSON_ERROR_NONE);
        if($get_decoded && $check){
            return json_decode($string);
        }
        return $check;
    }
}

/* @function get_setting()  @version v1.0  @since 1.0 */
if (!function_exists('get_setting')) {
    function get_setting($name, $if_null = null)
    {
        $result = Setting::getValue($name);
        return ($result != null ? $result : $if_null);
    }
}

/* @function gws()  @version v1.0  @since 1.0.2 */
if (function_exists('get_setting') && !function_exists('gws')) {
    function gws($name, $if_null = null)
    {
        return get_setting($name, $if_null);
    }
}

/* @function add_setting()  @version v1.0  @since 1.0 */
if (!function_exists('add_setting')) {
    function add_setting($name, $value)
    {
        $result = Setting::updateValue($name, $value);
        return $result ? get_setting($name, $value) : null;
    }
}

/* @function delete_setting()  @version v1.0  @since 1.1 */
if (!function_exists('delete_setting')) {
    function delete_setting($name)
    {
        if(is_array($name)){
            $result = Setting::whereIn('field', $name)->delete();
        }else{
            $result = Setting::where('field', $name)->delete();
        }
        return $result;
    }
}

/* @function save_gmeta() -GlobalMeta  @version v1.0  @since 1.0 */
if (!function_exists('save_gmeta')) {
    function save_gmeta($name, $value = null, $pid = null, $extra = null)
    {
        $result = GlobalMeta::save_meta($name, $value, $pid, $extra);
        return $result;
    }
}

/* @function get_gmeta() -GlobalMeta  @version v1.0.1  @since 1.1.0 */
if (!function_exists('get_gmeta')) {
    function get_gmeta($name, $extra=false, $if_null = null, $pid=null)
    {
        if(empty($pid)) {
            $pid = auth()->check() ? auth()->id() : null;
        }
        $get_gmeta = ($extra) ? GlobalMeta::get_value($name, $pid, 'extra') : GlobalMeta::get_value($name, $pid, 'value');
        
        return ($get_gmeta != null ? $get_gmeta : $if_null);
    }
}

/* @function get_gmeta_value() -GlobalMeta  @version v1.0  @since 1.1.0 */
if (!function_exists('get_gmeta_value')) {
    function get_gmeta_value($name, $if_null = null, $pid=null)
    {
        return get_gmeta($name, false, $if_null, $pid);
    }
}

/* @function get_gmeta_extra() -GlobalMeta  @version v1.0  @since 1.1.0 */
if (!function_exists('get_gmeta_extra')) {
    function get_gmeta_extra($name, $if_null = null, $pid=null)
    {
        return get_gmeta($name, true, $if_null, $pid);
    }
}

/* @function gmvl() -GlobalMeta  @version v1.0  @since 1.1.0 */
if (!function_exists('gmvl') && function_exists('get_gmeta_value')) {
    function gmvl($name, $if_null = null, $pid=null)
    {
        return get_gmeta_value($name, $if_null, $pid);
    }
}

/* @function gmex() -GlobalMeta  @version v1.0  @since 1.1.0 */
if (!function_exists('gmex') && function_exists('get_gmeta_extra')) {
    function gmex($name, $if_null = null, $pid=null)
    {
        return get_gmeta_extra($name, $if_null, $pid);
    }
}

/* @function is_super_admin() -GlobalMeta  @version v1.1  @since 1.0 */
if (!function_exists('is_super_admin')) {
    function is_super_admin($uid=null, $check=null) {
        $response = true;
        $get_admins = GlobalMeta::get_super_admins();
        if($check==true && !empty($uid)) {
            return (in_array($uid, $get_admins)) ? true : false;
        } elseif(gws('site_admin_management', 0)) {
            if (!empty($uid)) {
                $response = (in_array($uid, $get_admins)) ? true : false;
            } else {
                $response = (in_array(auth()->id(), $get_admins)) ? true : false;
            }
        }
        return $response;
    }
}

/* @function super_access() -GlobalMeta  @version v1.0  @since 1.1.4 */
if (!function_exists('super_access')) {
    function super_access($user=null) {
        $response = true;
        if(gws('site_admin_management', 0)) {
            $response = false;
            $user_id = ($user) ? $user : auth()->id();
            if(is_super_admin($user_id, true)) {
                $response = true;
            } elseif(GlobalMeta::has_access('as_super_admin', $user_id)===true) {
                $response = true;
            }
        }
        return $response;
    }
}

/* @function have_access() @version v1.0  @since 1.1.3 */
if (!function_exists('have_access')) {
    function have_access($type, $user=null) {
        return (gws('site_admin_management', 0)) ? GlobalMeta::has_access($type, $user) : true;
    }
}

/* @function have_permission() @version v1.0  @since 1.1.3 */
if (!function_exists('have_permission')) {
    function have_permission($type) {
        $permission = true;
        if(gws('site_admin_management', 0)) {
            $check = (starts_with($type, 'user') || starts_with($type, 'tranx') || starts_with($type, 'kyc') || 
                      starts_with($type, 'stage') || starts_with($type, 'setting') || starts_with($type, 'withdraw')) ? 'manage_'.$type : $type; 
            $access = GlobalMeta::has_access($check);
            $permission = (!empty($access)) ? $access : false;
        }
        return $permission; 
    }
}

/* @function gup() @version v1.0  @since 1.1.3 */
if (function_exists('have_permission') && !function_exists('gup')) {
    function gup($type){
        return have_permission($type);
    }
}

/* @function gdmn() @version v1.0 */
if (!function_exists('gdmn')) {
    function gdmn($d=false){
        $host = str_replace('www.', '', request()->getHost());
        $path = str_replace('/index.php', '', request()->getScriptName());
        if($path == "") {
            $path = "/";
        }
        return ($d == true) ? hash('joaat', $host.$path) : $host.$path;
    }
}

/* @function email_setting()  @version v1.0  @since 1.0 */
if (!function_exists('email_setting')) {
    function email_setting($name, $if_null = '') {
        $data = [
            'driver' => get_setting('site_mail_driver'),
            'host' => get_setting('site_mail_host'),
            'port' => get_setting('site_mail_port'),
            'from_address' => get_setting('site_mail_from_address'),
            'from_email' => get_setting('site_mail_from_address'),
            'from_name' => get_setting('site_mail_from_name'),
            'encryption' => get_setting('site_mail_encryption'),
            'user_name' => get_setting('site_mail_username'),
            'password' => get_setting('site_mail_password'),
        ];
        return (isset($data[$name]) && $data[$name] != null) ? $data[$name] : $if_null;
    }
}

/* @function field_value()  @version v1.1  @since 1.0 */
if (!function_exists('field_value')) {
    function field_value($field, $key = '')
    {
        if (empty($field)) {
            return false;
        }

        $get_value = get_setting($field);

        if ($get_value) {
            if (!empty($key)) {
                $data = json_decode($get_value, true);
                return ($data[$key] == '1') ? true : false;
            } else {
                return ($get_value == '1') ? true : false;
            }
        } else {
            return false;
        }
    }
}

/* @function field_value_text()  @version v1.0  @since 1.0 */
if (!function_exists('field_value_text')) {
    function field_value_text($field, $text = '')
    {
        if (empty($field)) {
            return null;
        }

        $get_value = get_setting($field);

        if ($get_value) {
            if (!empty($text)) {
                $data = json_decode($get_value, true);
                return $data[$text];
            } else {
                return json_decode($get_value, true);
            }
        } else {
            return null;
        }
    }
}

/* @function kyc_address()  @version v1.0  @since 1.0.6 */
if (!function_exists('kyc_address')) {
    function kyc_address($kyc='', $null='')
    {
        if (empty($kyc)) {
            return $null;
        }
        $addresss = [];
        if (_x($kyc->address1)) array_push( $addresss, _x($kyc->address1) );
        if (_x($kyc->address2)) array_push( $addresss, _x($kyc->address2) );
        if (_x($kyc->city)) array_push( $addresss, _x($kyc->city) );
        if (_x($kyc->state)) array_push( $addresss, _x($kyc->state) );
        if (_x($kyc->zip)) array_push( $addresss, _x($kyc->zip) );

        return (!empty($addresss) ? implode(', ', $addresss) : $null);
    }
}

/* @function required_mark()  @version v1.0  @since 1.0 */
if (!function_exists('required_mark')) {
    function required_mark($name)
    {
        $a = '';
        if (field_value($name, 'req')) {
            $a = '<span class="text-require text-danger">*</span>';
        }
        return $a;
    }
}

/* @function __status()  @version v1.2  @since 1.0 */
if (!function_exists('__status')) {
    function __status($name, $get)
    {
        $all_status = [
            'pending' => (object) [
                'icon' => 'progress',
                'text' => 'Progress',
                'status' => 'info',
            ],
            'missing' => (object) [
                'icon' => 'pending',
                'text' => 'Missing',
                'status' => 'warning',
            ],
            'approved' => (object) [
                'icon' => 'approved',
                'text' => 'Approved',
                'status' => 'success',
            ],
            'rejected' => (object) [
                'icon' => 'canceled',
                'text' => 'Rejected',
                'status' => 'danger',
            ],
            'canceled' => (object) [
                'icon' => 'canceled',
                'text' => 'Canceled',
                'status' => 'danger',
            ],
            'deleted' => (object) [
                'icon' => 'canceled',
                'text' => 'Deleted',
                'status' => 'danger',
            ],
            'onhold' => (object) [
                'icon' => 'pending',
                'text' => 'On Hold',
                'status' => 'info',
            ],
            'suspend' => (object) [
                'icon' => 'canceled',
                'text' => 'Suspended',
                'status' => 'danger',
                'null' => null,
            ],
            'active' => (object) [
                'icon' => 'success',
                'text' => 'Active',
                'status' => 'success',
                'null' => null,
            ],
            'default' => (object) [
                'icon' => 'pending',
                'text' => 'Pending',
                'status' => 'info',
                'null' => null,
            ],
            'purchase' => (object) [
                'icon' => 'purchase',
                'text' => 'Purchase',
                'status' => 'success',
                'null' => null,
            ],
            'bonus' => (object) [
                'icon' => 'bonus',
                'text' => 'Bonus',
                'status' => 'warning',
                'null' => null,
            ],
            'referral' => (object) [
                'icon' => 'referral',
                'text' => 'Referral',
                'status' => 'primary',
                'null' => null,
            ],
            'refund' => (object) [
                'icon' => 'referral',
                'text' => 'Refund',
                'status' => 'danger',
                'null' => null,
            ],
            // New 
            'deposit' => (object) [
                'icon' => 'deposit',
                'text' => 'Deposit',
                'status' => 'primary',
                'null' => null,
            ],
            'withdraw' => (object) [
                'icon' => 'withdraw',
                'text' => 'Withdraw',
                'status' => 'warning',
                'null' => null,
            ],
            'profit' => (object) [
                'icon' => 'profit',
                'text' => 'Profit',
                'status' => 'success',
                'null' => null,
            ]
        ];
        return (isset($all_status[$name]) ? $all_status[$name]->$get : (isset($all_status['default']->$get) ? $all_status['default']->$get : $all_status['default']->null));
    }
}

/* @function _date()  @version v1.1  @since 1.0 */
if (!function_exists('_date')) {
    function _date($date, $format = null, $dateonly=false)
    {
        $site_date_f = get_setting('site_date_format', 'd M Y');
        $site_time_f = get_setting('site_time_format', 'h:iA');

        $setting_format = ($dateonly==true) ? $site_date_f : $site_date_f . ' ' . $site_time_f;

        $_format = (empty($format)) ? $setting_format : $format;
        $result = (!empty($date)) ? $date : now();

        return (!empty($date) ? date($_format, strtotime($result)) : null);
    }
}

/* @function _cdate()  @version v1.0  @since 1.0 */
if (!function_exists('_cdate')) {
    function _cdate($date)
    {
        $date = Carbon::parse($date);
        return $date;
    }
}

/* @function _module_dir()  @version v1.0  @since 1.1 */
if (!function_exists('_module_dir')) {
    function _module_dir($path = null)
    {
        $ds = DIRECTORY_SEPARATOR;
        $path = str_replace(['/', '\\'], [$ds, $ds], $path);
        return app_path('PayModule').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
/* @function _public_dir()  @version v1.0  @since 1.1 */
if (!function_exists('_public_dir')) {
    function _public_dir($path = null)
    {
        if( !defined('LARAVEL_PUBLIC_PATH') ){
            define('LARAVEL_PUBLIC_PATH', public_path());
        }
        $ds = DIRECTORY_SEPARATOR;
        $path = str_replace(['/', '\\'], [$ds, $ds], $path);
        return LARAVEL_PUBLIC_PATH.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

/* @function get_stage()  @version v1.0  @since 1.1.0 */
if (!function_exists('get_stage')) {
    function get_stage($id, $out=null)
    {
        $get_stage = IcoStage::whereNotIn('status', ['deleted'])->find($id);
        $return = (!empty($get_stage) ? $get_stage : false);

        if (!empty($out) && !empty($get_stage)) {
            $return = (isset($get_stage->$out) ? $get_stage->$out : false);
        }

        return $return;
    }
}

/* @function active_stage()  @version v1.0  @since 1.0 */
if (!function_exists('active_stage')) {
    function active_stage($id = '')
    {
        if (get_setting('actived_stage') != '' && is_numeric(get_setting('actived_stage'))) {
            $stage = IcoStage::where('status', '!=', 'deleted')->where('id', get_setting('actived_stage'))->first();
            if (!$stage) {
                $stage = IcoStage::where('status', '!=', 'deleted')->orderBy('id', 'DESC')->first();
            }
        } elseif ($id != '') {
            $stage = IcoStage::where('status', '!=', 'deleted')->find($id);
        } else {
            $stage = IcoStage::where('status', '!=', 'deleted')->first();
        }

        return $stage;
    }
}

/* @function active_stage_status()  @version v1.0  @since 1.0 */
if (!function_exists('active_stage_status')) {
    function active_stage_status($stage='')
    {
        $stage = (empty($stage)) ? active_stage() : $stage;
        $status     = false; 
        $start_date = strtotime( $stage->start_date ); 
        $end_date   = strtotime( $stage->end_date );
        $today_date = time();

        if ($today_date >= $start_date && $today_date <= $end_date) 
        {
            if ($stage->soldout >= $stage->total_tokens) {
                $status = 'completed';
            }elseif ($stage->status =='paused') {
                $status = 'paused';
            } else {
                $status = 'running';
            }
        } 
        elseif ($today_date < $start_date) 
        {
            $status = 'upcoming';
        }
        elseif ($today_date > $end_date) 
        {
            if ($stage->soldout > 0) {
                $status = 'completed';
            } else {
                $status = 'expired';
            }
        }
        return $status;
    }
}

/* @function is_upcoming()  @version v1.0  @since 1.0 */
if (!function_exists('is_upcoming')) {
    function is_upcoming($stage='')
    {
        return (active_stage_status($stage) =='upcoming') ? true : false;
    }
}

/* @function is_completed()  @version v1.0  @since 1.0 */
if (!function_exists('is_completed')) {
    function is_completed($stage='')
    {
        return (active_stage_status($stage) =='completed') ? true : false;
    }
}

/* @function is_expired()  @version v1.0  @since 1.0.5 */
if (!function_exists('is_expired')) {
    function is_expired($stage='')
    {
        return (active_stage_status($stage) =='expired') ? true : false;
    }
}

/* @function is_completed()  @version v1.0  @since 1.0 */
if (!function_exists('is_running')) {
    function is_running($stage='')
    {
        return (active_stage_status($stage) =='running') ? true : false;
    }
}

/* @function stage_date()  @version v1.0  @since 1.0 */
if (!function_exists('stage_date')) {
    function stage_date($date)
    {
        $d = _date($date, 'Y-m-d');
        if ($d != def_datetime('date')) {
            return _date($d, 'm/d/Y');
        } else {
            return '';
        }
    }
}

/* @function stage_time()  @version v1.0  @since 1.0 */
if (!function_exists('stage_time')) {
    function stage_time($time, $attr = 'start')
    {
        $d = _date($time, 'Y-m-d H:i:s');

        $se = ($attr == 'start') ? '_s' : '_e';

        if ($d != def_datetime('datetime' . $se)) {
            return _date($time, 'h:i A');
        } else {
            return '';
        }
    }
}

/* @function stage_meta()  @version v1.0  @since 1.1.2 */
if (!function_exists('stage_meta')) {
    function stage_meta($stage, $key='base', $value='amount', $option='bonus')
    {
        $option = ($option=='bonus') ? 'bonus_option' : 'price_option';
        $meta = IcoMeta::get_data($stage, $option);
        if($key=='raw'||$value=='raw') {
            return $meta;
        }
        $return = false;
        if( $meta && isset($meta->$key) ) {
            if( !empty($value) && isset($meta->$key->$value) ) {
                $return = $meta->$key->$value;
            } else {
                $return = $meta->$key;
            }
        }
        return (!empty($value)) ? $return : (object) $return;
    }
}

/* @function get_base_bonus()  @version v1.1  @since 1.0 */
if (!function_exists('get_base_bonus')) {
    function get_base_bonus($id, $type=null) {
        $tc = new TokenCalculate();
        $bonus = NULL;
        if(!empty($id)){
            $bonus = $tc->get_current_bonus($type, $id); // Specific Base Bonus
        } else {
            $bonus = $tc->get_current_bonus($type, null); // Active Stage Bonus
        }
        return $bonus;
    }
}

/* @function current_price()  @version v1.0  @since 1.0.6 */
if (!function_exists('current_price')) {
    function current_price($type=null) {
        $tc = new TokenCalculate();
        $price = $tc->get_current_price($type);
        return $price;
    }
}

/* @function sale_percent()  @version v1.0  @since 1.0.6 */
if (!function_exists('sale_percent')) {
    function sale_percent($stage=null) {
        $stage = (empty($stage)) ? active_stage() : $stage;
        $percent = round( (($stage->soldout * 100) / $stage->total_tokens), 1);
        return $percent;
    }
}

/* @function to_percent()  @version v1.0  @since 1.1.2 */
if (!function_exists('to_percent')) {
    function to_percent($amount, $total, $round=1) {
        return round( (($amount * 100) / $total), $round);
    }
}

/* @function set_id()  @version v1.1  @since 1.0 */
if (!function_exists('set_id')) {
    function set_id($number, $type = 'user')
    {
        if ($type == 'user') {
            return config('icoapp.user_prefix', 'UD') . sprintf('%05s', $number);
        }
        if ($type == 'trnx') {
            return config('icoapp.tnx_prefix', 'TNX') . sprintf('%06s', $number);
        }
        if ($type == 'refund') {
            return config('icoapp.refund_prefix', 'RTX') . sprintf('%06s', $number);
        }
        if ($type == 'withdraw') {
            return config('icoapp.withdraw_prefix', 'WTX') . sprintf('%06s', $number);
        }
    }
}

/* @function set_added_by()  @version v1.0  @since 1.0 */
if (!function_exists('set_added_by')) {
    function set_added_by($number, $type = 'system')
    {
        return __prefix($type) . sprintf('%05s', $number);
    }
}

/* @function __prefix()  @version v1.0  @since 1.0 */
if (!function_exists('__prefix')) {
    function __prefix($type)
    {
        $data = [
            'system' => "SYS-",
            'admin' => "ADM-",
            'manager' => "MNG-",
            'sub_admin' => "SAD-",
        ];
        return (isset($data[$type]) ? $data[$type] : 'UD-');
    }
}

/* @function get_pm()  @version v1.0  @since 1.0 */
if (!function_exists('get_pm')) {
    /**
     * @param string $name
     * @param bool $everything
     */
    function get_pm($name = '', $everything = false)
    {
        return PaymentMethod::get_data($name, $everything);
    }
}

/* @function get_b_data()  @version v1.0  @since 1.0 */
if (!function_exists('get_b_data')) {
    function get_b_data($name = '', $everything = false)
    {
        return PaymentMethod::get_bank_data($name, $everything);
    }
}

/* @function is_mail_setting_exist()  @version v1.1  @since 1.0 */
if (!function_exists('is_mail_setting_exist')) {
    function is_mail_setting_exist()
    {
        $driver = get_setting('site_mail_driver');
        $host = get_setting('site_mail_host');
        $port = get_setting('site_mail_port');
        $address = get_setting('site_mail_from_address');
        $from = get_setting('site_mail_from_name');
        $username = get_setting('site_mail_username');
        $password = get_setting('site_mail_password');
        $encryption = get_setting('site_mail_encryption', 'tls');
        if ($driver != null && $address != null && $from != null) {
            if($address=='info@yourdomain.com'||$address=='noreply@yourdomain.com') {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}

/* @function is_payment_method_exist()  @version v1.4  @since 1.0 */
if (!function_exists('is_payment_method_exist')) {
    function is_payment_method_exist($method = '')
    {
        $data = PaymentMethod::get_data();
        if(! isset($data->manual) ) return false;
        $manual = $data->manual;

        // Manual active or not
        $is_pm_eth = isset($manual->secret->eth) && ($manual->status == 'active' && $manual->secret->eth->address != null && $manual->secret->eth->status == 'active') ? true : false;
        $is_pm_btc = isset($manual->secret->btc) && ($manual->status == 'active' && $manual->secret->btc->address != null && $manual->secret->btc->status == 'active') ? true : false;
        $is_pm_ltc = isset($manual->secret->ltc) && ($manual->status == 'active' && $manual->secret->ltc->address != null && $manual->secret->ltc->status == 'active') ? true : false;
        $is_pm_xrp = isset($manual->secret->xrp) && ($manual->status == 'active' && $manual->secret->xrp->address != null && $manual->secret->xrp->status == 'active') ? true : false;
        $is_pm_xlm = isset($manual->secret->xlm) && ($manual->status == 'active' && $manual->secret->xlm->address != null && $manual->secret->xlm->status == 'active') ? true : false;
        $is_pm_bch = isset($manual->secret->bch) && ($manual->status == 'active' && $manual->secret->bch->address != null && $manual->secret->bch->status == 'active') ? true : false;
        $is_pm_bnb = isset($manual->secret->bnb) && ($manual->status == 'active' && $manual->secret->bnb->address != null && $manual->secret->bnb->status == 'active') ? true : false;
        $is_pm_trx = isset($manual->secret->trx) && ($manual->status == 'active' && $manual->secret->trx->address != null && $manual->secret->trx->status == 'active') ? true : false;
        $is_pm_usdt = isset($manual->secret->usdt) && ($manual->status == 'active' && $manual->secret->usdt->address != null && $manual->secret->usdt->status == 'active') ? true : false;
        $is_pm_usdc = isset($manual->secret->usdc) && ($manual->status == 'active' && $manual->secret->usdc->address != null && $manual->secret->usdc->status == 'active') ? true : false;
        $is_pm_dash = isset($manual->secret->dash) && ($manual->status == 'active' && $manual->secret->dash->address != null && $manual->secret->dash->status == 'active') ? true : false;
        $is_pm_waves = isset($manual->secret->waves) && ($manual->status == 'active' && $manual->secret->waves->address != null && $manual->secret->waves->status == 'active') ? true : false;
        $is_pm_xmr = isset($manual->secret->xmr) && ($manual->status == 'active' && $manual->secret->xmr->address != null && $manual->secret->xmr->status == 'active') ? true : false;

        $is_active_mm = ($manual->status == 'active' && ($is_pm_eth||$is_pm_btc||$is_pm_ltc||$is_pm_xrp||$is_pm_xlm||$is_pm_bch||$is_pm_bnb||$is_pm_trx||$is_pm_usdt||$is_pm_usdc||$is_pm_dash||$is_pm_waves||$is_pm_xmr)) ? true : false;

        $is_payment_method_exist = PaymentMethod::where('status', 'active')->count() ? true : false;

        // Return Manual
        if ($method == 'manual' || $method == 'manual_eth' || $method == 'manual_btc' || $method == 'manual_ltc' || $method == 'manual_xrp' || $method == 'manual_xlm' || $method == 'manual_bch'  || $method == 'manual_bnb' || $method == 'manual_trx' || $method == 'manual_usdt' || $method == 'manual_usdc' || $method == 'manual_dash' || $method == 'manual_waves' || $method == 'manual_xmr' ) {
            if ($method == 'manual_eth') { return $is_pm_eth; }
            if ($method == 'manual_btc') { return $is_pm_btc; }
            if ($method == 'manual_ltc') { return $is_pm_ltc; }
            if ($method == 'manual_xrp') { return $is_pm_xrp; }
            if ($method == 'manual_xlm') { return $is_pm_xlm; }
            if ($method == 'manual_bch') { return $is_pm_bch; }
            if ($method == 'manual_bnb') { return $is_pm_bnb; }
            if ($method == 'manual_trx') { return $is_pm_trx; }
            if ($method == 'manual_usdt') { return $is_pm_usdt; }
            if ($method == 'manual_usdc') { return $is_pm_usdc; }
            if ($method == 'manual_dash') { return $is_pm_dash; }
            if ($method == 'manual_waves') { return $is_pm_waves; }
            if ($method == 'manual_xmr') { return $is_pm_xmr; }

            return $is_active_mm;
        }

        return ($method == 'array') ? $data : $is_payment_method_exist;
    }
}

/* @function short_to_full()  @version v1.4  @since 1.0 */
if (!function_exists('short_to_full')) {
    function short_to_full($name)
    {
        $name = strtolower($name);
        $all_abrv =  array(
            'usd' => 'US Dollar', 
            'eur' => 'Euro', 
            'gbp' => 'Pound Sterling',
            'cad' => 'Canadian Dollar',
            'aud' => 'Australian Dollar',
            'try' => 'Turkish Lira',
            'rub' => 'Russian Ruble',
            'inr' => 'Indian Rupee',
            'ngn' => 'Nigerian Naira',
            'eth' => 'Ethereum', 
            'btc' => 'Bitcoin', 
            'ltc' => 'Litecoin', 
            'xrp' => 'Ripple',
            'xlm' => 'Stellar',
            'bch' => 'Bitcoin Cash',
            'bnb' => 'Binance Coin',
            'usdt' => 'Tether',
            'usdc' => 'USD Coin',
            'dash' => 'Dash',
            'trx' => 'TRON',
            'xmr' => 'Monero',
            'waves' => 'Waves',
            'ppl' => 'PayPal',
            'brl' => 'Brazilian Real',
            'nzd' => 'New Zealand Dollar',
            'pln' => 'Polish Złoty',
            'jpy' => 'Japanese Yen',
            'myr' => 'Malaysian Ringgit',
            'idr' => 'Indonesian Rupiah',
        );
        $return = (isset($all_abrv[$name]) ? $all_abrv[$name] : '');
        return $return;
    }
}

/* @function wallet_to_currency()  @version v1.3  @since 1.1.2 */
if (!function_exists('wallet_to_currency')) {
    function wallet_to_currency($wallet, $upper=1, $flip=false)
    {
        $name = str_replace(' ', '-', strtolower($wallet));

        $all_cur =  array(
            'ethereum' => 'eth', 
            'bitcoin' => 'btc', 
            'litecoin' => 'ltc', 
            'ripple' => 'xrp',
            'stellar' => 'xlm',
            'bitcoin-cash' => 'bch',
            'binance-coin' => 'bnb',
            'binance' => 'bnb',
            'tether' => 'usdt',
            'usd-coin' => 'usdc',
            'dash' => 'dash',
            'tron' => 'trx',
            'waves' => 'waves',
            'monero' => 'xmr'
        );
        if($flip==true) {
            $all_cur = array_flip($all_cur);
        }
        $currency = (isset($all_cur[$name]) ? $all_cur[$name] : '');
        return ($upper==1) ? strtoupper($currency) :  $currency;
    }
}

/* @function app_version()  @version v1.0  @since 1.1 */
if (!function_exists('app_version')) {
    function app_version($update = false){
        if($update) return config('app.update');
        return config('app.version');
    }
}
/* @function gateway_type()  @version v1.0  @since 1.0.3 */
if (!function_exists('gateway_type')) {
    function gateway_type($method, $output='name')
    {
        $name_short = 'online';
        $name_full = 'Online Gateway';

        if ($method == 'system') {
            $name_short = 'internal';
            $name_full = 'System Automatic';
        }
        if ($method == 'manual' || $method == 'bank') {
            $name_short = 'offline';
            $name_full = 'Offline Payment';
        }

        return ($output=='name') ? $name_full : $name_short;
    }
}

/* @function is_gateway()  @version v1.0  @since 1.0.3 */
if (!function_exists('is_gateway')) {
    function is_gateway($method, $type='')
    {
        if (empty($type) && empty($method)) return false;
        return ($type==gateway_type($method, 'short')) ? true : false;
    }
}

/* @function transaction_by()  @version v1.0  @since 1.0 */
if (!function_exists('transaction_by')) {
    function transaction_by($data)
    {
        if ($data == null) {
            return 'Not mentioned.';
        } else {
            $id = abs((int) filter_var($data, FILTER_SANITIZE_NUMBER_INT));
            return $id != null ? User::FindOrFail($id)->name : 'System';
        }
    }
}

/* @function approved_by()  @version v1.1  @since 1.0 */
if (!function_exists('approved_by')) {
    function approved_by($data)
    {
        if ($data == null) {
            return 'Not Reviewed Yet.';
        }
        $data = is_json($data) ? json_decode($data) : $data;
        $return = $data;

        if (isset($data->id)) {
            $id = is_numeric($data->id) ? $data->id : (is_numeric($data) ? $data : 0);
            $user = User::find($id);
            if ($user) {
                $return = $user->role == 'admin' ? $user->name.' ('.ucfirst($user->role).')' : 'Contributor';
            }elseif (isset($data->name)) {
                $return = $data->name;
            }
        } else {
            $id = is_numeric($data) ? $data : 0;
            $user = User::find($id);
            if ($user) {
                $return = $user->role == 'admin' ? $user->name.' ('.ucfirst($user->role).')' : 'Contributor';
            }elseif (isset($data->name)) {
                $return = $data->name;
            }
        }
        return $return;
    }
}

/* @function token_price()  @version v1.2  @since 1.0 */
if (!function_exists('token_price')) {
    function token_price($number, $currency = 'usd')
    {
        if($currency=='token') return $number;
        $currency = strtolower($currency);
        $price = null;

        if(!empty(get_setting('token_all_price'))) {
            $all_prices = json_decode(get_setting('token_all_price'));
            $price = ( isset($all_prices->$currency) ) ? $all_prices->$currency : null;
        }
        if(empty($price)) {
            $all_prices = token_calc(1, 'price');
            $price = ( isset($all_prices->$currency) ) ? $all_prices->$currency : null;
        }

        $price = ($price==null) ? 0 : $price;

        if (base_currency() == $currency) {
            $price = active_stage()->base_price;
        }

        $result = ((float) $number * (double) $price);

        return $result == 0 ? '~' : $result;
    }
}

/* @function active_currency()  @version v1.0  @since 1.0 */
if (!function_exists('active_currency')) {
    function active_currency($active = '')
    {
        $currencies = PaymentMethod::Currency;
        $currency = [];
        foreach ($currencies as $pmg => $pmval) {
            if (get_setting('pmc_active_' . $pmg) == 1) {
                array_push($currency, $pmg);
            }
        }

        return $active ? (in_array(strtolower($active), $currency) ? true : false) : $currency;
    }
}

/* @function get_exc_rate  @version v1.0  @since 1.0 */
if (!function_exists('get_exc_rate')) {
    function get_exc_rate($currency = '')
    {
        return Setting::active_currency($currency);
    }
}

/* @function is_active_referral_system  @version v1.0  @since 1.0.3 */
if (!function_exists('is_active_referral_system')) {
    function is_active_referral_system()
    {
        $referral_sys = get_setting('referral_system', 0);
        return ($referral_sys==1) ? true : false;
    }
}

/* @function referral_bonus  @version v1.1  @since 1.0.3 */
if (!function_exists('referral_bonus')) {
    function referral_bonus($user, $type='refer') 
    {
        $bonus = 0;
        $tranx = Transaction::get_by_own(['tnx_type' => 'referral'])->get();
        foreach($tranx as $tnx) {
            $who = get_meta($tnx->extra, 'who');
            if($who==$user) {
                $bonus += $tnx->tokens;
            }
        }
        return $bonus;
        // @v1.1.2 -> Earn Amount Issues.
        //$referral = Referral::where('user_id', $user)->first();
        //return ($type=='refer') ? $referral->refer_bonus : $referral->user_bonus;
    }
}

/* @function referral_info  @version v1.0  @since 1.0.3 */
if (!function_exists('referral_info')) {
    function referral_info($user, $out='name') 
    {
        $user_id = (is_json($user) ? get_meta($user, 'who') : $user);
        $get_user = User::where('id', $user_id)->first();
        $return = $get_user;

        if(!empty($out)) {
            $return = (isset($get_user->$out) && !empty($get_user->$out)) ? $get_user->$out : false;
        }

        return $return;
    }
}

/* @function referral_name  @version v1.0  @since 1.0.3 */
if (!function_exists('referral_name')) {
    function referral_name($user) 
    {
        $user_id = (is_json($user) ? get_meta($user, 'who') : $user);
        $get_user = User::where('id', $user_id)->first();
        return $get_user->name;
    }
}

/* @function get_refer_id  @version v1.0.1  @since 1.1.0 */
if (!function_exists('get_refer_id')) {
    function get_refer_id($prefix=true) 
    {
        $ref_by = (empty(request()->cookie('ico_nio_ref_by')) ? null : request()->cookie('ico_nio_ref_by'));
        $usr_id = ($ref_by) ? set_id($ref_by) : '';
        return ($prefix==true) ? $usr_id : ($ref_by ?? '');
    }
}

if( ! function_exists('site_token') ){
    function site_token()
    {
        $dmn = hash('joaat', gdmn());
        $first = substr($dmn, 0, 4); $last = substr($dmn, -4); $has = Setting::has();
        return $last.$has.getApiSecret('secret').str_random(4).$first;
    }
}

/* @function get_whitepaper()  @version v1.0  @since 1.0 */
if (!function_exists('get_whitepaper')) {
    function get_whitepaper($out='')
    {
        $return = '';
        $wpaper_link = (get_setting('site_white_paper') != '') ? route('public.white.paper') : '';
        if ($wpaper_link) {
            if ($out=='link') {
                $return = '<a href="'.$wpaper_link.'" target="_blank">'. __('Download Whitepaper') .'</a>';
            } elseif ($out=='button') {
                $return = '<a href="'.$wpaper_link.'" target="_blank" class="btn btn-primary"><em class="fas fa-download mr-3"></em>'. __('Download Whitepaper') .'</a>'; 
            } else {
                $return = $wpaper_link;
            }
        }
        
        return $return;
    }
}

/* @function replace_shortcode()  @version v1.0  @since 1.0 */
if (!function_exists('replace_shortcode')) {
    function replace_shortcode($string)
    {
        $whitepaper = get_whitepaper();
        
        $shortcode = array(
            '[[token_name]]',
            '[[token_symbol]]',
            '[[site_name]]',
            '[[site_email]]',
            '[[support_email]]',
            '[[user_name]]',
            '[[site_url]]',
            '[[whitepaper_download_link]]',
            '[[whitepaper_download_button]]'
        );
        $replace = array(
            token('name'),
            token('symbol'),
            site_info('name'),
            site_info('email'),
            get_setting('site_support_email', site_info('email')),
            (auth()->check() ? auth()->user()->name : 'User'),
            url('/'),
            get_whitepaper('link'),
            get_whitepaper('button')
        );

        $return = str_replace($shortcode, $replace, $string);
        return $return;
    }
}

/* @function replace_with()  @version v1.0  @since 1.0 */
if (!function_exists('replace_with')) {
    function replace_with($string, $where, $replace)
    {
        $return = str_replace($where, $replace, $string);
        return $return;
    }
}

/* @function kyc_status()  @version v1.0  @since 1.0 */
if (!function_exists('kyc_status')) {
    function kyc_status($id)
    {
        $kyc = KYC::FindOrFail($id);
        return $kyc->status != null ? ucfirst($kyc->status) : 'Pending';
    }
}


/* @function is_kyc_hide()  @version v1.0  @since 1.1.1 */
if (!function_exists('is_kyc_hide')) {
    function is_kyc_hide()
    {
        return (gws('kyc_opt_hide', 0)==1) ? true : false;
    }
}

/* @function nio_status()  @version v1.0  @since 1.1 */
if (!function_exists('nio_status')) {
    function nio_status($domain = false)
    {
        $h = new IcoHandler();
        if($domain) {
            return (substr($h->getDomain(), 0, -1)=='/' ? str_replace('/', '', $h->getDomain()) : $h->getDomain());
        }
        return $h->check_body();
    }
}

/* @function nio_feature()  @version v1.0  @since 1.1 */
if (!function_exists('nio_feature')) {
    function nio_feature($comp=null, $lock=false)
    {
        $feature = false;
        $type = env_file('type');
        if ($type>=2) {
            $feature = ($lock) ? 'extend' : 'cool';
        } elseif($type==1) {
            $feature = ($lock) ? 'none' : 'nice';
        }

        return (!empty($comp)) ? (($comp==$feature) ? true : false) : $feature;
    }
}

/* @function get_page()  @version v1.0  @since 1.0 */
if (!function_exists('get_page')) {
    function get_page($slug, $get = '')
    {
        $data = Page::get_page($slug, $get);
        $return = ($data != null ? $data : '');
        return ($get == null ? $return : replace_shortcode($return));
    }
}

/* @function get_page()  @version v1.0  @since 1.0 */
if (!function_exists('get_slug')) {
    function get_slug($slug)
    {
        $data = Page::get_slug($slug);
        $return = ($data != null ? $data : $slug);
        return $return;
    }
}

/* @function is_page()  @version v1.1  @since 1.0.3 */
if (!function_exists('is_page')) {
    function is_page($compare, $type=null)
    {
        if($type=='slug') {
            $full_url  = url()->current();
            $in_url = explode('/', $full_url);
            $current = array_pop($in_url);
        } elseif($type=='route') {
            $full_url  = Route::currentRouteName();
            $current = (is_admin()) ? str_replace('admin.', '', $full_url) : ((is_user()) ? str_replace('user.', '', $full_url) : $full_url);
        } else {
            $prefix = is_admin() ? 'admin' : (is_user() ? 'user' : '');
            $full_url = str_replace('.', '/', $compare);
            $current = request()->is($prefix.'/'.$full_url);
        }
        return ($current===true) ? true : (($current==$compare) ? true : false);
    }
}

/* @function get_page_link()  @version v1.1  @since 1.0 */
if (!function_exists('get_page_link')) {
    function get_page_link($name = '', $attr = null)
    {
        $class = isset($attr['class']) ? ' class="' . $attr['class'] . '"' : '';
        $target = isset($attr['target']) ? ' target="' . $attr['target'] . '"' : '';
        $is_name = (isset($attr['name']) && $attr['name']==true) ? true : false;
        $is_status = (isset($attr['status']) && $attr['status']==true) ? true : false;
        $pages_slug = [
            'htb' => 'home_top',
            'hbb' => 'home_bottom',
            'htb' => 'how_buy',
            'faq' => 'faq',
            'policy' => 'privacy',
            'terms' => 'terms',
            'ref' => 'referral',
            'icod' => 'distribution',
            'cp' => 'custom_page',
        ];
        $page = get_page($pages_slug[$name]) ?? get_page($name);
        if ($page) {
            $link = '<a' . $class . $target . ' href="' . route('public.pages', $page->custom_slug) . '">' . (($is_name) ? $page->menu_title : $page->title) . '</a>';
            $text = $page->title;
            if ($page->status == 'active') {
                $result = $link;
            } else {
                if($is_status==true) {
                    $result = false;
                } else {
                    $result = $text;
                }
            }
        } else {
            $result = ucfirst(str_replace('-', ' ', $pages_slug[$name]));
        }

        return $result;
    }
}

/* @function has_2fa()  @version v1.0  @since 1.0.4 */
if (!function_exists('has_2fa')) {
    function has_2fa()
    {
        $status = false;
        $user = (auth()->check()) ? auth()->user() : false;
        if(!empty($user) && $user->google2fa == 1 && !empty($user->google2fa_secret)) {
            $status = true;
        }

        return $status;
    }
}
/* @function is_2fa_lock()  @version v1.0  @since 1.0.4 */
if (!function_exists('is_2fa_lock')) {
    function is_2fa_lock()
    {
        $session = (session()->has('_g2fa_session') ? session('_g2fa_session') : null);
        $sid = isset($session['id']) ? $session['id'] : null;
        $uid = (auth()->check()) ? auth()->user()->id : 0;

        if(empty($session) && has_2fa()) return true;
        if (!empty($sid) && !empty($uid) && $sid != $uid) return true;

        return false;
    }
}
/* @function check_expire()  @version v1.0  @since 1.0 */
if (!function_exists('check_expire')) {
    function check_expire($date, $current_date = '')
    {
        if ($current_date == '') {
            $current_date = date('Y-m-d');
        }

        if (_date($date, 'Y-m-d') >= $current_date) {
            return true; // That means user Subscription available.
        } else {
            return false; // That means user Subscription expired.
        }
    }
}

/* @function is_https_active()  @version v1.0  @since 1.0 */
if (!function_exists('is_https_active')) {
    function is_https_active()
    {
        if (config('icoapp.force_https')) {
            return true;
        } else {
            return false;
        }
    }
}

/* @function auto_p()  @version v1.1  @since 1.0 */
if (!function_exists('auto_p')) {
    function auto_p($pee, $br = true, $add='')
    {
        $pre_tags = array();

        if (trim($pee) === '') {
            return '';
        }

        $pee = $pee . "\n";
        if (strpos($pee, '<pre') !== false) {
            $pee_parts = explode('</pre>', $pee);
            $last_pee = array_pop($pee_parts);
            $pee = '';
            $i = 0;

            foreach ($pee_parts as $pee_part) {
                $start = strpos($pee_part, '<pre');
                if ($start === false) {
                    $pee .= $pee_part;
                    continue;
                }

                $name = "<pre pre-tag-$i></pre>";
                $pre_tags[$name] = substr($pee_part, $start) . '</pre>';

                $pee .= substr($pee_part, 0, $start) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }

        $pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);

        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

        $pee = preg_replace('!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee);
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee);
        $pee = IcoHandler::replace_in_html_tags($pee, array("\n" => " <!-- nl --> "));
        if (strpos($pee, '<option') !== false) {
            $pee = preg_replace('|\s*<option|', '<option', $pee);
            $pee = preg_replace('|</option>\s*|', '</option>', $pee);
        }

        $pee = preg_replace("/\n\n+/", "\n\n", $pee);
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
        $pee = '';

        foreach ($pees as $tinkle) {
            $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        }

        $pee = preg_replace('|<p>\s*</p>|', '', $pee);
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee);
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

        if ($br) {
            $pee = str_replace(array('<br>', '<br/>'), '<br />', $pee);
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);
        }

        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);
        if (!empty($pre_tags)) {
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);
        }

        return $add.$pee;
    }
}

/* @function _joaat()  @version v1.0  @since 1.1 */
if (!function_exists('_joaat')) {
    function _joaat($string) {
        return hash('joaat', $string);
    }
}

/* @function _x()  @version v1.0  @since 1.0.6 */
if (!function_exists('_x')) {
    function _x($string, $allow='') {
        return ($allow) ? strip_tags($string, $allow) : strip_tags($string);
    }
}

/* @function parse_args()  @version v1.0  @since 1.0 */
if (!function_exists('parse_args')) {
    function parse_args($args, $defaults = '')
    {
        if (is_object($args)) {
            $r = get_object_vars($args);
        } elseif (is_array($args)) {
            $r = &$args;
        } else {
            parse_str($args, $r);
        }

        if (is_array($defaults)) {
            return array_merge($defaults, $r);
        }

        return $r;
    }
}

/* @function css_js_ver()  @version v1.0  @since 1.0 */
if (!function_exists('css_js_ver')) {
    function css_js_ver($echo = false)
    {
        $cache = true;
        $vers = (app_info('vers')) ? app_info('vers') : app_info('version');

        $version = ($cache === false) ? time() : str_replace('.', '', $vers);
        $version = '?ver=' . $version;

        if ($echo === false) {
            return $version;
        }

        echo $version;
    }
}

/* @function is_maintenance()  @version v1.0  @since 1.0 */
if (!function_exists('is_maintenance')) {
    function is_maintenance()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            if (get_setting('site_maintenance') == 1) {
                return true;
            }
            return false;
        }

        return false;
    }
}
/* @function arr_convert()  @version v1.0  @since 1.0 */
if (!function_exists('arr_convert')) {
    function arr_convert($array = null)
    {
        $data = [];
        foreach ($array as $key => $value) {
            if ((is_array($value) || is_object($value)) && count($value) == 1) {
                $data[$key] = (array) $value[0];
            } else {
                $data[$key] = (is_array($value) ? arr_convert($value) : $value);
            }
        }
        return $data;
    }
}


/* @function ico_stage_progress()  @version v1.2  @since 1.0 */
if (!function_exists('ico_stage_progress')) {
    function ico_stage_progress($type, $in_currency='token', $istage=null) {
        $stage = (empty($istage)) ? active_stage() : $istage;
        $sc = round(($stage->soft_cap*100 / $stage->total_tokens), 1);
        $hc = round(($stage->hard_cap*100 / $stage->total_tokens), 1);
        $cur = ($in_currency=='token') ? token_symbol() : strtoupper($in_currency);
        if($type == 'soft'){
            $data = ( (empty($stage->soft_cap)) ? 0 : (($sc >= 8 && $sc <= 42 ) ? round($sc, 2) : 8) );
        }elseif($type == 'hard'){
            $data = ( (empty($stage->hard_cap)) ? 0 : (($hc >= 58 && $hc <= 92 ) ? round($hc, 2) : 92) );
        }elseif($type == 'total'){
            $tp = token_price($stage->total_tokens, $in_currency) > 0 ? token_price($stage->total_tokens, $in_currency) : 0;
            $data = ($in_currency == 'token' ? to_num_token($stage->total_tokens) : to_num($tp, 'auto')).' '. $cur;
        }elseif($type == 'raised'){
            $tp = token_price($stage->soldout, $in_currency) > 0 ? token_price($stage->soldout, $in_currency) : 0;
            $data = ($in_currency == 'token' ? to_num_token($stage->soldout) : to_num($tp, 'auto')).' '. $cur;
        }elseif($type == 'softtoken'){
            $data = ($in_currency == 'token' ? to_num_token($stage->soft_cap) : to_num(token_price($stage->soft_cap, $in_currency), 'auto')).' '. $cur;
        }elseif($type == 'hardtoken'){
            $data = ($in_currency == 'token' ? to_num_token($stage->hard_cap) : to_num(token_price($stage->hard_cap, $in_currency), 'auto')).' '. $cur;
        }
        return $data;
    }
}

/* @function explode_user_for_demo()  @version v1.0  @since 1.0 */
if (!function_exists('explode_user_for_demo')) {
    function explode_user_for_demo($data, $user_type) {
       if($user_type == 'demo'){
            $data = substr($data, 0,3).'...'.substr($data, -3);
       }

       return $data;
    }
}

/* @function get_lang()  @version v1.0.1  @since 1.1.3 */
if (!function_exists('get_lang')) {
    function get_lang($get=null) {
        if( application_installed(true) && Schema::hasTable('languages') ){
            $actived_lang = Language::where('status', 1)->get(['name', 'label', 'short', 'code']);
            $languages = [];
            if($actived_lang) {
                foreach ($actived_lang as $lang) {
                    $languages[$lang->code] = (gws('languages_show_as', 'code')=='code') ? $lang->short : $lang->label;
                }
            }
            if(empty($get)) {
                return array_keys($languages);
            } elseif ($get=='labels'||$get=='label'||$get=='short') {
                return $languages;
            } elseif (isset($languages[$get])) {
                return $languages[$get];
            }
        }
        return false;
    }
}

/* @function is_lang_switch()  @version v1.1  @since 1.0.2 */
if (!function_exists('is_lang_switch')) {
    function is_lang_switch($where=null) {
        $switcher = config('icoapp.show_languages_switcher');
        return ($switcher) ? true : false;
    }
}

/* @function available_lang()  @version v1.0  @since 1.1.0 */
if (!function_exists('available_lang')) {
    function available_lang($lang=null, $out='join') {
        $get_langs = config('icoapp.supported_languages');
        if (empty($lang)) {
            $return = ($out=='array') ? $get_langs : strtoupper(join(', ', $get_langs));
        } else {
            $return = (isset($get_langs[$lang])) ? true : false;
        }

        return $return;
    }
}

/* @function is_show_social()  @version v1.0  @since 1.0.2 */
if (!function_exists('is_show_social')) {
    function is_show_social($where=null) {
        $return = false;
        $social = json_decode(get_setting('site_social_links', []));

        $is_exist = UserPanel::social_links('exists');

        $onsite = (isset($social->onsite) && $social->onsite && $is_exist==true) ? true : false; 
        $onlogin = (isset($social->onlogin) && $social->onlogin && $is_exist==true) ? true : false;

        if ($where=='site') {
            $return = $onsite;
        } elseif ($where=='login') {
            $return = $onlogin;
        }

        return $return;
    }
}

/* @function site_copyright()  @version v1.2  @since 1.0.2 */
if (!function_exists('site_copyrights')) {
    function site_copyrights() {
        $is_env = (env_file(3, 1) && !empty(env_file('p')));
        $year = '&copy; '.date('Y '); 
        $app_info = app_info('name').' v'.app_info('version');
        $copyright = $year.site_info('name') . '. ' . gws('site_copyright');

        $copyright = (is_admin() && !is_2fa_lock() && $is_env) ? $year.$app_info.'. All Rights Reserved. <br class="d-block d-md-none">Application Developed by <a href="https://softnio.com/" target="_blank">Softnio</a>.' : $copyright;

        return $copyright;
    }
}


/* @function style_theme()  @version v1.2  @since 1.0.2 */
if (!function_exists('style_theme')) {
    function style_theme($panel='base', $ver=true) {
        $to_extend  = nio_feature();
        $a_sheet = ($to_extend) ? gws('theme_admin', 'style') : 'style';
        $u_sheet = gws('theme_user','style');
        $admin_color = '#7D70FC';
        
        if ($a_sheet=='style-green' || $a_sheet=='style-watermelon') {
            $admin_color = '#8eff8b';
        } elseif($a_sheet=='style-coral') {
            $admin_color = '#f35151';
        } elseif($a_sheet=='style-gold') {
            $admin_color = '#ffc034';
        } elseif($a_sheet=='style-tangerine') {
            $admin_color = '#ff812d';
        }

        $stylesheets = [
            'vendor' => 'assets/css/vendor.bundle.css',
            'base' => 'assets/css/style.css',
            'admin' => 'assets/css/'.$a_sheet.'.css',
            'admin-color' => $admin_color,
            'user' => 'assets/css/'.$u_sheet.'.css',
            'custom' => 'css/custom.css',
        ];

        $style = (isset($stylesheets[$panel])) ? $stylesheets[$panel] : $stylesheets['base'];
        return ($ver) ? $style.css_js_ver() : $style;
    }
}

/* @function theme_color()  @version v1.0  @since 1.1.1 */
if (!function_exists('theme_color')) {
    function theme_color($out='base', $name=null, $theme='admin') {
        $style = (!empty($name)) ? str_replace('style-', '', $name) : 'default';
        $defaults = str_replace('style-', '', gws('theme_'.$theme, 'style'));

        $preset = [
            "style" => ['base' => "#7668fe", 'text'=> '#495463', 'heading'=> '#253992'],
            "default" => ['base' => "#7668fe", 'text'=> '#495463', 'heading'=> '#253992'],
            "blue" => ['base' => "#2c80ff", 'text'=> '#495463', 'heading'=> '#253992'],
            "green" => ['base' => "#21a184", 'text'=> '#a6a8ad', 'heading'=> '#5f6569'],
            "charcoal" => ['base' => "#455e84", 'text'=> '#a6a8ad', 'heading'=> '#5f6569'],
            "coral" => ['base' => "#ce2e2e", 'text'=> '#a6a8ad', 'heading'=> '#5f6569'],
            "gold" => ['base' => "#d8990e", 'text'=> '#a6a8ad', 'heading'=> '#5f6569'],
            "tangerine" => ['base' => "#ff812d", 'text'=> '#a6a8ad', 'heading'=> '#5f6569'],
            "watermelon" => ['base' => "#04a919", 'text'=> '#a6a8ad', 'heading'=> '#5f6569']
        ];

        $color = (empty($name)) ? $defaults : $style;
        $output = (!empty($out)) ? $preset[$color][$out] : $preset[$color];
        return (isset($output) && !empty($output)) ? $output : '';
    }
}


/* @function get_meta()  @version v1.0  @since 1.0.3 */
if (!function_exists('get_meta')) {
    function get_meta($data=null, $key=null) {
        $meta = is_json($data) ? json_decode($data) : false;

        if (!empty($key)) {
            return ( (isset($meta->$key) && !empty($meta->$key)) ? $meta->$key : false );
        }
        return false;
    }
}

/* @function get_tnx()  @version v1.0  @since 1.0.3 */
if (!function_exists('get_tnx')) {
    function get_tnx($tnx, $out=null) {
        $get_tnx = (!empty($tnx)) ? Transaction::where('tnx_id', $tnx)->first() : false;

        if (!empty($out)) {
            return ( (isset($get_tnx->$out) && !empty($get_tnx->$out)) ? $get_tnx->$out : false );
        }
        return $get_tnx;
    }
}

/* @function get_tnx_id()  @version v1.0  @since 1.0.3 */
if (!function_exists('get_tnx_id')) {
    function get_tnx_id($tnx) {
        $tnx_id = (is_json($tnx) ? get_meta($tnx, 'tnx_id') : $tnx);
        $iid = get_tnx($tnx_id, 'id');
        return $iid;
    }
}

/* @function getApiSecret()()  @version v1.0 */
if (!function_exists('getApiSecret()')) {
    function getApiSecret($name=null) {
        if( $name == 'secret' ){
            return get_setting('site_api_secret', str_random(16));
        }
        return get_setting('site_api_key'); 
    }
}

/* @function api_route()  @version v1.0  @since 1.0.6 */
if (!function_exists('api_route')) {
    function api_route($name='') {
        $url = route('api.'.$name, ['secret' => getApiSecret('key')]); 
        return $url;
    }
}

/* @function currency_join()  @version v1.0  @since 1.1.0 */
if (!function_exists('currency_join')) {
    function currency_join($curs=null) {
        if (empty($curs))  return false;

        $supported = (is_array($curs)) ? join(", ", $curs) : $curs;
        return $supported;
    }
}


/* @function qs_filter()  @version v1.0  @since 1.1.0 */
if (!function_exists('qs_filter')) {
    function qs_filter($to_be_null = '') {
        $query = request()->all();
        if($to_be_null != null && isset($query[$to_be_null])){
            unset($query[$to_be_null]);
        }
        return (count($query) > 1 ? $query : []);
    }
}

/* @function qs_url()  @version v1.0  @since 1.1.0 */
if (!function_exists('qs_url')) {
    function qs_url( $qs = array(), $path = null, $secure = null)
    {
        $url = $path ?? url()->to(request()->path(), $secure);
        if (count($qs)){
            foreach($qs as $key => $value){
                $qs[$key] = sprintf('%s=%s',$key, urlencode($value));
            }
            $url = sprintf('%s?%s', $url, implode('&', $qs));
        }
        return $url;
    }
}

/* @function get_user()  @version v1.0  @since 1.1.0 */
if (!function_exists('get_user')) {
    function get_user($id, $out=null)
    {
        $get_user = User::whereNotIn('status', ['deleted'])->find($id);
        $return = (!empty($get_user) ? $get_user : false);

        if (!empty($out) && !empty($get_user)) {
            $return = (isset($get_user->$out) ? $get_user->$out : false);
        }

        return $return;
    }
}

//* @function get_admin()  @version v1.0  @since 1.1.2 */
if (!function_exists('get_admin')) {
    function get_admin($out=null, $extra = false)
    {
        $user = auth()->user();
        if($user->role == 'admin' ){
            $get_user = $user;
        }else{
            $get_user = User::whereNotIn('status', ['deleted'])
                    ->where('role', 'admin')
                    ->when($extra, function($q) use($extra){
                        return $q->where($extra);
                    })->first();
        }
        $return = (!empty($get_user) ? $get_user : false);

        if (!empty($out) && !empty($get_user)) {
            $return = (isset($get_user->$out) ? $get_user->$out : false);
        }

        return $return;
    }
}

/* @function nio_module()  @version v1.0  @since 1.1.2 */
if (!function_exists('nio_module')) {
    function nio_module($name = null)
    {
        return new App\Helpers\NioModule();
    }
}
/* @function is_module_enable()  @version v1.0  @since 1.1.2 */
if (!function_exists('is_module_enable')) {
    function is_module_enable($name)
    {
        $name = strtolower($name);
        if($name == 'token') return (get_setting("nio_{$name}_module", 'disable') == 'enable');
        return (nio_module()->has($name) && get_setting("nio_{$name}_module", 'disable') == 'enable');
    }
}

/* @function has_route()  @version v1.0  @since 1.1.2 */
if (!function_exists('has_route')) {
    function has_route($name)
    {
        return Route::has($name);
    }
}

/* @function tnx_meta()  @version v1.0  @since 1.1.2 */
if (!function_exists('tnx_meta')) {
    function tnx_meta($tnx, $key, $out)
    {
        $data = json_decode($tnx->$key);
        if(empty($out)) {
            return ($data) ? $data : false;
        } else {
            return isset($data->$out) ? $data->$out : false;
        }
    }
}

/* @function cur_meta()  @version v1.0  @since 1.1.2 */
if (!function_exists('cur_meta')) {
    function cur_meta($tnx, $out=null)
    {
        return tnx_meta($tnx, 'currency_data', $out);
    }
}

/* @function pay_meta()  @version v1.0  @since 1.1.2 */
if (!function_exists('pay_meta')) {
    function pay_meta($tnx, $out=null)
    {
        return tnx_meta($tnx, 'pay_data', $out);
    }
}

/* @function recaptcha()  @version v1.0  @since 1.1.4 */
if (!function_exists('recaptcha')) {
    function recaptcha($out=null)
    {
        $sitekey    = gws('recaptcha_site_key');
        $secret     = gws('recaptcha_secret_key');

        $has_key    = (!empty($sitekey) && !empty($secret)) ? true : false;
        if($has_key) {
            if(empty($out)) {
                return $has_key;
            } elseif($out=='site') {
                return $sitekey;
            } elseif ($out=='secret') {
                return $secret;
            }
        }
        return false;
    }
}