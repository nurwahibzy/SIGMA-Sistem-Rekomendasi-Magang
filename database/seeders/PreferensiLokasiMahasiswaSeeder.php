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
                'provinsi' => 'Jawa Tengah',
                'daerah' => 'Surakarta',
                'latitude' => '-7.5556',
                'longitude' => '110.8222',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 2,
                'provinsi' => 'Jawa Timur',
                'daerah' => 'Malang',
                'latitude' => '-7.9666',
                'longitude' => '112.6333',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 3,
                'provinsi' => 'Jawa Barat',
                'daerah' => 'Bandung',
                'latitude' => '-6.9175',
                'longitude' => '107.6191',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 4,
                'provinsi' => 'Jawa Tengah',
                'daerah' => 'Semarang',
                'latitude' => '-6.9700', 
                'longitude' => '110.4200', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 5,
                'provinsi' => 'Jawa Barat',
                'daerah' => 'Bandung',
                'latitude' => '-6.9175',
                'longitude' => '107.6191',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 6,
                'provinsi' => 'Jawa Timur',
                'daerah' => 'Nganjuk',
                'latitude' => '-7.6000', 
                'longitude' => '111.9000', 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('preferensi_lokasi_mahasiswa')->insert($data);
    }
}
