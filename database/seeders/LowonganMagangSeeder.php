<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LowonganMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lowonganMagang = [
            [1, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Bertanggung jawab dalam membangun antarmuka pengguna menggunakan framework ReactJS atau VueJS.'],
            [2, 2, 'Back End Developer', 'Memiliki pengalaman dalam Node.js atau Java, paham konsep API dan database.', 'Mengembangkan layanan backend yang handal dan scalable menggunakan Node.js atau Java.'],
            [3, 3, 'Machine Learning Engineer', 'Pengalaman dalam pengolahan data besar dan algoritma machine learning.', 'Mengembangkan model machine learning untuk meningkatkan kinerja layanan Gojek.'],
            [4, 4, 'Data Scientist', 'Menguasai Python, R, serta alat analisis data seperti Pandas dan Numpy.', 'Bertugas untuk menganalisis data pengguna dan memberikan insight bagi perusahaan.'],
            [5, 5, 'DevOps Engineer', 'Memahami Docker, Kubernetes, dan AWS. Mampu mengelola sistem operasi dan infrastruktur.', 'Bertanggung jawab dalam mengelola infrastruktur dan pipeline CI/CD untuk aplikasi.'],
            [6, 6, 'UI/UX Designer', 'Berpengalaman dalam desain UI/UX dengan menggunakan tools seperti Figma atau Adobe XD.', 'Membantu merancang antarmuka yang user-friendly serta pengalaman pengguna yang memuaskan.'],
            [7, 7, 'Cyber Security Engineer', 'Berpengalaman dalam pengujian penetrasi dan pengelolaan keamanan sistem.', 'Melakukan audit dan pengujian keamanan serta mengembangkan sistem keamanan aplikasi.'],
            [8, 8, 'Mobile Developer', 'Pengalaman dalam pengembangan aplikasi Android atau iOS dengan Kotlin atau Swift.', 'Membangun aplikasi mobile yang intuitif dan ramah pengguna untuk platform Android dan iOS.'],
        ];

        foreach ($lowonganMagang as $lowongan) {

            DB::table('lowongan_magang')->insert([
                'id_perusahaan' => $lowongan[0],
                'id_bidang' => $lowongan[1],
                'nama' => $lowongan[2],
                'persyaratan' => $lowongan[3],
                'deskripsi' => $lowongan[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
