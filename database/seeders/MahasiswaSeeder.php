<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_akun' => 2,
                'id_prodi' => 1, 
                'nim' => '123456789012',
                'nama' => 'Fajar Pratama',
                'alamat' => 'Jl. Kenanga No. 3, Surakarta',
                'telepon' => '081234567804',
                'tanggal_lahir' => Carbon::parse('2000-05-25')->toDateString(),
                'email' => 'fajar.pratama@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('mahasiswa')->insert($data);
    }
}
