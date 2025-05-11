<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeriodeMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_lowongan' => 1,
                'nama' => 'Periode 1 - 2025',
                'tanggal_mulai' => Carbon::parse('2025-06-01')->toDateString(),
                'tanggal_selesai' => Carbon::parse('2025-08-31')->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lowongan' => 2,
                'nama' => 'Periode 2 - 2025',
                'tanggal_mulai' => Carbon::parse('2025-07-01')->toDateString(),
                'tanggal_selesai' => Carbon::parse('2025-09-30')->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lowongan' => 3,
                'nama' => 'Periode 3 - 2025',
                'tanggal_mulai' => Carbon::parse('2025-08-01 09:00:00'),
                'tanggal_selesai' => Carbon::parse('2025-10-31 17:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('periode_magang')->insert($data);
    }
}
