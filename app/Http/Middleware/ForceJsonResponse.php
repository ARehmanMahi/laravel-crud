<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Accept') !== 'application/json' && ($request->is('api/*') || $request->expectsJson())) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
