<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
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
        $user = Auth::user();
        if ($user->role == 'user') {
            $check_dt = \IcoHandler::checkDB();
            if(empty($check_dt)){
                return $next($request);
            } else {
                return response()->view('errors.maintenance');
            }
        } else {
            if (Auth::check() && $user->role == 'admin') {
                return redirect(route('admin.home'));
            } else {
                Auth::logout();
                return redirect(route('login'))->with(['danger'=>'You are not an User!']);
            }
        }
    }
}
