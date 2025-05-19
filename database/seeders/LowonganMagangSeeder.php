<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LowonganMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            ['Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            ['Data Analyst', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            ['UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            ['Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            ['Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            ['DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            ['Content Writer', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],
            ['Digital Marketing', 'Paham social media ads, Google Ads.', 'Menjalankan kampanye pemasaran digital.'],
            ['Customer Service', 'Komunikatif dan sabar menghadapi pelanggan.', 'Menangani pertanyaan dan keluhan pengguna.'],
        ];

        $jumlahLowongan = 50;
        $jumlahPerusahaan = 20;

        $counter = 0;
        $maxTries = 200;

        while ($counter < $jumlahLowongan && $maxTries > 0) {
            $position = $positions[array_rand($positions)];
            $id_perusahaan = rand(1, $jumlahPerusahaan);
            $id_bidang = rand(1, 6);
            $baseName = $position[0];
            $uniqueName = $baseName;
            $suffix = 1;

            // Pastikan kombinasi unik
            while (DB::table('lowongan_magang')->where([
                ['id_perusahaan', '=', $id_perusahaan],
                ['id_bidang', '=', $id_bidang],
                ['nama', '=', $uniqueName],
            ])->exists()) {
                $uniqueName = $baseName . ' (' . $suffix++ . ')';
                $maxTries--;
                if ($maxTries <= 0) break 2; // Hentikan kalau terlalu banyak percobaan
            }

            DB::table('lowongan_magang')->insert([
                'id_perusahaan' => $id_perusahaan,
                'id_bidang' => $id_bidang,
                'nama' => $uniqueName,
                'persyaratan' => $position[1],
                'deskripsi' => $position[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $counter++;
        }
    }
}
