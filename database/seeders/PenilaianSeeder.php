<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_magang' => 2,
                'fasilitas' => 4,  
                'tugas' => 5,      
                'kedisiplinan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('penilaian')->insert($data);
    }
}
