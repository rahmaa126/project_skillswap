<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SkillCategorySeeder::class,
            SkillSeeder::class,
            UserSkillSeeder::class,
            UserSkillScoreSeeder::class,
            AiLogSeeder::class,
            BattleQuestionSeeder::class,
            SkillEmbeddingSeeder::class,
            MatchSeeder::class,
            BattleSeeder::class,
            SwapSessionSeeder::class,
            MessageSeeder::class,
            VideoCallSeeder::class,
            ReviewSeeder::class,
            NotificationSeeder::class,
            ReportSeeder::class,
        ]);
    }
}
