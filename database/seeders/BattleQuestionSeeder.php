<?php

namespace Database\Seeders;

use App\Models\AiLog;
use App\Models\BattleQuestion;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class BattleQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $aiLog = AiLog::first();
        $laravel = Skill::where('name', 'Laravel Framework')->first();
        $vue = Skill::where('name', 'Vue.js')->first();
        $figma = Skill::where('name', 'Figma UI Design')->first();
        $python = Skill::where('name', 'Python for Data Analysis')->first();

        $questions = [];

        if ($laravel) {
            $questions[] = [
                'skill_id' => $laravel->id,
                'question' => 'Method apa yang digunakan di Eloquent untuk mencegah masalah N+1 Query Problem?',
                'options' => [
                    'with() (Eager Loading)',
                    'lazy()',
                    'find()',
                    'chunk()',
                ],
                'correct_option' => 0,
                'points' => 10,
                'difficulty' => 'easy',
                'explanation' => 'Eager loading dengan with() memuat relasi sekaligus dalam query terpisah, mencegah N+1 query problem.',
                'source' => 'manual',
                'status' => 'approved',
                'ai_model' => null,
                'generation_log_id' => null,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now(),
                'created_by' => $admin?->id,
                'created_at' => now(),
            ];

            $questions[] = [
                'skill_id' => $laravel->id,
                'question' => 'Di arsitektur Laravel, di mana tempat terbaik untuk menempatkan validasi request yang kompleks?',
                'options' => [
                    'Langsung di dalam Blade View',
                    'Form Request class terdedikasi',
                    'Database Migration file',
                    'Kernel.php',
                ],
                'correct_option' => 1,
                'points' => 10,
                'difficulty' => 'medium',
                'explanation' => 'Form Request mengisolasi logika validasi dan autorisasi dari Controller, menjaga kode tetap clean dan modular.',
                'source' => 'ai',
                'status' => 'approved',
                'ai_model' => 'gpt-4o-mini',
                'generation_log_id' => $aiLog?->id,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now(),
                'created_by' => $admin?->id,
                'created_at' => now(),
            ];

            $questions[] = [
                'skill_id' => $laravel->id,
                'question' => 'Fitur apa di Laravel yang digunakan untuk mengeksekusi tugas berat seperti pengiriman email secara asynchronous?',
                'options' => [
                    'Laravel Artisan make:command',
                    'Database Seeder',
                    'Queue Jobs & Workers',
                    'Route Middleware',
                ],
                'correct_option' => 2,
                'points' => 10,
                'difficulty' => 'medium',
                'explanation' => 'Laravel Queues memungkinkan penundaan pemrosesan tugas yang memakan waktu (seperti kirim email) ke background.',
                'source' => 'manual',
                'status' => 'approved',
                'ai_model' => null,
                'generation_log_id' => null,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now(),
                'created_by' => $admin?->id,
                'created_at' => now(),
            ];
        }

        if ($vue) {
            $questions[] = [
                'skill_id' => $vue->id,
                'question' => 'Dalam Vue 3 Composition API, fungsi apa yang digunakan untuk membuat state reaktif tipe primitif?',
                'options' => [
                    'reactive()',
                    'ref()',
                    'computed()',
                    'watch()',
                ],
                'correct_option' => 1,
                'points' => 10,
                'difficulty' => 'easy',
                'explanation' => 'ref() digunakan untuk membungkus nilai primitif (string, number, boolean) agar reaktif melalui properti .value.',
                'source' => 'manual',
                'status' => 'approved',
                'ai_model' => null,
                'generation_log_id' => null,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now(),
                'created_by' => $admin?->id,
                'created_at' => now(),
            ];

            $questions[] = [
                'skill_id' => $vue->id,
                'question' => 'Apa store management library resmi yang direkomendasikan untuk Vue 3 menggantikan Vuex?',
                'options' => [
                    'Redux',
                    'Pinia',
                    'MobX',
                    'Zustand',
                ],
                'correct_option' => 1,
                'points' => 10,
                'difficulty' => 'easy',
                'explanation' => 'Pinia adalah state management library resmi baru untuk Vue yang mendukung TypeScript dan Composition API.',
                'source' => 'ai',
                'status' => 'approved',
                'ai_model' => 'gpt-4o-mini',
                'generation_log_id' => $aiLog?->id,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now(),
                'created_by' => $admin?->id,
                'created_at' => now(),
            ];
        }

        if ($figma) {
            $questions[] = [
                'skill_id' => $figma->id,
                'question' => 'Fitur apa di Figma yang memungkinkan layout card menyesuaikan ukuran padding dan jarak antar elemen secara otomatis?',
                'options' => [
                    'Constraints',
                    'Auto Layout (Shift + A)',
                    'Smart Animate',
                    'Boolean Groups',
                ],
                'correct_option' => 1,
                'points' => 10,
                'difficulty' => 'easy',
                'explanation' => 'Auto Layout menyusun frame yang secara otomatis menyesuaikan ukuran sesuai konten dengan aturan padding & gap.',
                'source' => 'manual',
                'status' => 'approved',
                'ai_model' => null,
                'generation_log_id' => null,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now(),
                'created_by' => $admin?->id,
                'created_at' => now(),
            ];
        }

        if ($python) {
            $questions[] = [
                'skill_id' => $python->id,
                'question' => 'Di library Pandas, method apa yang digunakan untuk menampilkan statistik deskriptif ringkas dari DataFrame numerik?',
                'options' => [
                    'df.info()',
                    'df.describe()',
                    'df.head()',
                    'df.summary()',
                ],
                'correct_option' => 1,
                'points' => 10,
                'difficulty' => 'easy',
                'explanation' => 'df.describe() menghasilkan ringkasan statistik seperti mean, std, min, quartiles, dan max.',
                'source' => 'manual',
                'status' => 'approved',
                'ai_model' => null,
                'generation_log_id' => null,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now(),
                'created_by' => $admin?->id,
                'created_at' => now(),
            ];
        }

        foreach ($questions as $q) {
            BattleQuestion::create($q);
        }
    }
}
