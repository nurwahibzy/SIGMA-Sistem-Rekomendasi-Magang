<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeriodeMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $startMonth = 6; // Mulai dari bulan Juni
        $year = 2025;

        for ($i = 1; $i <= 50; $i++) {
            $bulanMulai = $startMonth + ($i % 3); // Rotasi antara bulan 6, 7, 8
            $tanggalMulai = Carbon::createFromDate($year, $bulanMulai, 1);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth(); // 3 bulan periode

            $data[] = [
                'id_lowongan' => $i,
                'nama' => 'Periode ' . $i . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('periode_magang')->insert($data);
    }
}
