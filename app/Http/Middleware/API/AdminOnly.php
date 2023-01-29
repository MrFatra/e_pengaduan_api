<?php

namespace App\Http\Middleware\API;

use App\Helpers\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;

class AdminOnly
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
        if ($request->user()->level == 'admin') {
            return $next($request);
        } else {
            return ResponseFormatter::error('You\'re not an admin...', 'LEVEL NOT PASSED');
        }
    }
}
