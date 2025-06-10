<?php

namespace Database\Seeders;

use App\Models\LowonganMagangModel;
use DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeriodeMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $idCounter = 1; 

        $data2025 = [];
        
        for ($i = 0; $i < 6; $i++) {
            $year = 2025;
            $startMonth = 7 + $i; 

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, 1);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth();

            $data2025[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }

        for ($i = 0; $i < 4; $i++) {
            $year = 2025;
            $startMonth = 7 + $i; 

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, 15); 
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth();

            $data2025[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }
        DB::table('periode_magang')->insert($data2025);

        $data2024 = [];

        for ($i = 0; $i < 12; $i++) { 
            $year = 2024;
            $startMonth = 1 + $i;
            $startDay = 1;

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, $startDay);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth();

            $data2024[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }

        for ($i = 0; $i < 12; $i++) { 
            $year = 2024;
            $startMonth = 1 + $i;
            $startDay = 10;

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, $startDay);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth();

            $data2024[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }

        for ($i = 0; $i < 6; $i++) {
            $year = 2024;
            $startMonth = 1 + $i;
            $startDay = 20;

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, $startDay);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth();

            $data2024[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }
        DB::table('periode_magang')->insert($data2024);

        $data2023 = [];

        for ($i = 0; $i < 12; $i++) { 
            $year = 2023;
            $startMonth = 1 + $i;
            $startDay = 1;

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, $startDay);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth();

            $data2023[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }

        for ($i = 0; $i < 8; $i++) { 
            $year = 2023;
            $startMonth = 1 + $i;
            $startDay = 15; 

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, $startDay);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth();

            $data2023[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }
        DB::table('periode_magang')->insert($data2023);

        $data2022 = [];
        for ($i = 0; $i < 10; $i++) {
            $year = 2022;
            $startMonth = 1 + $i;
            $startDay = 1; 

            if ($startMonth > 12) {
                $startMonth = 1; 
                $year++; 
            }

            $tanggalMulai = Carbon::createFromDate($year, $startMonth, $startDay);
            $tanggalSelesai = $tanggalMulai->copy()->addMonths(2)->endOfMonth(); 

            $data2022[] = [
                'id_periode' => $idCounter,
                'id_lowongan' => $idCounter,
                'nama' => 'Periode ' . $idCounter . ' - ' . $year,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $idCounter++;
        }
        DB::table('periode_magang')->insert($data2022);

    }
}
