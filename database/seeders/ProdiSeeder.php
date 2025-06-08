<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_prodi' => 'Teknik Informatika',
                'nama_jurusan' => 'Teknologi Informasi',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_prodi' => 'Sistem Informasi Bisnis',
                'nama_jurusan' => 'Teknologi Informasi',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('prodi')->insert($data);
    }
}
