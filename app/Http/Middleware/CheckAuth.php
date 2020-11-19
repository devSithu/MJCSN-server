<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        if ($request->user($guard)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => false]);
        }
        return redirect()->route('RegisterController#userLogin');
    }
}
