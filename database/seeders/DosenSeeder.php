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
                'id_akun' => 2,
                'nip' => '1987654321000002',
                'nama' => 'Dr. Budi Santosa',
                'alamat' => 'Jl. Cendana No. 12, Yogyakarta',
                'telepon' => '081234567801',
                'tanggal_lahir' => Carbon::parse('1979-03-10 00:00:00'),
                'email' => 'budi.santosa@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 3,
                'nip' => '1987654321000003',
                'nama' => 'Dr. Siti Aminah',
                'alamat' => 'Jl. Melati No. 45, Surabaya',
                'telepon' => '081234567802',
                'tanggal_lahir' => Carbon::parse('1981-07-21 00:00:00'),
                'email' => 'siti.aminah@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 4,
                'nip' => '1987654321000004',
                'nama' => 'Dr. Andi Wijaya',
                'alamat' => 'Jl. Mawar No. 8, Semarang',
                'telepon' => '081234567803',
                'tanggal_lahir' => Carbon::parse('1983-09-15 00:00:00'),
                'email' => 'andi.wijaya@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('dosen')->insert($data);
    }
}
