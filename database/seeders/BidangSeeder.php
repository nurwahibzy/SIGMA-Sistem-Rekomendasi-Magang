<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Front End',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Back End',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Machine Learning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Data Science',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'DevOps',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'UI/UX Design',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Cyber Security',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mobile Development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('bidang')->insert($data);
    }
}
