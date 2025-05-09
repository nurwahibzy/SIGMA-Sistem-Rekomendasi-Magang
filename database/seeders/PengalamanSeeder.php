<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengalamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 1, 
                'deskripsi' => 'Saya telah melakukan magang di perusahaan teknologi selama 6 bulan dan mengerjakan berbagai proyek pengembangan perangkat lunak menggunakan PHP dan Laravel.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 1, 
                'deskripsi' => 'Saya aktif sebagai asisten dosen dalam mata kuliah Pemrograman Web dan membantu dalam pembuatan modul pembelajaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 1, 
                'deskripsi' => 'Saya juga terlibat dalam proyek pengembangan aplikasi mobile yang menggunakan React Native, bekerja dalam tim untuk membuat aplikasi berbasis Android dan iOS.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pengalaman')->insert($data);
    }
}
