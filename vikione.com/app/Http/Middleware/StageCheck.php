<?php

namespace App\Http\Middleware;

use Closure;

class StageCheck
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
        $current_date = date('Y-m-d H:i:s');
        if ($current_date >= active_stage()->start_date && $current_date <= active_stage()->end_date) {
            return $next($request);
        } elseif (active_stage()->start_date >= $current_date && $current_date <= active_stage()->end_date) {
            return $next($request);
        } elseif ($current_date > active_stage()->end_date && active_stage()->soldout > 0) {
            $chk_stg = ['info' => __('messages.stage.completed')];
            return redirect(route('user.home'))->with($chk_stg);
        } else {
            $chk_stg = active_stage()->end_date == def_datetime('datetime_e') ? ['warning' => __('messages.stage.not_started')] : ['warning' => __('messages.stage.expired')];
            return redirect(route('user.home'))->with($chk_stg);
        }
    }
}
