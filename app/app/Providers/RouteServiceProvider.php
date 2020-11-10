<?php

namespace App\Providers;

use App\Models\Core\User;
use App\Models\Deposit\WalletDeposit;
use App\Models\Wallet\Wallet;
use App\Models\Withdrawal\WalletWithdrawal;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Uuid;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = null;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::pattern('id', '[0-9]+');
        Route::pattern('admin_setting_type', implode('|', array_keys(config('appsettings.settings'))));
        Route::pattern('menu_slug', implode('|', config('navigation.registered_place')));

        Route::bind('wallet', function ($value, $route) {
            if (Uuid::isValid($value)) {
                return Wallet::where('id', $value)
                    ->when($route->parameter('user'), function ($query) use ($route) {
                        $query->where('user_id', $route->parameter('user'));
                    })->firstOrFail();
            } else if (Auth::check()) {
                return Wallet::where('user_id', Auth::id())
                    ->where('symbol', $value)
                    ->withoutSystemWallet()
                    ->when($route->parameter('user'), function ($query) use ($route) {
                        $query->where('user_id', $route->parameter('user'));
                    })->firstOrFail();
            }
            return null;
        });

        Route::model('username', User::class);
        Route::model('deposit', WalletDeposit::class);
        Route::model('bank-deposit', WalletDeposit::class);
        Route::model('withdrawal', WalletWithdrawal::class);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
//        $this->mapPermissionApiRoutes();
//        $this->mapGuestPermissionApiRoutes();
//        $this->mapVerificationPermissionApiRoutes();

        $this->mapPermissionRoutes();
        $this->mapGuestPermissionRoutes();
        $this->mapVerificationPermissionRoutes();
        $this->exchangeRoute();
        $this->mapWebRoutes();
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
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
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
        $filename = $middleware = 'web';
        $middleware = ['web', 'menuable'];
        $prefix = $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace, 'routes/');
    }

    protected function routeMap($filename, $middleware, $prefix = null, $namespace = null, $path = 'routes/groups/')
    {
        $locale = strtolower($this->app->request->segment(1));
        $language = check_language($locale);
        if ($language != null && $prefix != null) {
            $prefix = $language . '/' . $prefix;
        } elseif ($language != null) {
            $prefix = $language;
        }

        if ($namespace != null) {
            $namespace = $this->namespace . '\\' . ucfirst($namespace);
        } else {
            $namespace = $this->namespace;
        }

        Route::prefix($prefix)
            ->middleware($middleware)
            ->namespace($namespace)
            ->group(base_path($path . $filename . '.php'));
    }

    protected function mapPermissionRoutes()
    {
        $filename = 'permission';
        $middleware = ['web', 'auth', '2fa', 'permission'];
        $prefix = $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace);
    }

    protected function mapGuestPermissionRoutes()
    {
        $filename = 'guest_permission';
        $middleware = ['web', 'guest.permission'];
        $prefix = $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace);
    }

    // API Starts here

    protected function mapVerificationPermissionRoutes()
    {
        $filename = 'verification_permission';
        $middleware = ['web', 'verification.permission'];
        $prefix = $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace);
    }

    private function exchangeRoute()
    {
        $filename = 'exchange';
        $middleware = ['web'];
        $prefix = 'exchange';
        $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace);
    }

    protected function mapPermissionApiRoutes()
    {
        $filename = 'permission_api';
        $middleware = ['api', 'auth:api', 'permission.api'];
        $prefix = $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace);
    }

    protected function mapGuestPermissionApiRoutes()
    {
        $filename = 'guest_permission_api';
        $middleware = ['api', 'guest.permission.api'];
        $prefix = $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace);
    }

    protected function mapVerificationPermissionApiRoutes()
    {
        $filename = 'verification_permission_api';
        $middleware = ['api', 'verification.permission.api'];
        $prefix = $namespace = null;
        $this->routeMap($filename, $middleware, $prefix, $namespace);
    }
}
