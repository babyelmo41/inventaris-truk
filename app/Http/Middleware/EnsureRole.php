<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->session()->get('auth_user');

        if (! $user || !in_array($user['role'] ?? null, $roles)) {
            $route = match($user['role'] ?? null) {
                'pimpinan' => 'pimpinan.dashboard',
                'karyawan' => 'karyawan.dashboard',
                default => 'admin.dashboard',
            };

            return redirect()->route($route);
        }

        return $next($request);
    }
}
