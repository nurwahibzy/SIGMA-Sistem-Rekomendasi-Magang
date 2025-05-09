<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perusahaan = [
            ['Telkom Indonesia', 1, '0211111111', 'Jakarta', 'Jakarta Selatan', '-6.2275', '106.8321',
                'Telkom Indonesia merupakan perusahaan milik negara yang bergerak di bidang telekomunikasi, menyediakan layanan internet, komunikasi data, dan berbagai solusi digital untuk mendukung transformasi digital nasional.'],
            ['Bank Mandiri', 1, '0212222222', 'Jakarta', 'Jakarta Pusat', '-6.1907', '106.8310',
                'Bank Mandiri adalah salah satu bank terbesar milik BUMN di Indonesia yang menyediakan layanan perbankan lengkap mulai dari individu, UMKM hingga korporat, serta terus berinovasi melalui layanan digital banking.'],
            ['Gojek', 2, '0213333333', 'Jakarta', 'Jakarta Selatan', '-6.2607', '106.7816',
                'Gojek adalah perusahaan teknologi asal Indonesia yang menyediakan berbagai layanan on-demand seperti transportasi, pengantaran makanan, dan dompet digital melalui satu aplikasi terintegrasi.'],
            ['Tokopedia', 2, '0214444444', 'Jakarta', 'Jakarta Barat', '-6.1751', '106.8650',
                'Tokopedia merupakan platform marketplace besar di Indonesia yang menghubungkan jutaan penjual dan pembeli untuk bertransaksi secara online, serta mendukung UMKM dalam memperluas pasar.'],
            ['Kementerian Kominfo', 3, '0215555555', 'Jakarta', 'Jakarta Pusat', '-6.1865', '106.8345',
                'Kementerian Komunikasi dan Informatika (Kominfo) adalah lembaga pemerintah yang bertanggung jawab dalam pengembangan infrastruktur dan regulasi komunikasi dan informasi di Indonesia.'],
            ['Kementerian Riset dan Teknologi', 3, '0216666666', 'Jakarta', 'Jakarta Pusat', '-6.1828', '106.8329',
                'Kementerian Riset dan Teknologi bertugas mengembangkan riset nasional, inovasi teknologi, dan peningkatan sumber daya manusia untuk mendukung kemajuan ilmu pengetahuan di Indonesia.'],
            ['Dicoding Indonesia', 2, '0227777777', 'Jawa Barat', 'Bandung', '-6.9147', '107.6098',
                'Dicoding adalah platform edukasi teknologi yang menyediakan kursus pemrograman berkualitas dan diakui industri, menjadi mitra resmi Google dan berbagai perusahaan besar lainnya.'],
            ['Ruangguru', 2, '0218888888', 'Jakarta', 'Jakarta Selatan', '-6.2442', '106.8000',
                'Ruangguru adalah perusahaan edtech yang menyediakan platform belajar daring interaktif untuk siswa SD hingga SMA dengan fitur video pembelajaran, latihan soal, dan bimbingan belajar.'],
            ['Shopee Indonesia', 2, '0219999999', 'Jakarta', 'Jakarta Barat', '-6.1754', '106.8272',
                'Shopee merupakan platform e-commerce terkemuka di Asia Tenggara yang menyediakan pengalaman belanja online dengan fitur gratis ongkir, flash sale, dan berbagai metode pembayaran digital.'],
            ['Bukalapak', 2, '0211231234', 'Jakarta', 'Jakarta Selatan', '-6.2612', '106.8103',
                'Bukalapak adalah perusahaan teknologi Indonesia yang fokus pada pemberdayaan warung dan UMKM melalui solusi digital untuk penjualan, pembayaran, dan pengelolaan bisnis.'],
            ['Traveloka', 2, '0214564567', 'Jakarta', 'Jakarta Barat', '-6.1894', '106.8236',
                'Traveloka adalah platform travel dan lifestyle terkemuka di Asia Tenggara yang menawarkan layanan pemesanan tiket pesawat, hotel, hiburan, dan pembayaran digital dalam satu aplikasi.'],
            ['OVO', 2, '0216543210', 'Jakarta', 'Jakarta Pusat', '-6.2088', '106.8456',
                'OVO adalah layanan dompet digital yang memungkinkan pengguna melakukan transaksi non-tunai di berbagai merchant dan platform online dengan cepat, aman, dan praktis.'],
            ['Dana Indonesia', 2, '0213216549', 'Jakarta', 'Jakarta Barat', '-6.2011', '106.8451',
                'DANA merupakan platform pembayaran digital Indonesia yang menyediakan layanan transaksi keuangan seperti transfer, pembayaran tagihan, dan QRIS dengan fitur keamanan tinggi.'],
            ['Telkomsel', 1, '0217894561', 'Jakarta', 'Jakarta Selatan', '-6.2412', '106.8372',
                'Telkomsel adalah operator seluler terbesar di Indonesia yang menyediakan layanan komunikasi berbasis GSM dan internet seluler berkualitas hingga pelosok negeri.'],
            ['Pertamina', 1, '0211470000', 'Jakarta', 'Jakarta Pusat', '-6.2000', '106.8200',
                'Pertamina adalah perusahaan BUMN di bidang energi yang menangani eksplorasi, produksi, dan distribusi minyak serta gas di seluruh Indonesia.'],
            ['BNI', 1, '0218745632', 'Jakarta', 'Jakarta Timur', '-6.2100', '106.8700',
                'BNI adalah salah satu bank milik negara yang memiliki jaringan internasional dan menyediakan berbagai layanan keuangan, termasuk investasi dan pinjaman usaha.'],
            ['BRI', 1, '0219876543', 'Jakarta', 'Jakarta Pusat', '-6.1900', '106.8300',
                'BRI fokus pada pembiayaan UMKM di seluruh Indonesia dan menjadi bank dengan jaringan terluas serta berperan dalam inklusi keuangan nasional.'],
            ['ShopeePay', 2, '0213452345', 'Jakarta', 'Jakarta Barat', '-6.1770', '106.8275',
                'ShopeePay adalah layanan e-wallet yang terintegrasi dengan aplikasi Shopee, memungkinkan pengguna melakukan pembayaran digital secara cepat dan aman.'],
            ['UNESCO Indonesia', 6, '0217654321', 'Jakarta', 'Jakarta Pusat', '-6.1999', '106.8321',
                'UNESCO Indonesia adalah organisasi internasional yang berfokus pada pendidikan, ilmu pengetahuan, dan budaya dengan tujuan membangun perdamaian melalui kolaborasi global.'],
            ['Universitas Indonesia', 5, '0211239876', 'Jawa Barat', 'Depok', '-6.3686', '106.8272',
                'Universitas Indonesia adalah perguruan tinggi ternama yang menyediakan program pendidikan berkualitas serta mendukung riset dan pengabdian masyarakat di berbagai bidang ilmu.'],
        ];

        foreach ($perusahaan as $p) {
            $slugifiedName = Str::slug($p[0], '_'); // Mengubah nama menjadi nama_file
            DB::table('perusahaan')->insert([
                'nama' => $p[0],
                'id_jenis' => $p[1],
                'telepon' => $p[2],
                'deskripsi' => $p[7],
                'foto_path' => 'images/perusahaan/' . $slugifiedName . '.jpg',
                'provinsi' => $p[3],
                'daerah' => $p[4],
                'latitude' => $p[5],
                'longitude' => $p[6],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
