<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 1,
                'nama' => 'CV',
                'file_path' => '1_CV.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_mahasiswa' => 1,
                'nama' => 'Transkrip',
                'file_path' => '1_Transkrip.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('dokumen')->insert($data);
    }
}
