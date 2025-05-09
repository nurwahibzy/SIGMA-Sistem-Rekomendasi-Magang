<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeahlianMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keahlianMahasiswa = [
            [1, 1, 'Pengalaman menggunakan HTML, CSS, dan JavaScript untuk membangun aplikasi web.'],
            [1, 2, 'Mampu bekerja dengan Node.js, Express, serta menggunakan database MySQL untuk pengembangan backend.'], 
            [1, 3, 'Pernah mengembangkan model machine learning dengan menggunakan Python dan library seperti TensorFlow dan Scikit-learn.'],
            [1, 4, 'Menguasai analisis data menggunakan Python dengan Pandas, Numpy, dan Matplotlib.'],  
            [1, 5, 'Berpengalaman dalam penggunaan Docker, Kubernetes, dan Jenkins untuk otomatisasi deployment aplikasi.'], 
            [1, 6, 'Berpengalaman dalam mendesain antarmuka pengguna menggunakan tools seperti Figma dan Adobe XD.'], 
            [1, 7, 'Pernah melakukan penetration testing dan audit keamanan aplikasi menggunakan tools seperti Kali Linux.'], 
            [1, 8, 'Membangun aplikasi mobile menggunakan React Native untuk platform Android dan iOS.'],
        ];

        foreach ($keahlianMahasiswa as $keahlian) {
            DB::table('keahlian_mahasiswa')->insert([
                'id_mahasiswa' => $keahlian[0],
                'id_bidang' => $keahlian[1],
                'keahlian' => $keahlian[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
