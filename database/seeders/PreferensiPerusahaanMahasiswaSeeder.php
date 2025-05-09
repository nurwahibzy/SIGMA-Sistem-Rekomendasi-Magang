<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreferensiPerusahaanMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 1,
                'id_jenis' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 1,
                'id_jenis' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 1,
                'id_jenis' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('preferensi_perusahaan_mahasiswa')->insert($data);
    }
}
