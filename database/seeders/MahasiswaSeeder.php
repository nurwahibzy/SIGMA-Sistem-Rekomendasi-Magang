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
                'nama' => 'Fajar Pratama',
                'alamat' => 'Jl. Kenanga No. 3, Surakarta',
                'telepon' => '081234567804',
                'tanggal_lahir' => Carbon::parse('2000-05-25')->toDateString(),
                'email' => 'fajar.pratama@example.com',
                'gender' => 'l',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 3,
                'id_prodi' => 1, 
                'nama' => 'Ahmad Setiawan',
                'alamat' => 'Jl. Melati No. 5, Malang',
                'telepon' => '081234567805',
                'tanggal_lahir' => Carbon::parse('2001-08-15')->toDateString(),
                'email' => 'ahmad.setiawan@example.com',
                'gender' => 'l',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 4,
                'id_prodi' => 1, 
                'nama' => 'Siti Nurhaliza',
                'alamat' => 'Jl. Anggrek No. 10, Bandung',
                'telepon' => '081234567806',
                'tanggal_lahir' => Carbon::parse('2002-12-30')->toDateString(),
                'email' => 'siti.nurhaliza@example.com',
                'gender' => 'p',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 5,
                'id_prodi' => 1, 
                'nama' => 'Julianto',
                'alamat' => 'Jl. Mawar No. 8, Semarang',
                'telepon' => '081234567807',
                'tanggal_lahir' => Carbon::parse('2000-11-20')->toDateString(),
                'email' => 'julianto@example.com',
                'gender' => 'l',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 6,
                'id_prodi' => 1, 
                'nama' => 'Maika Sari',
                'alamat' => 'Jl. Anggrek No. 10, Bandung',
                'telepon' => '081234567809',
                'tanggal_lahir' => Carbon::parse('2002-12-30')->toDateString(),
                'email' => 'maika.sari@example.com',
                'gender' => 'p',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 7,
                'id_prodi' => 1, 
                'nama' => 'Rudi Hartono',
                'alamat' => 'Jl. Cempaka No. 15, Nganjuk',
                'telepon' => '081234567808',
                'tanggal_lahir' => Carbon::parse('2001-03-05')->toDateString(),
                'email' => 'rudi.hartono@example.com',
                'gender' => 'l',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('mahasiswa')->insert($data);
    }
}
