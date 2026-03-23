<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Auth;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        // If user is not logged in and trying to logout, skip CSRF check
        if (!Auth::check() && $request->route()->named('logout')) {
            $this->except[] = route('logout');
        }

        return parent::handle($request, $next);
    }
}
