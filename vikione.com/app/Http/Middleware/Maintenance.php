<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\IcoHandler;
use Illuminate\Support\Facades\Schema;

class Maintenance
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        if( $request->ajax() && isset($request->app) ){ 
            $handle = new IcoHandler();
            if( $request->current !== route('admin.tokenlite') ){
                $response = ['thanks' => $handle->check_body()];
            }else{ $response = true; }
            return response()->json($response);
        }
        if( application_installed(true) == false ) return $next($request);
        if (application_installed(true) && get_setting('site_maintenance') == 1 && (!$request->is('/') && !$request->is('admin') && !$request->is('admin/*') && !$request->is('/log-out'))) {
            return response()->view('errors.maintenance', [], 500);
        }
        return $next($request);
    }
}
