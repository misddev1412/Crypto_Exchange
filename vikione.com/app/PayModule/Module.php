<?php
namespace App\PayModule;

/**
 * Payments Module
 * @version v1.1.0
 * @since v1.0.2
 */
use File;
use Route;
use IcoHandler;
use App\PayModule\ModuleHelper;

class Module
{
    /*
     * Here is the name of module load by default fallback
     */
    private $active_payment_methods = ['Manual', 'Bank', 'Paypal'];

    function __construct()
    {
        if(application_installed(true)){
            $default = [
                'Manual' => array('type' =>'core', 'version' => '1.2.0'),
                'Bank' => array('type' =>'core', 'version' => '1.2.0'),
                'Paypal' => array('type' =>'core', 'version' => '1.2.0')
            ];
            $modules = get_setting('active_payment_modules', json_encode($default));
            $get_modules = json_decode(gws('active_payment_modules'), TRUE);
            $set_modules = (!empty($get_modules) && is_array($get_modules)) ? array_keys($get_modules) : array_keys(json_decode($modules, TRUE));
            $this->active_payment_methods = $set_modules;
        }
    }

    /**
     * Initialize the all Module
     * @version v1.0.0
     * @since v1.0.2
     * @return void
     */
    public function init()
    {
        $this->check_in_db();
    }

    /**
     * Routes of Module
     * @version v1.0.0
     * @since v1.0.2
     * @return void
     */
    public function module_routes()
    {
        foreach ($this->active_payment_methods as $item) {
            $object = $this->getItemInstance($item);
            if (method_exists($object, 'routes')) {
                $object->routes();
            }
        }
    }

    /**
     * Save Payments Setting of Module
     * @version v1.0.0
     * @since v1.0.2
     * @return void
     */
    public function save_module_data($type, $request)
    {
        $object = $this->getItemInstance($type);
        if (method_exists($object, 'save_data') && $this->is_default_load()) {
            return $object->save_data($request);
        }
        if( !$this->is_default_load() ){
            return $object->save_data($request);
        }
        return false;
    }

    /**
     * Show module in AdminPanel
     * @version v1.0.0
     * @since v1.0.2
     * @return HtmlString
     */
    public function module_views($type = null)
    {
        if(! empty($type)){
            $object = $this->getItemInstance($type);
            if (method_exists($object, 'admin_views_details')) {
                return $object->admin_views_details();
            }else{
                return null;
            }
        }
        $all = [];
        foreach ($this->active_payment_methods as $item) {
            $object = $this->getItemInstance($item);
            if (method_exists($object, 'admin_views')) {
                $all[] = $object->admin_views();
            }
        }
        return $all;
    }

    /**
     * Show the module in purchase option
     * @version v1.0.0
     * @since v1.0.2
     * @return void
     */
    public function show_module($currency, $data)
    {
        $methods = $support = [];
        foreach ($this->active_payment_methods as $item) {
            $object = $this->getItemInstance($item);
            $pm = get_pm(strtolower($item), true);
            if(method_exists($object, 'show_action')){
                $act = $object->show_action();
                if(in_array(strtoupper($currency), $act['currency']) && $pm->status == 'active'){
                    $methods[] = $act['html'];
                    $support[$item] = $act['currency'];
                }
            }
        }
        return ModuleHelper::view('views.payment', compact('methods', 'data', 'support'), false);
    }

    /**
     * Show the transaction details in transaction page
     * @version v1.0.0
     * @since v1.0.2
     * @return void
     */
    public function show_details($transaction)
    {
        $object = $this->getItemInstance($transaction->payment_method);
        if(method_exists($object, 'transaction_details')){
            return $object->transaction_details($transaction);
        }else{
            return ModuleHelper::view('views.details', ['transaction' => $transaction, 'details' => true]);
        }
    }

    /**
     * Create the transaction and payment
     * @version v1.0.0
     * @since v1.0.2
     * @return void
     */
    public function make_payment($type, $request)
    {
        $object = $this->getItemInstance($type);
        if(method_exists($object, 'create_transaction')){
            return $object->create_transaction($request);
        }
    }

