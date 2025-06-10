<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            BidangSeeder::class,
            JenisPerusahaanSeeder::class,
            ProdiSeeder::class,
            PerusahaanSeeder::class,
            AkunSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            PengalamanSeeder::class,
            DokumenSeeder::class,
            KompetensiSeeder::class,
            PreferensiLokasiMahasiswaSeeder::class,
            PreferensiPerusahaanMahasiswaSeeder::class,
            LowonganMagangSeeder::class,
            KeahlianMahasiswaSeeder::class,
            KeahlianDosenSeeder::class,
            PeriodeMagangSeeder::class,
            MagangSeeder::class,
            AktivitasMagangSeeder::class,
            EvaluasiMagangSeeder::class,
            PenilaianSeeder::class,
        ]);
    }
}
