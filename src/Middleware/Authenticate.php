<?php

namespace LiveCMS\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Str;

class Authenticate
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

    protected function emailVerificationRoutes($instance)
    {
        return ;
    }

    protected function isNotVerified($request, $instance, $auth)
    {
        $routeName = $request->route()->getName();
        $verificationRoute = 'livecms.'.$instance.'.verification.';
        return Str::startsWith($routeName, $verificationRoute) && LC_CurrentConfig('verify_email') && ! $auth->user()->hasVerifiedEmail();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$guard
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, $instance = null)
    {
        $guard = LC_GuardName();
        if ($instance === null || $instance == LC_CurrentInstance()) {
            $auth = $this->auth->guard($guard);
            if ($auth->check()) {

                // check verification
                if ($this->isNotVerified($request, $instance, $auth)) {
                    return $request->expectsJson()
                            ? abort(403, 'Your email address is not verified.')
                            : redirect()->intended(LC_Route('verification.notice'));

                }

                $this->auth->shouldUse($guard);
                return $next($request);
            }
        }

        return $request->expectsJson()
                    ? response()->json(['message' => 'You must login first'], 401)
                    : redirect()->guest(LC_Route('login'));
    }

}
