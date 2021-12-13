<?php

namespace App\Http\Middleware;

use App\Responders\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class MonoSecure
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
        if (is_null($request->header("mono-webhook-secret")) || $request->header("mono-webhook-secret") != config("services.mono.webhook")) {
            return ApiResponse::unauthorized();
        }

        return $next($request);
    }
}
