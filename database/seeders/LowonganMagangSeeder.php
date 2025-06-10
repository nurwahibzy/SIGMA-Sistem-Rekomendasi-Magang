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
            [1, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [1, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [1, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [1, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [1, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [1, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [1, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [1, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [2, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [2, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [2, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [2, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [2, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [2, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [2, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [2, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [3, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [3, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [3, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [3, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [3, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [3, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [3, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [3, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [4, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [4, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [4, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [4, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [4, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [4, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [4, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [4, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [5, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [5, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [5, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [5, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [5, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [5, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [5, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [5, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [6, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [6, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [6, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [6, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [6, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [6, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [6, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [6, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [7, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [7, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [7, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [7, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [7, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [7, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [7, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [7, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [8, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [8, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [8, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [8, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [8, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [8, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [8, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [8, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [9, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [9, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [9, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [9, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [9, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [9, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [9, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [9, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [10, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [10, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [10, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [10, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [10, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [10, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [10, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [10, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [11, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [11, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [11, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [11, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [11, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [11, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [11, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [11, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [12, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [12, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [12, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [12, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [12, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [12, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [12, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [12, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [13, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [13, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [13, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [13, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [13, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [13, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [13, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [13, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [14, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [14, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [14, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [14, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [14, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [14, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [14, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [14, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [15, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [15, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [15, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [15, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [15, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [15, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [15, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [15, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [16, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [16, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [16, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [16, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [16, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [16, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [16, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [16, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [17, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [17, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [17, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [17, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [17, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [17, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [17, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [17, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [18, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [18, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [18, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [18, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [18, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [18, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [18, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [18, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [19, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [19, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [19, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [19, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [19, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [19, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [19, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [19, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],

            [20, 1, 'Front End Developer', 'Pengalaman menggunakan ReactJS dan VueJS, mampu bekerja dalam tim.', 'Membangun antarmuka pengguna menggunakan ReactJS/VueJS.'],
            [20, 2, 'Back End Developer', 'Menguasai Node.js, Laravel atau Spring Boot.', 'Membangun API dan sistem backend yang scalable.'],
            [20, 4, 'Data Science', 'Mampu menggunakan Excel, SQL, dan Python.', 'Menganalisis data dan membuat laporan performa.'],
            [20, 6, 'UI/UX Designer', 'Menguasai Figma atau Adobe XD.', 'Mendesain antarmuka dan pengalaman pengguna yang optimal.'],
            [20, 8, 'Mobile Developer', 'Pengalaman di Android Studio atau Swift.', 'Membangun aplikasi mobile Android/iOS.'],
            [20, 7, 'Cyber Security', 'Memahami dasar keamanan jaringan dan web.', 'Mengidentifikasi kerentanan sistem dan mitigasi risiko.'],
            [20, 5, 'DevOps Engineer', 'Mampu mengoperasikan Docker, Git, dan CI/CD.', 'Mengelola infrastruktur dan proses deployment.'],
            [20, 3, 'Machine Learning', 'Kemampuan menulis yang baik dan paham SEO.', 'Membuat konten artikel, blog, atau sosial media.'],
        ];

        foreach ($positions as $position) {
            DB::table('lowongan_magang')->insert([
                'id_perusahaan' => $position[0],
                'id_bidang' => $position[1],
                'nama' => $position[2],
                'persyaratan' => $position[3],
                'deskripsi' => $position[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // }
    }
}
