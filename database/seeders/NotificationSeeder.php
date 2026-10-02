<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
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

        $notifications = [
            [
                'user_id' => $budi?->id,
                'title' => 'Tantangan Battle Menang! 🎉',
                'body' => 'Selamat, kamu memenangkan battle kuis Laravel Framework melawan Siti Rahmawati.',
                'is_read' => true,
                'created_at' => now()->subDays(4),
            ],
            [
                'user_id' => $budi?->id,
                'title' => 'Review Baru Diterima 🌟',
                'body' => 'Siti Rahmawati memberikan review bintang 5 untuk sesi pertukaran skill Laravel.',
                'is_read' => false,
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $siti?->id,
                'title' => 'Swap Session Selesai 🤝',
                'body' => 'Sesi belajar bersama Budi Santoso telah selesai. Jangan lupa berikan ulasan!',
                'is_read' => true,
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $reza?->id,
                'title' => 'Match Baru Ditemukan! 🎯',
                'body' => 'AI menemukan pasangan skillswap baru: Dewi Lestari (React.js <-> Figma UI Design).',
                'is_read' => true,
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $dewi?->id,
                'title' => 'Sesi Swap Dimulai ⏱️',
                'body' => 'Sesi belajar bersama Reza Pratama sedang berlangsung. Klik untuk masuk ke video call.',
                'is_read' => false,
                'created_at' => now()->subMinutes(25),
            ],
        ];

        foreach ($notifications as $notif) {
            if ($notif['user_id']) {
                Notification::create($notif);
            }
        }
    }
}
