<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class LocalPermission
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
        $token = $request->header('local_token') ?? null;
        if ($token == 'dC38Xaq0YO03A3fRbaitx78zmYhvIPSjsOjVB4VGpfOtNLar37gPphE1tlfJIZxs') {
            return $next($request);
        }
        throw new UnauthorizedException(ROUTE_REDIRECT_TO_UNAUTHORIZED, 401);
    }
}
