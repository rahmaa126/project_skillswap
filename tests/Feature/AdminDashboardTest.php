<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    /**
     * Test admin dashboard page can be rendered.
     */
    public function test_admin_dashboard_can_be_rendered(): void
    {
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'db_skillswap',
            'database.connections.mysql.username' => 'root',
            'database.connections.mysql.password' => '',
        ]);

        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('SkillSwap Admin Dashboard');
        $response->assertSee('adminlte/css/adminlte.min.css');
        $response->assertSee('adminlte/js/adminlte.min.js');
    }

    /**
     * Test admin login page can be rendered.
     */
    public function test_admin_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('SkillSwap');
        $response->assertSee('Masuk ke Akun Anda');
        $response->assertSee('adminlte/css/adminlte.min.css');
    }

    /**
     * Test AdminLTE core css file exists in public directory.
     */
    public function test_adminlte_core_assets_exist(): void
    {
        $this->assertFileExists(public_path('adminlte/css/adminlte.min.css'));
        $this->assertFileExists(public_path('adminlte/js/adminlte.min.js'));
        $this->assertFileExists(public_path('adminlte/assets/img/AdminLTELogo.png'));
    }
}
