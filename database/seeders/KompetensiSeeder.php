<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kompetensi = [
            [1, 1],
            [1, 2],
            [1, 3],
            [1, 4],
            [1, 5],
            [1, 6],
            [1, 7],
            [2, 6],
            [2, 7],
            [2, 8]
        ];

        foreach ($kompetensi as $kompeten) {
            DB::table('kompetensi')->insert([
                'id_mahasiswa' => $kompeten[0],
                'id_bidang' => $kompeten[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
