<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status === 'banned') {
            // Allow access to logout route to prevent infinite redirect
            if ($request->routeIs('logout')) {
                return $next($request);
            }
            // If the user is banned and not trying to log out, redirect to a banned page
            if (! $request->routeIs('banned')) {
                return redirect()->route('banned');
            }
        }
        return $next($request);
    }
}
