<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkillScore;
use Illuminate\Database\Seeder;

class UserSkillScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->pluck('id', 'email');
        $skills = Skill::pluck('id', 'name');

        $scores = [
            ['email' => 'budi@skillswap.test', 'skill' => 'Laravel Framework', 'score' => 94],
            ['email' => 'budi@skillswap.test', 'skill' => 'Vue.js', 'score' => 65],

            ['email' => 'siti@skillswap.test', 'skill' => 'Vue.js', 'score' => 91],
            ['email' => 'siti@skillswap.test', 'skill' => 'Laravel Framework', 'score' => 68],

            ['email' => 'reza@skillswap.test', 'skill' => 'Figma UI Design', 'score' => 96],
            ['email' => 'reza@skillswap.test', 'skill' => 'React.js', 'score' => 60],

            ['email' => 'dewi@skillswap.test', 'skill' => 'React.js', 'score' => 92],
            ['email' => 'dewi@skillswap.test', 'skill' => 'Figma UI Design', 'score' => 70],

            ['email' => 'ahmad@skillswap.test', 'skill' => 'Python for Data Analysis', 'score' => 89],
            ['email' => 'ahmad@skillswap.test', 'skill' => 'Prompt Engineering & LLM', 'score' => 62],

            ['email' => 'nadia@skillswap.test', 'skill' => 'Prompt Engineering & LLM', 'score' => 88],
            ['email' => 'nadia@skillswap.test', 'skill' => 'Python for Data Analysis', 'score' => 70],

            ['email' => 'fajar@skillswap.test', 'skill' => 'Flutter', 'score' => 90],
            ['email' => 'fajar@skillswap.test', 'skill' => 'English for Tech Speaking', 'score' => 64],

            ['email' => 'maya@skillswap.test', 'skill' => 'English for Tech Speaking', 'score' => 95],
            ['email' => 'maya@skillswap.test', 'skill' => 'Flutter', 'score' => 60],
        ];

        foreach ($scores as $item) {
            $userId = $users[$item['email']] ?? null;
            $skillId = $skills[$item['skill']] ?? null;

            if ($userId && $skillId) {
                UserSkillScore::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'skill_id' => $skillId,
                    ],
                    [
                        'score' => $item['score'],
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
