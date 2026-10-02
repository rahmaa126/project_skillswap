<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillMatch;
use App\Models\User;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budi = User::where('email', 'budi@skillswap.test')->first();
        $siti = User::where('email', 'siti@skillswap.test')->first();
        $reza = User::where('email', 'reza@skillswap.test')->first();
        $dewi = User::where('email', 'dewi@skillswap.test')->first();
        $ahmad = User::where('email', 'ahmad@skillswap.test')->first();
        $nadia = User::where('email', 'nadia@skillswap.test')->first();
        $fajar = User::where('email', 'fajar@skillswap.test')->first();
        $maya = User::where('email', 'maya@skillswap.test')->first();

        $laravel = Skill::where('name', 'Laravel Framework')->first();
        $vue = Skill::where('name', 'Vue.js')->first();
        $figma = Skill::where('name', 'Figma UI Design')->first();
        $react = Skill::where('name', 'React.js')->first();
        $python = Skill::where('name', 'Python for Data Analysis')->first();
        $prompt = Skill::where('name', 'Prompt Engineering & LLM')->first();
        $flutter = Skill::where('name', 'Flutter')->first();
        $english = Skill::where('name', 'English for Tech Speaking')->first();

        $matches = [];

        // Match 1: Budi & Siti (Accepted)
        if ($budi && $siti && $laravel && $vue) {
            $matches[] = [
                'user_a_id' => min($budi->id, $siti->id),
                'user_b_id' => max($budi->id, $siti->id),
                'skill_a_id' => $laravel->id,
                'skill_b_id' => $vue->id,
                'match_score' => 95.50,
                'status' => 'accepted',
                'match_source' => 'ai_semantic',
                'score_breakdown' => ['semantic' => 0.96, 'level' => 0.95, 'rating' => 0.98],
                'ai_reason' => 'Keduanya memiliki skor keahlian tinggi dan saling membutuhkan skill (Budi butuh Vue.js, Siti butuh Laravel).',
                'created_at' => now()->subDays(5),
            ];
        }

        // Match 2: Reza & Dewi (Accepted)
        if ($reza && $dewi && $figma && $react) {
            $matches[] = [
                'user_a_id' => min($reza->id, $dewi->id),
                'user_b_id' => max($reza->id, $dewi->id),
                'skill_a_id' => $figma->id,
                'skill_b_id' => $react->id,
                'match_score' => 92.00,
                'status' => 'accepted',
                'match_source' => 'ai_semantic',
                'score_breakdown' => ['semantic' => 0.92, 'level' => 0.90, 'rating' => 0.95],
                'ai_reason' => 'Sinergi mutualisme antara Product Designer (Figma) dan Frontend Engineer (React).',
                'created_at' => now()->subDays(3),
            ];
        }

        // Match 3: Ahmad & Nadia (Suggested)
        if ($ahmad && $nadia && $python && $prompt) {
            $matches[] = [
                'user_a_id' => min($ahmad->id, $nadia->id),
                'user_b_id' => max($ahmad->id, $nadia->id),
                'skill_a_id' => $python->id,
                'skill_b_id' => $prompt->id,
                'match_score' => 88.50,
                'status' => 'suggested',
                'match_source' => 'rule_based',
                'score_breakdown' => ['skill_match' => 0.88, 'location' => 0.85],
                'ai_reason' => 'Pencocokan skill: kebutuhan data analyst dan rekayasa prompt saling melengkapi.',
                'created_at' => now()->subDay(),
            ];
        }

        // Match 4: Fajar & Maya (Suggested)
        if ($fajar && $maya && $flutter && $english) {
            $matches[] = [
                'user_a_id' => min($fajar->id, $maya->id),
                'user_b_id' => max($fajar->id, $maya->id),
                'skill_a_id' => $flutter->id,
                'skill_b_id' => $english->id,
                'match_score' => 86.00,
                'status' => 'suggested',
                'match_source' => 'rule_based',
                'score_breakdown' => ['skill_match' => 0.86],
                'ai_reason' => 'Fajar ingin melatih English Speaking dan Maya ingin mempelajari mobile app Flutter.',
                'created_at' => now()->subHours(12),
            ];
        }

        foreach ($matches as $match) {
            SkillMatch::updateOrCreate(
                [
                    'user_a_id' => $match['user_a_id'],
                    'user_b_id' => $match['user_b_id'],
                    'skill_a_id' => $match['skill_a_id'],
                    'skill_b_id' => $match['skill_b_id'],
                ],
                $match
            );
        }
    }
}
