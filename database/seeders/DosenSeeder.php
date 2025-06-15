<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_akun' => 8,
                'nama' => 'Dr. Siti Aminah',
                'alamat' => 'Jl. Melati No. 45, Surabaya',
                'telepon' => '081234567802',
                'tanggal_lahir' => Carbon::parse('1981-07-21')->toDateString(),
                'email' => 'siti.aminah@example.com',
                'gender' => 'p',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 9,
                'nama' => 'Dr. Andi Wijaya',
                'alamat' => 'Jl. Mawar No. 8, Semarang',
                'telepon' => '081234567803',
                'tanggal_lahir' => Carbon::parse('1983-09-15')->toDateString(),
                'email' => 'andi.wijaya@example.com',
                'gender' => 'l',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('dosen')->insert($data);
    }
}
