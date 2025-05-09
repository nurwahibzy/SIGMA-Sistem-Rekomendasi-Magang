<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kode' => 'ADM',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'MHS',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'DSN',
                'role' => 'dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('level')->insert($data);
    }
}
