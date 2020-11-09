<?php

namespace App\Http\Middleware;

use Closure;

class Language
{
    public function handle($request, Closure $next)
    {

        $locale = $request->segment(1);
        if (check_language($locale) == null) {
            $locale = '';
        }

        if (empty($locale) && auth()->check()) {
            $preference = auth()->user()->preference;
            if (!empty($preference) && check_language($preference->default_language)) {
                $locale = $preference->default_language;
            }
        }
        set_language($locale, settings('lang'));

        has_permission($request->route()->getName(), null, false);

        return $next($request);
    }
}
