<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RoleAuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'db_skillswap',
            'database.connections.mysql.username' => 'root',
            'database.connections.mysql.password' => '',
        ]);
    }

    public function test_login_page_renders_with_both_roles_options(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Role Admin');
        $response->assertSee('Role Pengguna');
        $response->assertSee('admin@skillswap.test');
        $response->assertSee('budi@skillswap.test');
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@skillswap.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isAdmin());
    }

    public function test_regular_user_login_redirects_to_portal_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'budi@skillswap.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isUser());
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::where('role', 'user')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('SkillSwap Admin Dashboard');
    }

    public function test_admin_can_also_access_user_portal(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/portal');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Pengguna');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/portal');
        $response->assertRedirect(route('login'));

        $adminResponse = $this->get('/admin');
        $adminResponse->assertRedirect(route('login'));
    }

    public function test_new_registration_creates_user_with_role_user(): void
    {
        $email = 'pengguna_baru_'.uniqid().'@skillswap.test';

        $response = $this->post('/register', [
            'name' => 'Pengguna Baru',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city' => 'Bandung',
            'phone' => '081299998888',
        ]);

        $response->assertRedirect(route('portal.dashboard'));

        $newUser = User::where('email', $email)->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('user', $newUser->role);
        $this->assertTrue($newUser->isUser());
        $this->assertFalse($newUser->isAdmin());

        // Cleanup
        $newUser->profile()->delete();
        $newUser->delete();
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
