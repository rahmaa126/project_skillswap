<?php

namespace Database\Seeders;

use App\Models\Battle;
use App\Models\BattleAnswer;
use App\Models\BattleQuestion;
use App\Models\Skill;
use App\Models\SkillMatch;
use App\Models\User;
use Illuminate\Database\Seeder;

class BattleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budi = User::where('email', 'budi@skillswap.test')->first();
        $siti = User::where('email', 'siti@skillswap.test')->first();
        $laravel = Skill::where('name', 'Laravel Framework')->first();
        $match = SkillMatch::where('user_a_id', min($budi?->id ?? 0, $siti?->id ?? 0))->first();

        if ($budi && $siti && $laravel) {
            $battle = Battle::create([
                'skill_id' => $laravel->id,
                'match_id' => $match?->id,
                'player1_id' => $budi->id,
                'player2_id' => $siti->id,
                'winner_id' => $budi->id,
                'status' => 'finished',
                'started_at' => now()->subDays(4)->addMinutes(10),
                'finished_at' => now()->subDays(4)->addMinutes(25),
                'created_at' => now()->subDays(4),
            ]);

            // Add answers for this battle
            $questions = BattleQuestion::where('skill_id', $laravel->id)->take(2)->get();

            foreach ($questions as $q) {
                // Budi's answer (correct)
                BattleAnswer::create([
                    'battle_id' => $battle->id,
                    'question_id' => $q->id,
                    'user_id' => $budi->id,
                    'chosen_option' => $q->correct_option,
                    'is_correct' => true,
                    'points_earned' => $q->points,
                    'answered_at' => now()->subDays(4)->addMinutes(12),
                ]);

                // Siti's answer
                BattleAnswer::create([
                    'battle_id' => $battle->id,
                    'question_id' => $q->id,
                    'user_id' => $siti->id,
                    'chosen_option' => ($q->correct_option + 1) % 4,
                    'is_correct' => false,
                    'points_earned' => 0,
                    'answered_at' => now()->subDays(4)->addMinutes(15),
                ]);
            }
        }
    }
}
