<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'id_akun' => 1,
            'nama' => 'Administrator Utama',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'telepon' => '081234567890',
            'tanggal_lahir' => Carbon::parse('1987-01-01')->toDateString(),
            'email' => 'admin1@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('admin')->insert($data);
    }
}
