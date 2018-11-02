<?php

namespace Blog\Http\Middleware;

use Closure;

class Locale
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
        $locale = substr($request->path(), 0, 2);
        if (in_array($locale, [
            'en',
            'lv',
        ])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
