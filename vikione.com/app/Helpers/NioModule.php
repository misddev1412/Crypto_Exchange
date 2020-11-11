<?php 

namespace App\Helpers;

/**
 * NioModulw Helper Class
 *
 * @package TokenLite
 * @version 1.0
 * @since 1.1.1
 */

use View;

class NioModule
{
	public $modules;
	function __construct()
	{
		// $this->modules = $this->loadModule();
	}

	public function view($path, $data=[])
    {
        View::addNamespace('modules', app_path('NioModules/'));
        return View::make("modules::$path", $data);
    }

	public function loadModule() : array
	{
		$modules = cache()->remember('nio_modules', 86400, function(){
			return $this->getModules();
		});
        $this->modules = $modules;
        return $modules;
	}

	public function getNamespace($name='')
	{
		if($name) {
			return "App\\NioModules\\$name\\";
		}
		return "App\\NioModules";
	}

	public function getModules()
	{
		$path = app_path('NioModules');
		$directories = scandir($path);

		$modules = [];
        foreach ($directories as $dir) {
            if( ! in_array($dir, ['.', '..'])){
                $modules[] = $dir;
            }
        }
        return $modules;
	}

	public function getPath($path='')
	{
		return app_path('NioModules').($path ? DIRECTORY_SEPARATOR.$path : $path);
	}

	public function has($name)
	{
		if(empty($this->modules)){
			$this->modules = $this->loadModule();
		}
		return in_array(ucfirst($name), $this->modules);
	}

	public function user_modules($name=null)
	{
		$umodules = [];
		if($this->has('withdraw') && !empty(config('withdraw.userpanel'))) {
			$umodules['withdraw'] = (object) config('withdraw.userpanel');
		}
		if($this->has('transfers') && !empty(config('transfer.userpanel'))) {
			$umodules['transfers'] = (object) config('transfer.userpanel');
		}
		return ($name && isset($umodules[$name])) ? $umodules[$name] : $umodules;
	}

	public function admin_modules($name=null)
	{
		$amodules = [];
		if($this->has('withdraw') && !empty(config('withdraw.view_setting'))) {
			$amodules['withdraw'] = (object) [
				'view' => config('withdraw.view_setting'),
				'variables' => [
					'settings' => (object) config('withdraw.settings'),
					'currencies' =>  \App\NioModules\Withdraw\WithdrawModule::support_currency('all')
				]
			];
		}
		if($this->has('transfers') && !empty(config('transfer.view_setting'))) {
			$amodules['transfer'] = (object) [
				'view' => config('transfer.view_setting'),
				'variables' => [
					'settings' => (object) config('transfer.settings')
				]
			];
		}
		return ($name && isset($amodules[$name])) ? $amodules[$name] : $amodules;
	}
}
