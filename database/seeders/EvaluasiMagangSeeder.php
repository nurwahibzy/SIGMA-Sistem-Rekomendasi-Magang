<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluasiMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_magang' => 2,
                'feedback'  => 'Magang berjalan lancar, mahasiswa menunjukkan peningkatan dalam keterampilan dan selalu hadir tepat waktu.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('evaluasi_magang')->insert($data);
    }
}
