<?php

namespace Database\Seeders;

use App\Models\SwapSession;
use App\Models\VideoCall;
use Illuminate\Database\Seeder;

class VideoCallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $session1 = SwapSession::where('status', 'completed')->first();
        $session2 = SwapSession::where('status', 'ongoing')->first();

        if ($session1) {
            VideoCall::create([
                'session_id' => $session1->id,
                'room_id' => 'room-swap-session-'.$session1->id.'-alpha',
                'started_at' => $session1->started_at,
                'ended_at' => $session1->completed_at,
                'duration_seconds' => 3900, // 65 minutes
                'created_at' => $session1->started_at ?? now(),
            ]);
        }

        if ($session2) {
            VideoCall::create([
                'session_id' => $session2->id,
                'room_id' => 'room-swap-session-'.$session2->id.'-live',
                'started_at' => $session2->started_at,
                'ended_at' => null,
                'duration_seconds' => null,
                'created_at' => $session2->started_at ?? now(),
            ]);
        }
    }
}
