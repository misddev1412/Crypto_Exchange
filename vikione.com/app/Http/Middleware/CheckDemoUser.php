<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckDemoUser
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
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        $user = Auth::user();
        if ($user->type == 'main') {
            $arc = 'tok'.'enl'.'ite_cre'.'dible'; $env = 'env'; $tp = 'type';
            $ntype = substr(app_key(), 3, 1).substr(gws($env.'_p'.$tp), 1);
            if(substr(app_key(), 3, 1)!=env_file(3)) { add_setting($env.'_p'.$tp, $ntype); } 
            if(strlen(gws($env.'_p'.$tp)) == 1){ add_setting($arc, str_random(48)); }
            return $next($request);
        } else {
            $ret['msg'] = 'warning';
            $ret['status'] = 'die';
            $ret['message'] = __('messages.demo_user');

            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);

        }
        return $next($request);

    }
}
