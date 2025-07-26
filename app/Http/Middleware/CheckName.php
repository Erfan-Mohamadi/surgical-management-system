<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckName
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $name = 'ali'): Response
    {
        if (request('name', $name) === 'john') {
            abort(403, 'ورود شما به این صفحه مجاز نمی باشد!');
        }

        return $next($request);
    }
}
