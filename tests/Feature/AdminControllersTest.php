<?php

namespace Tests\Feature;

use App\Models\SkillCategory;
use App\Models\User;
use Tests\TestCase;

class AdminControllersTest extends TestCase
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

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);
        }
    }

    public function test_admin_dashboard_connects_to_models(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('SkillSwap Admin Dashboard');
        $response->assertSee('Active Users');
        $response->assertSee('Total Skills');
    }

    public function test_admin_users_controller_index_and_show(): void
    {
        $user = User::first();
        $this->assertNotNull($user);

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Users Management');
        $response->assertSee($user->name);

        $showResponse = $this->get(route('admin.users.show', $user));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($user->email);
    }

    public function test_admin_categories_controller_index_and_store(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Skill Categories');

        $testName = 'Test Cat '.uniqid();
        $storeResponse = $this->post(route('admin.categories.store'), [
            'name' => $testName,
        ]);
        $storeResponse->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('skill_categories', ['name' => $testName]);

        // Cleanup
        SkillCategory::where('name', $testName)->delete();
    }

    public function test_admin_skills_controller_index_and_create(): void
    {
        $response = $this->get(route('admin.skills.index'));
        $response->assertStatus(200);
        $response->assertSee('Skills Management');

        $createResponse = $this->get(route('admin.skills.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Add New Skill');
    }

    public function test_admin_swap_sessions_controller_index(): void
    {
        $response = $this->get(route('admin.swap-sessions.index'));
        $response->assertStatus(200);
        $response->assertSee('Swap Sessions Management');
    }

    public function test_admin_battles_controller_index(): void
    {
        $response = $this->get(route('admin.battles.index'));
        $response->assertStatus(200);
        $response->assertSee('Skill Battles Management');
    }

    public function test_admin_matches_controller_index(): void
    {
        $response = $this->get(route('admin.matches.index'));
        $response->assertStatus(200);
        $response->assertSee('Skill Matches');
    }

    public function test_admin_reviews_controller_index(): void
    {
        $response = $this->get(route('admin.reviews.index'));
        $response->assertStatus(200);
        $response->assertSee('Reviews & Ratings');
    }

    public function test_admin_reports_controller_index(): void
    {
        $response = $this->get(route('admin.reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Reports & Disputes');
    }

    public function test_admin_ai_logs_controller_index(): void
    {
        $response = $this->get(route('admin.ai-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('AI Engine Logs');
    }
}
