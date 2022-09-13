<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddAuthHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->bearerToken()) {
            if ($request->hasCookie(config('auth.cookie_name'))) {
                $token = $request->cookie(config('auth.cookie_name'));
                $request->headers->add(['Authorization' => 'Bearer ' . $token]);
            }
        }

        return $next($request);
    }
}
