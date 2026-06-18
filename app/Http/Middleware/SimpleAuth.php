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

        // Cek user masih aktif
        $authUser = $request->session()->get('auth_user');
        $user = \App\Models\User::find($authUser['id'] ?? null);
        if (! $user || ! $user->is_active) {
            $request->session()->forget('auth_user');
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.',
            ]);
        }

        return $next($request);
    }
}
