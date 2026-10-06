<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Tests\TestCase;

class UserPortalTest extends TestCase
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

    public function test_user_can_view_portal_dashboard(): void
    {
        $user = User::where('role', 'user')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/portal');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Pengguna');
        $response->assertSee($user->name);
        $response->assertSee('Role: Pengguna');
    }

    public function test_user_can_view_skills_catalog(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/portal/skills');

        $response->assertStatus(200);
        $response->assertSee('Jelajah Katalog Keahlian');
    }

    public function test_user_can_view_my_skills_page(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/portal/my-skills');

        $response->assertStatus(200);
        $response->assertSee('Kelola Keahlian Saya');
    }

    public function test_user_can_add_skill_and_remove(): void
    {
        $user = User::where('role', 'user')->first();
        $skill = Skill::first();
        $this->assertNotNull($skill);

        // Delete if already exists
        UserSkill::where('user_id', $user->id)->where('skill_id', $skill->id)->delete();

        $response = $this->actingAs($user)->post('/portal/my-skills', [
            'skill_id' => $skill->id,
            'type' => 'offered',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_skills', [
            'user_id' => $user->id,
            'skill_id' => $skill->id,
            'type' => 'offered',
        ]);

        $userSkill = UserSkill::where('user_id', $user->id)->where('skill_id', $skill->id)->first();
        $deleteResponse = $this->actingAs($user)->delete("/portal/my-skills/{$userSkill->id}");
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('user_skills', ['id' => $userSkill->id]);
    }

    public function test_user_can_view_swaps_page(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/portal/swaps');

        $response->assertStatus(200);
        $response->assertSee('Sesi Pertukaran Keahlian');
    }

    public function test_user_can_view_battles_page(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/portal/battles');

        $response->assertStatus(200);
        $response->assertSee('Skill Battles Arena');
    }

    public function test_user_can_view_and_update_profile(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/portal/profile');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil Saya');

        $newName = 'Budi Santoso Updated';
        $updateResponse = $this->actingAs($user)->put('/portal/profile', [
            'name' => $newName,
            'email' => $user->email,
            'city' => 'Jakarta Selatan',
            'phone' => '081234567890',
            'bio' => 'Bio uji coba pengguna',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $newName]);

        // Revert name
        $user->update(['name' => 'Budi Santoso']);
    }
}
