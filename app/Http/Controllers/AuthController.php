<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): RedirectResponse|View
    {
        if ($request->session()->has('auth_user')) {
            return redirect()->route($this->dashboardRoute($request->session()->get('auth_user.role')));
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Query dari database, bukan hardcode
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password tidak sesuai.']);
        }

        if (! $user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.']);
        }

        $request->session()->regenerate();
        $request->session()->put('auth_user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'role_label' => match($user->role) {
                'pimpinan' => 'Pimpinan',
                'karyawan' => 'Karyawan/Mekanik',
                default => 'Admin',
            },
        ]);

        return redirect()->route($this->dashboardRoute($user->role));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('auth_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    private function dashboardRoute(?string $role): string
    {
        return match($role) {
            'pimpinan' => 'pimpinan.dashboard',
            'karyawan' => 'karyawan.dashboard',
            default => 'admin.dashboard',
        };
    }
}
