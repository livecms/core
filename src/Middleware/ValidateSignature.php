<?php

namespace LiveCMS\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidateSignature
{
    protected function hasValidSignature($request)
    {
        $original = rtrim($request->url().'?'.http_build_query(
            Arr::except($request->query(), 'signature'),
        null, '&', PHP_QUERY_RFC3986), '?');
        $expires = Arr::get($request->query(), 'expires');
        $signature = hash_hmac('sha256', $original, config('app.key'));
        return  hash_equals($signature, $request->query('signature', '')) &&
               ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next)
    {
        if ($this->hasValidSignature($request)) {
            return $next($request);
        }
        throw new HttpException(419, 'Invalid signature.');
    }
}