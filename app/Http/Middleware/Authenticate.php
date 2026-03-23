<?php

// namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;
// use Illuminate\Http\Request;

// class Authenticate extends Middleware
// {
//     /**
//      * Get the path the user should be redirected to when they are not authenticated.
//      */
//     protected function redirectTo(Request $request): ?string
//     {
//         return $request->expectsJson() ? null : route('auth#loginPage');
//     }
// }

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    // public function handle(Request $request, Closure $next, ...$guards)
    // {
    //     try {
    //         return $next($request);
    //     } catch (AuthenticationException $exception) {
    //         return $this->unauthenticated($request, $exception);
    //     }
    // }

    // public function unauthenticated(Request $request, AuthenticationException $exception)
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json(['message' => 'Unauthenticated.'], 401);
    //     }

    //     // Default behavior
    //     return redirect()->route('login');
    // }

     /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {

        $guard = $request->expectsJson() ? 'sanctum' : 'web';

        if (Auth::guard($guard)->check()) {
            return $next($request);
        }


        // For JSON requests, return JSON response
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // For web requests, redirect to login page
        return redirect()->route('auth#loginPage');
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Only used if you let the parent class handle redirection
        return $request->expectsJson() ? null : route('auth#loginPage');
    }
}
