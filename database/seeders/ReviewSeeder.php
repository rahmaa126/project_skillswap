<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\SwapSession;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $session1 = SwapSession::where('status', 'completed')->first();

        if ($session1) {
            // Review from Budi to Siti
            Review::create([
                'session_id' => $session1->id,
                'reviewer_id' => $session1->requester_id,
                'reviewee_id' => $session1->partner_id,
                'rating' => 5,
                'comment' => 'Penjelasan Kak Siti tentang Vue 3 Composition API dan Pinia sangat terstruktur, jelas, dan aplikatif!',
                'is_hidden' => false,
                'created_at' => $session1->completed_at ? $session1->completed_at->addMinutes(10) : now(),
            ]);

            // Review from Siti to Budi
            Review::create([
                'session_id' => $session1->id,
                'reviewer_id' => $session1->partner_id,
                'reviewee_id' => $session1->requester_id,
                'rating' => 5,
                'comment' => 'Mas Budi sangat menguasai arsitektur backend Laravel. Contoh relasi database dan repository pattern-nya sangat membantu saya.',
                'is_hidden' => false,
                'created_at' => $session1->completed_at ? $session1->completed_at->addMinutes(15) : now(),
            ]);
        }
    }
}
