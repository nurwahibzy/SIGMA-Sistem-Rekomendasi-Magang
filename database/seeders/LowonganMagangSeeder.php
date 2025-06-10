<?php

namespace Database\Seeders;

use App\Models\BidangModel;
use App\Models\LowonganMagangModel;
use App\Models\PerusahaanModel;
use DB;
use Illuminate\Database\Seeder;

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

        $jumlahLowongan = 30;
        $jumlahPerusahaan = 20;
        $jumlahBidang = BidangModel::count();

        $counter = 0;

        for ($index = 0; $index < $jumlahLowongan; $index++) {
            $position = $positions[$index % count($positions)];
            $id_perusahaan = ($index % $jumlahPerusahaan) + 1;
            $id_bidang = ($index % $jumlahBidang) + 1;

            $baseName = $position[0];
            $uniqueName = $baseName;
            $suffix = 1;

            // Cek duplikat nama
            while (DB::table('lowongan_magang')->where([
                ['id_perusahaan', '=', $id_perusahaan],
                ['id_bidang', '=', $id_bidang],
                ['nama', '=', $uniqueName],
            ])->exists()) {
                $uniqueName = $baseName . ' (' . $suffix++ . ')';
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
        }
    }
}
