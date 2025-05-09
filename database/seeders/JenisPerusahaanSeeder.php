<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'jenis' => 'BUMN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Swasta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Pemerintahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Startup',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Lembaga Pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis' => 'Organisasi Non-Profit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('jenis_perusahaan')->insert($data);
    }
}
