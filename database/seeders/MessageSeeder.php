<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\SwapSession;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $session1 = SwapSession::where('status', 'completed')->first();
        $session2 = SwapSession::where('status', 'ongoing')->first();

        if ($session1) {
            $messages = [
                [
                    'session_id' => $session1->id,
                    'sender_id' => $session1->requester_id,
                    'type' => 'text',
                    'content' => 'Halo Siti! Salam kenal. Terima kasih sudah menerima swap session untuk Laravel & Vue.js.',
                    'read_at' => $session1->created_at->addMinutes(5),
                    'created_at' => $session1->created_at->addMinutes(2),
                ],
                [
                    'session_id' => $session1->id,
                    'sender_id' => $session1->partner_id,
                    'type' => 'text',
                    'content' => 'Halo Mas Budi! Sama-sama. Saya antusias sekali ingin memperdalam query Eloquent dan API resource di Laravel.',
                    'read_at' => $session1->created_at->addMinutes(8),
                    'created_at' => $session1->created_at->addMinutes(6),
                ],
                [
                    'session_id' => $session1->id,
                    'sender_id' => $session1->requester_id,
                    'type' => 'text',
                    'content' => 'Siap! Nanti kita bahas live coding sekitar 1 jam ya. 30 menit Laravel, 30 menit Vue 3 Composition API.',
                    'read_at' => $session1->created_at->addMinutes(12),
                    'created_at' => $session1->created_at->addMinutes(10),
                ],
                [
                    'session_id' => $session1->id,
                    'sender_id' => $session1->partner_id,
                    'type' => 'text',
                    'content' => 'Deal! Sampai jumpa di room video call sesuai jadwal.',
                    'read_at' => $session1->created_at->addMinutes(15),
                    'created_at' => $session1->created_at->addMinutes(14),
                ],
                [
                    'session_id' => $session1->id,
                    'sender_id' => $session1->requester_id,
                    'type' => 'system',
                    'content' => 'Sesi video call telah dimulai.',
                    'read_at' => $session1->started_at,
                    'created_at' => $session1->started_at ?? now(),
                ],
            ];

            foreach ($messages as $msg) {
                Message::create($msg);
            }
        }

        if ($session2) {
            $messages2 = [
                [
                    'session_id' => $session2->id,
                    'sender_id' => $session2->requester_id,
                    'type' => 'text',
                    'content' => 'Hai Dewi! Aku sudah siapkan Figma file berisi contoh design system tokens & auto-layout.',
                    'read_at' => now()->subMinutes(20),
                    'created_at' => now()->subMinutes(24),
                ],
                [
                    'session_id' => $session2->id,
                    'sender_id' => $session2->partner_id,
                    'type' => 'text',
                    'content' => 'Keren banget Reza! Aku juga sudah buka repository React Vite untuk kita implementasi komponen tombol dan modal.',
                    'read_at' => now()->subMinutes(18),
                    'created_at' => now()->subMinutes(19),
                ],
            ];

            foreach ($messages2 as $msg) {
                Message::create($msg);
            }
        }
    }
}
