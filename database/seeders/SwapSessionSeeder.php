<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillMatch;
use App\Models\SwapSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class SwapSessionSeeder extends Seeder
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

        $laravel = Skill::where('name', 'Laravel Framework')->first();
        $vue = Skill::where('name', 'Vue.js')->first();
        $figma = Skill::where('name', 'Figma UI Design')->first();
        $react = Skill::where('name', 'React.js')->first();
        $python = Skill::where('name', 'Python for Data Analysis')->first();
        $prompt = Skill::where('name', 'Prompt Engineering & LLM')->first();

        $match1 = SkillMatch::where('user_a_id', min($budi?->id ?? 0, $siti?->id ?? 0))->first();
        $match2 = SkillMatch::where('user_a_id', min($reza?->id ?? 0, $dewi?->id ?? 0))->first();

        // 1. Completed Session: Budi & Siti
        if ($budi && $siti && $laravel && $vue) {
            SwapSession::create([
                'match_id' => $match1?->id,
                'requester_id' => $budi->id,
                'partner_id' => $siti->id,
                'requester_skill_id' => $laravel->id,
                'partner_skill_id' => $vue->id,
                'status' => 'completed',
                'scheduled_at' => now()->subDays(3)->setTime(14, 0),
                'started_at' => now()->subDays(3)->setTime(14, 5),
                'completed_at' => now()->subDays(3)->setTime(15, 10),
                'created_at' => now()->subDays(4),
            ]);
        }

        // 2. Ongoing Session: Reza & Dewi
        if ($reza && $dewi && $figma && $react) {
            SwapSession::create([
                'match_id' => $match2?->id,
                'requester_id' => $reza->id,
                'partner_id' => $dewi->id,
                'requester_skill_id' => $figma->id,
                'partner_skill_id' => $react->id,
                'status' => 'ongoing',
                'scheduled_at' => now()->subMinutes(30),
                'started_at' => now()->subMinutes(25),
                'completed_at' => null,
                'created_at' => now()->subDays(2),
            ]);
        }

        // 3. Accepted / Scheduled Session: Ahmad & Nadia
        if ($ahmad && $nadia && $python && $prompt) {
            SwapSession::create([
                'match_id' => null,
                'requester_id' => $ahmad->id,
                'partner_id' => $nadia->id,
                'requester_skill_id' => $python->id,
                'partner_skill_id' => $prompt->id,
                'status' => 'accepted',
                'scheduled_at' => now()->addDay()->setTime(19, 30),
                'started_at' => null,
                'completed_at' => null,
                'created_at' => now()->subHours(6),
            ]);
        }
    }
}
