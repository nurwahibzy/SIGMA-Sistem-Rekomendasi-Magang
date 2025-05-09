<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AktivitasMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_magang'  => 2,
                'tanggal'    => Carbon::parse('2025-05-09')->toDateString(),
                'keterangan' => 'Membuat laporan kegiatan harian',
                'foto_path'  => 'foto/1_2025-05-09.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('aktivitas_magang')->insert($data);
    }
}
