<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SimpleAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('auth_user')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Silakan login terlebih dahulu.',
            ]);
        }

        return $next($request);
    }
}
