<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akun = [
            ['admin', 1],
            ['mahasiswa1', 2],
            ['dosen1', 3],
            ['dosen2', 3],
            ['dosen3', 3],
        ];

        foreach ($akun as $a) {
            DB::table('akun')->insert([
                'username' => $a[0],
                'id_level' => $a[1],
                'password' => Hash::make('password'), // default password
                'status' => 'aktif',
                'foto_path' => 'images/akun/' . $a[0] . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
