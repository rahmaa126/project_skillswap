<?php

namespace Database\Seeders;

use App\Models\AiLog;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class AiLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $budi = User::where('email', 'budi@skillswap.test')->first();
        $laravel = Skill::where('name', 'Laravel Framework')->first();
        $vue = Skill::where('name', 'Vue.js')->first();

        $logs = [
            [
                'feature' => 'question_generation',
                'user_id' => $admin?->id,
                'skill_id' => $laravel?->id,
                'model' => 'gpt-4o-mini',
                'prompt_tokens' => 450,
                'completion_tokens' => 380,
                'status' => 'success',
                'error_message' => null,
                'created_at' => now()->subDays(2),
            ],
            [
                'feature' => 'question_generation',
                'user_id' => $admin?->id,
                'skill_id' => $vue?->id,
                'model' => 'gpt-4o-mini',
                'prompt_tokens' => 420,
                'completion_tokens' => 350,
                'status' => 'success',
                'error_message' => null,
                'created_at' => now()->subDays(2),
            ],
            [
                'feature' => 'matching',
                'user_id' => $budi?->id,
                'skill_id' => $laravel?->id,
                'model' => 'text-embedding-3-small',
                'prompt_tokens' => 120,
                'completion_tokens' => 0,
                'status' => 'success',
                'error_message' => null,
                'created_at' => now()->subDay(),
            ],
        ];

        foreach ($logs as $log) {
            AiLog::create($log);
        }
    }
}
