<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Admin User
        $admin = User::create([
            'name' => 'Admin SkillSwap',
            'email' => 'admin@skillswap.test',
            'password_hash' => $password,
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'last_login_at' => now(),
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150',
            'bio' => 'System Administrator at SkillSwap Platform.',
            'city' => 'Jakarta',
            'phone' => '081200000001',
            'avg_rating' => 5.00,
            'total_swaps' => 0,
        ]);

        // 2. Regular Users with Profiles
        $usersData = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@skillswap.test',
                'city' => 'Jakarta Selatan',
                'phone' => '081234567801',
                'bio' => 'Senior Backend Engineer dengan 5+ tahun pengalaman di Laravel & Cloud Architecture. Ingin mendalami reactive frontend.',
                'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
                'avg_rating' => 4.90,
                'total_swaps' => 14,
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti@skillswap.test',
                'city' => 'Bandung',
                'phone' => '081234567802',
                'bio' => 'Frontend Specialist fokus di Vue.js ecosystem dan design system. Sedang belajar arsitektur backend Laravel.',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150',
                'avg_rating' => 4.85,
                'total_swaps' => 12,
            ],
            [
                'name' => 'Reza Pratama',
                'email' => 'reza@skillswap.test',
                'city' => 'Yogyakarta',
                'phone' => '081234567803',
                'bio' => 'Product Designer & Design System Architect di startup teknologi. Ingin belajar React.js untuk prototyping interaktif.',
                'avatar' => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150',
                'avg_rating' => 4.95,
                'total_swaps' => 18,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@skillswap.test',
                'city' => 'Surabaya',
                'phone' => '081234567804',
                'bio' => 'Fullstack Developer berfokus pada React & Next.js. Ingin mengasah skill UI/UX research dan Figma auto-layout.',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150',
                'avg_rating' => 4.80,
                'total_swaps' => 9,
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@skillswap.test',
                'city' => 'Semarang',
                'phone' => '081234567805',
                'bio' => 'Data Analyst mengolah big data menggunakan Python, Pandas, dan SQL. Tertarik menguasai Prompt Engineering & LLM.',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150',
                'avg_rating' => 4.75,
                'total_swaps' => 7,
            ],
            [
                'name' => 'Nadia Putri',
                'email' => 'nadia@skillswap.test',
                'city' => 'Malang',
                'phone' => '081234567806',
                'bio' => 'AI Prompt Engineer & Content Strategist. Ingin belajar fundamental Python Data Science untuk otomasi analisis.',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150',
                'avg_rating' => 4.90,
                'total_swaps' => 11,
            ],
            [
                'name' => 'Fajar Nugraha',
                'email' => 'fajar@skillswap.test',
                'city' => 'Bali',
                'phone' => '081234567807',
                'bio' => 'Mobile Developer spesialis Flutter cross-platform. Sedang mempersiapkan kemampuan English Speaking untuk remote global work.',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150',
                'avg_rating' => 4.70,
                'total_swaps' => 6,
            ],
            [
                'name' => 'Maya Indah',
                'email' => 'maya@skillswap.test',
                'city' => 'Jakarta Barat',
                'phone' => '081234567808',
                'bio' => 'Corporate English Coach & Tech Translator. Ingin belajar membuat aplikasi mobile Android/iOS menggunakan Flutter.',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150',
                'avg_rating' => 4.95,
                'total_swaps' => 16,
            ],
        ];

        foreach ($usersData as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password_hash' => $password,
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(rand(1, 48)),
            ]);

            Profile::create([
                'user_id' => $user->id,
                'avatar_url' => $userData['avatar'],
                'bio' => $userData['bio'],
                'city' => $userData['city'],
                'phone' => $userData['phone'],
                'avg_rating' => $userData['avg_rating'],
                'total_swaps' => $userData['total_swaps'],
            ]);
        }
    }
}
