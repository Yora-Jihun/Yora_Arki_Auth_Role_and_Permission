<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordResetSessionValid
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->expectsJson() && session()->missing('password_reset_email')) {
            return redirect()->route('forgot.password');
        }

        return $next($request);
    }
}
