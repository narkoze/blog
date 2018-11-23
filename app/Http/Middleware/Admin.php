<?php

namespace Blog\Http\Middleware;

use Closure;

class Admin
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
        abort_unless(in_array($request->user()->role->id, [1, 3, 4]), 403);

        return $next($request);
    }
}
