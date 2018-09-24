<?php

namespace LiveCMS\Middleware;

use Closure;
use Illuminate\Support\Str;

class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $route = Str::replaceFirst(LIVECMS.'.', '', $request->route()->getName());
        $parse = explode('.', $route);

        $instance = $parse[0];
        config(['livecms.current' => $instance]);

        $baseRoute = LIVECMS.'.'.$instance;
        config(['livecms.base_route' => $baseRoute]);

        return $next($request);
    }
}
