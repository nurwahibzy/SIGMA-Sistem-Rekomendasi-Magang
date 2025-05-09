<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreferensiLokasiMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 1,
                'provinsi' => 'Jawa Barat',
                'daerah' => 'Bandung',
                'latitude' => '-6.9147',
                'longitude' => '107.6098',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('preferensi_lokasi_mahasiswa')->insert($data);
    }
}
