<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_admin_gudang_can_login_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@gudang.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertSame('admin_gudang', session('auth_user.role'));
    }

    public function test_pimpinan_can_login_to_pimpinan_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'pimpinan@perusahaan.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/pimpinan/dashboard');
        $this->assertSame('pimpinan', session('auth_user.role'));
    }
}
