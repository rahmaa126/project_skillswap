<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\SwapSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $budi = User::where('email', 'budi@skillswap.test')->first();
        $fajar = User::where('email', 'fajar@skillswap.test')->first();
        $session = SwapSession::first();

        if ($budi && $fajar) {
            Report::create([
                'reporter_id' => $budi->id,
                'reported_id' => $fajar->id,
                'session_id' => $session?->id,
                'reason' => 'Contoh laporan uji coba moderasi: Masalah jaringan internet saat koneksi video room.',
                'status' => 'resolved',
                'handled_by' => $admin?->id,
                'created_at' => now()->subDays(2),
            ]);
        }
    }
}
