<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->session()->get('auth_user');

        if (! $user || ($user['role'] ?? null) !== $role) {
            $route = ($user['role'] ?? null) === 'pimpinan' ? 'pimpinan.dashboard' : 'admin.dashboard';

            return redirect()->route($route);
        }

        return $next($request);
    }
}
