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
            ['1987123456780001', 1],
            ['123456789012', 2],
            ['1987654321000002', 3],
            ['1987654321000003', 3],
            ['1987654321000004', 3],
        ];

        foreach ($akun as $a) {
            DB::table('akun')->insert([
                'id_user' => $a[0],
                'id_level' => $a[1],
                'password' => Hash::make('password'), // default password
                'status' => 'aktif',
                'foto_path' =>  $a[0] . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
