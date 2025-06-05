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
                'status' => 'diterima',
                'tanggal_pengajuan' => Carbon::parse('2024-02-20 14:30:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 2,
                'id_dosen' => 2,
                'id_periode' => 3,
                'status' => 'proses',
                'tanggal_pengajuan' => Carbon::parse('2025-06-02 09:15:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 3,
                'id_dosen' => 1,
                'id_periode' => 3,
                'status' => 'diterima',
                'tanggal_pengajuan' => Carbon::parse('2024-04-12 11:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 4,
                'id_dosen' => 3,
                'id_periode' => 7,
                'status' => 'diterima',
                'tanggal_pengajuan' => Carbon::parse('2024-05-25 16:45:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 5,
                'id_dosen' => 2,
                'id_periode' => 6,
                'status' => 'diterima',
                'tanggal_pengajuan' => Carbon::parse('2024-06-01 07:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 6,
                'id_dosen' => null,
                'id_periode' => 5,
                'status' => 'ditolak',
                'tanggal_pengajuan' => Carbon::parse('2024-06-08 10:20:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 6,
                'id_dosen' => 3,
                'id_periode' => 4,
                'status' => 'diterima',
                'tanggal_pengajuan' => Carbon::parse('2024-06-15 13:50:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('magang')->insert($data);
    }
}
