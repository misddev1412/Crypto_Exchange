<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\UnauthorizedException;

class Permission
{
    public function handle($request, Closure $next)
    {
        $permission = has_permission($request->route()->getName(), null, false);
        if ($permission === true) {
            return $next($request);
        }
        throw new UnauthorizedException($permission, 401);
    }
}
