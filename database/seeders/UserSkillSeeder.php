<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;

class UserSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->pluck('id', 'email');
        $skills = Skill::pluck('id', 'name');

        $userSkills = [
            // Budi Santoso
            ['email' => 'budi@skillswap.test', 'skill' => 'Laravel Framework', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'budi@skillswap.test', 'skill' => 'Vue.js', 'type' => 'wanted', 'level' => 'beginner'],

            // Siti Rahmawati
            ['email' => 'siti@skillswap.test', 'skill' => 'Vue.js', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'siti@skillswap.test', 'skill' => 'Laravel Framework', 'type' => 'wanted', 'level' => 'intermediate'],

            // Reza Pratama
            ['email' => 'reza@skillswap.test', 'skill' => 'Figma UI Design', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'reza@skillswap.test', 'skill' => 'React.js', 'type' => 'wanted', 'level' => 'beginner'],

            // Dewi Lestari
            ['email' => 'dewi@skillswap.test', 'skill' => 'React.js', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'dewi@skillswap.test', 'skill' => 'Figma UI Design', 'type' => 'wanted', 'level' => 'intermediate'],

            // Ahmad Fauzi
            ['email' => 'ahmad@skillswap.test', 'skill' => 'Python for Data Analysis', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'ahmad@skillswap.test', 'skill' => 'Prompt Engineering & LLM', 'type' => 'wanted', 'level' => 'beginner'],

            // Nadia Putri
            ['email' => 'nadia@skillswap.test', 'skill' => 'Prompt Engineering & LLM', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'nadia@skillswap.test', 'skill' => 'Python for Data Analysis', 'type' => 'wanted', 'level' => 'intermediate'],

            // Fajar Nugraha
            ['email' => 'fajar@skillswap.test', 'skill' => 'Flutter', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'fajar@skillswap.test', 'skill' => 'English for Tech Speaking', 'type' => 'wanted', 'level' => 'beginner'],

            // Maya Indah
            ['email' => 'maya@skillswap.test', 'skill' => 'English for Tech Speaking', 'type' => 'offered', 'level' => 'advanced'],
            ['email' => 'maya@skillswap.test', 'skill' => 'Flutter', 'type' => 'wanted', 'level' => 'beginner'],
        ];

        foreach ($userSkills as $item) {
            $userId = $users[$item['email']] ?? null;
            $skillId = $skills[$item['skill']] ?? null;

            if ($userId && $skillId) {
                UserSkill::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'skill_id' => $skillId,
                        'type' => $item['type'],
                    ],
                    [
                        'level' => $item['level'],
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