    /**
     * Check the module in database
     * @version v1.0.0
     * @since v1.0.2
     * @return void
     */
    public function check_in_db()
    {
        foreach ($this->active_payment_methods as $item) {
            $object = $this->getItemInstance($item);
            if (method_exists($object, 'demo_data')) {
                $object->demo_data();
                $this->sync_module($object);
            }
        }
    }

    /**
     * Get the instance of active method
     *
     * @param  string  $name
     * @return Module
     */
    public function email_data($transaction='')
    {
        $object = $this->getItemInstance($transaction->payment_method);
        if(method_exists($object, 'email_details')){
            return $object->email_details($transaction);
        }
        return null;
    }

    /**
     * Get the instance of activation
     *
     * @return boolean
     */
    public function is_default_load()
    {
        $domain = get_site(); $iok = 'ni'.'o_l'.'key'; $cr = 'tok'.'enli'.'te_cr'.'edible';
        return (str_contains(get_setting($iok), _joaat($domain)) && str_contains(gws($cr), _joaat($domain)) );
    }

    /**
     * Get the instance of active method
     *
     * @param  string  $name
     * @version 1.1
     * @since 1.0.2
     * @return Module
     */
    public function getItemInstance($name)
    {
        $instance = null;
        if (!in_array(ucfirst($name), $this->active_payment_methods)) {
            return false;
        }
        
        try {
            $name = ucfirst($name);
            $path = 'App\PayModule'.'\\'.$name.'\\'.$name."Module";
            if( class_exists($path) ){
                $instance = new $path;
            }
        } catch (\Exception $e) {
            $instance = false;
            $message = $e->getMessage();
            info($message);
        }
        return $instance;
    }

    /**
     * Copy modules necessary files to the public directory
     *
     * @version 1.0
     * @since 1.1.0
     * @return void
     */
    public function sync_module($module)
    {
        $path = _public_dir('assets/images/pay-'.$module::SLUG.'.png');
        $source = _module_dir(ucfirst($module::SLUG).'/pay-'.$module::SLUG.'.png');
        if( !file_exists($path) && file_exists($source) ){
            try {
                @copy($source, $path);
            } catch (\Exception $e) {
                info($e->getMessage());
                // session()->flash('warning', 'Please manually copy the module assets to the public directory.');
            }
        }
        $asource = _module_dir(ucfirst($module::SLUG).'/pay-'.$module::SLUG.'-admin.png');
        $apath = _public_dir('assets/images/pay-'.$module::SLUG.'-admin.png');
        if( !file_exists($apath) && file_exists($asource) ){
            try {
                @copy($asource, $apath);
            } catch (\Exception $e) {
                info($e->getMessage());
                // session()->flash('warning', 'Please manually copy the module assets to the public directory.');
            }
        }
    }

    /**
     * Get the enabled modules
     *
     * @version 1.0
     * @since 1.1.0
     * @return array
     */
    public static function load_modules()
    {
        $module_path = app_path('PayModule');
        $ds = DIRECTORY_SEPARATOR;
        $directories = scandir($module_path);
        $modules = [];
        foreach ($directories as $dir) {
            if( ! in_array($dir, ['.', '..'])){
                $path = $module_path.$ds.$dir.$ds.'module.json';
                if( file_exists($path) && is_file($path) && is_dir($module_path.$ds.$dir) ){
                    $file = File::get($path);
                    $item = json_decode($file);
                    if($item->status == true){ //  && ModuleHelper::satisfy_version($item->requires)
                        if($dir == $item->alias) {
                            $modules[$dir] = array('type' => $item->type, 'version' => $item->version);
                        } else {
                            $modules[$item->name] = array('type' => $item->type, 'version' => $item->version);
                        }
                    }
                }
            }
        }
        $all_module = self::array_orderby($modules, 'type', SORT_DESC);
        $default = [
            'Manual' => array('type' =>'core', 'version' => '1.2.0'),
            'Bank' => array('type' =>'core', 'version' => '1.2.0'),
            'Paypal' => array('type' =>'core', 'version' => '1.2.0')
        ];
        $old = gws('active_payment_modules', json_encode($default));
        $old = json_decode($old, true);
        if( is_admin() && (count($old) != count($all_module)) ){
            add_setting('active_payment_modules', json_encode($all_module));
        }
        return $all_module;
    }

    /**
     * array_orderby for serialize 
     *
     * @version 1.0
     * @since 1.1.0
     */
    protected static function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
                }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}
