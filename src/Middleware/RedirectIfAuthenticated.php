<?php

namespace LiveCMS\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class RedirectIfAuthenticated
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $instance = null)
    {
        $guard = config('livecms.guard.name');
        if ($instance === null || $instance == LC_CurrentInstance()) {
            if ($this->auth->guard($guard)->check()) {
                return redirect()->route(LC_BaseRoute().'.index');
            }
        }

        return $next($request);
    }
}
