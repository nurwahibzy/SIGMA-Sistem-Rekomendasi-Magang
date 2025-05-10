<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 1,
                'id_dosen' => null, 
                'id_periode' => 1,
                'status' => 'ditolak',
                'tanggal_pengajuan' => Carbon::parse('2024-01-10 08:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 1,
                'id_dosen' => 2, 
                'id_periode' => 2,
                'status' => 'lulus',
                'tanggal_pengajuan' => Carbon::parse('2024-06-15 10:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('magang')->insert($data);
    }
}
