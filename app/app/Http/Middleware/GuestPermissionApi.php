<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestPermissionApi
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = Auth::user();
        if (!$auth) {
            return $next($request);
        }
        return response()->json([401 => api_permission(ROUTE_REDIRECT_TO_UNAUTHORIZED)]);
    }
}
