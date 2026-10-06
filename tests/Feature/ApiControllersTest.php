<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiControllersTest extends TestCase
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

    public function test_api_users_endpoint(): void
    {
        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email', 'role', 'is_active', 'profile'],
            ],
        ]);
    }

    public function test_api_categories_endpoint(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'skills_count'],
            ],
        ]);
    }

    public function test_api_skills_endpoint(): void
    {
        $response = $this->getJson('/api/v1/skills');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'min_score', 'is_active'],
            ],
        ]);
    }

    public function test_api_swap_sessions_endpoint(): void
    {
        $response = $this->getJson('/api/v1/swap-sessions');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function test_api_battles_endpoint(): void
    {
        $response = $this->getJson('/api/v1/battles');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function test_api_matches_endpoint(): void
    {
        $response = $this->getJson('/api/v1/matches');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function test_api_reviews_endpoint(): void
    {
        $response = $this->getJson('/api/v1/reviews');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function test_api_reports_endpoint(): void
    {
        $response = $this->getJson('/api/v1/reports');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function test_api_ai_logs_endpoint(): void
    {
        $response = $this->getJson('/api/v1/ai-logs');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
    }
}
