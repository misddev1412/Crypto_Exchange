<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapPayModuleRoutes();

        // $this->mapNioModuleRoutes();  @v1.1.2
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->name('api.')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "payment-module" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapPayModuleRoutes()
    {
        Route::prefix('payment')
             ->name('payment.')
             ->middleware('web')
             ->namespace('App\PayModule')
             ->group(app_path('PayModule/routes.php'));
    }

    /**
     * Define the "module" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapNioModuleRoutes()
    {
        foreach (nio_module()->loadModule() as $module) {
			if( is_dir(app_path('NioModules/'.$module)) && file_exists(app_path('NioModules/'.$module.'/routes.php')) ){
				Route::name('module::'.strtolower($module).'.')
					 // ->prefix('admin')
					 ->middleware('web')
					 ->namespace('App\NioModules\\'.$module)
					 ->group(app_path('NioModules/'.$module.'/routes.php'));
			}
        }
    }
}
