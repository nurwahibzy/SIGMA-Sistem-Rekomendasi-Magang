<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JarakController;
use App\Models\AkunModel;
use App\Models\BidangModel;
use App\Models\KeahlianMahasiswaModel;
use App\Models\LowonganMagangModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeMagangModel;
use App\Models\PerusahaanModel;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{

    protected $prosesPerhitungan = [];
    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }

    public function hitungRekomendasiMagang()
    {
        $id_mahasiswa = $this->idMahasiswa();

        // Ambil data mahasiswa lengkap dengan preferensi dan keahliannya
        $mahasiswa = MahasiswaModel::with(['preferensi_lokasi_mahasiswa', 'keahlian_mahasiswa'])->findOrFail($id_mahasiswa);

        // Ambil semua data perusahaan dan lowongan yang terhubung dengan periode magang dan penilaian
        $perusahaans = PerusahaanModel::with([
            'jenis_perusahaan',
            'lowongan_magang.periode_magang.magang' => function ($q) {
                $q->with('penilaian');
            }
        ])->get();

        // Ambil preferensi keahlian mahasiswa berdasarkan prioritas
        $preferensiKeahlianMahasiswa = KeahlianMahasiswaModel::with(['bidang'])
            ->where('id_mahasiswa', $id_mahasiswa)
            ->orderBy('prioritas', 'asc')
            ->get(['id_bidang', 'prioritas']);

        // Validasi jika belum ada preferensi
        if ($preferensiKeahlianMahasiswa->isEmpty()) {
            return redirect()->route('mahasiswa.profil')->with('error', 'Silakan atur preferensi keahlian terlebih dahulu.');
        }

        $totalPrioritas = $preferensiKeahlianMahasiswa->count();
        $hariIni = date('Y-m-d');

        // Hitung bobot prioritas bidang
        $bobot_prioritas = [];
        foreach ($preferensiKeahlianMahasiswa as $preferensi) {
            $bobot_prioritas[$preferensi->id_bidang] = $preferensi->prioritas;
        }

        // Definisi kriteria untuk perhitungan
        $kriteria = [
            (object) ['id' => 'bidang_prioritas', 'type' => 'cost'],
            (object) ['id' => 'fasilitas', 'type' => 'benefit'],
            (object) ['id' => 'tugas', 'type' => 'benefit'],
            (object) ['id' => 'pembinaan', 'type' => 'benefit'],
            (object) ['id' => 'jarak', 'type' => 'cost']
        ];
        $this->prosesPerhitungan = ['kriteria' => $kriteria];

        $data_array = [];

        // Loop untuk setiap perusahaan dan lowongan
        foreach ($perusahaans as $perusahaan) {
            // Hitung rata-rata penilaian dari seluruh magang
            $total_fasilitas = 0;
            $total_tugas = 0;
            $total_kedisiplinan = 0;
            $total_penilaian = 0;

            foreach ($perusahaan->lowongan_magang as $lowongan) {
                foreach ($lowongan->periode_magang as $periode) {
                    foreach ($periode->magang as $magang) {
                        if ($magang->penilaian) {
                            $total_fasilitas += (int) $magang->penilaian->fasilitas;
                            $total_tugas += (int) $magang->penilaian->tugas;
                            $total_kedisiplinan += (int) $magang->penilaian->kedisiplinan;
                            $total_penilaian++;
                        }
                    }
                }
            }

            // Inisialisasi nilai rata-rata
            $rata_fasilitas = 0;
            $rata_tugas = 0;
            $rata_kedisiplinan = 0;

            if ($total_penilaian > 0) {
                $rata_fasilitas = $total_fasilitas / $total_penilaian;
                $rata_tugas = $total_tugas / $total_penilaian;
                $rata_kedisiplinan = $total_kedisiplinan / $total_penilaian;
            }


            foreach ($perusahaan->lowongan_magang as $lowongan) {

                // Lewati jika semua periode sudah berjalan
                $upcoming = $lowongan->periode_magang->filter(fn($periode) => $periode->tanggal_mulai > $hariIni);
                if ($upcoming->isEmpty())
                    continue;


                // Hitung jarak
                $jarak = JarakController::hitungJarak($perusahaan, $mahasiswa);

                $id_bidang = $lowongan->id_bidang;
                $bobot_bidang = $bobot_prioritas[$id_bidang] ?? ($totalPrioritas + 1);

                // Simpan data
                $data_array[] = [
                    'id_perusahaan' => $perusahaan->id_perusahaan,
                    'nama_perusahaan' => $perusahaan->nama,
                    'jenis_perusahaan' => $perusahaan->jenis_perusahaan->id_jenis,
                    'id_lowongan' => $lowongan->id_lowongan,
                    'nama_lowongan' => $lowongan->nama,
                    'prioritas_keahlian' => $bobot_bidang,
                    'jarak' => $jarak,
                    'fasilitas' => $rata_fasilitas,
                    'tugas' => $rata_tugas,
                    'kedisiplinan' => $rata_kedisiplinan,
                ];
            }
        }

        // $data_array = array_slice($data_array, 0, 8);
        // Proses perhitungan keputusan MEREC & ARAS
        $matriksKeputusan = $this->bangunMatriksKeputusan($data_array, $kriteria);


        $normalisasiMatriks = $this->normalisasiMerec($matriksKeputusan, $kriteria);
        $this->hitungIntermediateMerec($normalisasiMatriks, $kriteria);
        $bobot = $this->hitungBobotMerec($normalisasiMatriks, $kriteria);


        $normalisasiAras = $this->normalisasiAras($matriksKeputusan, $kriteria);
        $this->hitungIntermediateAras($normalisasiAras, $bobot, $data_array, $kriteria);
        $peringkat = $this->hitungAras($normalisasiAras, $bobot, $data_array, $kriteria);

        return $peringkat;
    }

    public function tampilkanHasilRekomendasi()
    {
        $peringkat = $this->hitungRekomendasiMagang();

        // return response()->json($peringkat);

        // Ambil 5 lowongan terbaik berdasarkan peringkat
        $topLowonganIds = array_column(array_slice($peringkat, 0, 5), 'id_lowongan');

        // Ambil detail periode untuk lowongan terbaik
        $periode = PeriodeMagangModel::with([
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
            'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
            'lowongan_magang.bidang:id_bidang,nama',
            'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        ])
            ->whereIn('id_lowongan', $topLowonganIds)
            ->get(['id_periode', 'id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);

        // Hitung data statistik
        $perusahaans = PerusahaanModel::with('jenis_perusahaan')->get();

        return view('mahasiswa.periode.index', [
            'periode' => $periode,
            'activeMenu' => 'dashboard',
            'jumlahPerusahaan' => $perusahaans->count(),
            'jumlahJenisPerusahaan' => $perusahaans->pluck('jenis_perusahaan.id_jenis')->unique()->count(),
            'jumlahBidang' => BidangModel::count(),
            'perhitungan' => true
        ]);
    }


    // Fungsi untuk membangun matriks keputusan dari data alternatif dan nilai kriteria
    private function bangunMatriksKeputusan($alternatif, $kriteria)
    {
        $matriks = [];
        foreach ($alternatif as $alt) {
            $matriks[$alt['id_lowongan']][$kriteria[0]->id] = $alt['prioritas_keahlian'];
            $matriks[$alt['id_lowongan']][$kriteria[1]->id] = $alt['fasilitas'];
            $matriks[$alt['id_lowongan']][$kriteria[2]->id] = $alt['tugas'];
            $matriks[$alt['id_lowongan']][$kriteria[3]->id] = $alt['kedisiplinan'];
            $matriks[$alt['id_lowongan']][$kriteria[4]->id] = $alt['jarak'];
        }
        $this->prosesPerhitungan["matriksKeputusan"] = $matriks;
        return $matriks;
    }

    private function normalisasiMerec($matriks, $kriteria)
    {
        $hasil = [];
        foreach ($kriteria as $krit) {
            $kolomNilai = array_column($matriks, $krit->id);
            $min = min($kolomNilai);
            // dd($min);
            $max = max($kolomNilai);

            foreach ($matriks as $idAlt => $nilai) {
                $v = $nilai[$krit->id];
                if ($max == $min ) {
                    $hasil[$idAlt][$krit->id] = 0;
                } else {
                    if ($krit->type === 'benefit') {
                        $hasil[$idAlt][$krit->id] = $max == 0 ? 0 : $v / $max  ;
                    } else {
                        $hasil[$idAlt][$krit->id] = $v == 0 ? 0 : $min / $v;
                    }
                }
            }
        }
        $this->prosesPerhitungan["normalisasiMerec"] = $hasil;
        return $hasil;
    }

    // Intermediate MEREC
    private function hitungIntermediateMerec($normal, $kriteria)
    {
        $initial_s_j = [];
        $s_prime_j = [];
        $e_j = [];

        // Hitung initial_s_j: Total utilitas semua alternatif dengan semua kriteria
        foreach ($normal as $idAlt => $nilai) {
            $initial_s_j[$idAlt] = array_sum($nilai);
        }

        // Hitung s_prime_j: total utilitas setelah penghapusan masing-masing kriteria
        foreach ($kriteria as $krit) {
            foreach ($normal as $idAlt => $nilai) {
                $temp = $nilai;
                unset($temp[$krit->id]);
                $s_prime_j[$idAlt][$krit->id] = array_sum($temp);
            }
        }

        // Hitung e_j: pengaruh atau selisih mutlak tiap kriteria
        foreach ($kriteria as $krit) {
            $selisih = 0;
            foreach ($normal as $idAlt => $nilai) {
                $selisih += abs($initial_s_j[$idAlt] - $s_prime_j[$idAlt][$krit->id]);
            }
            $e_j[$krit->id] = $selisih;
        }

        $this->prosesPerhitungan["initial_s_j"] = $initial_s_j;
        $this->prosesPerhitungan["s_prime_j"] = $s_prime_j;
        $this->prosesPerhitungan["e_j"] = $e_j;

        return [$initial_s_j, $s_prime_j, $e_j];
    }

    private function hitungBobotMerec($normal, $kriteria)
    {
        // Total utilitas dengan semua kriteria
        $totalSemua = [];
        foreach ($normal as $idAlt => $nilai) {
            $totalSemua[$idAlt] = array_sum($nilai);
        }

        // Hitung efek penghapusan tiap kriteria
        $pengaruh = [];
        foreach ($kriteria as $krit) {
            $selisih = 0;
            foreach ($normal as $idAlt => $nilai) {
                $nilaiTanpa = $nilai;
                unset($nilaiTanpa[$krit->id]);
                $partial = array_sum($nilaiTanpa);
                $selisih += abs($totalSemua[$idAlt] - $partial);
            }
            $pengaruh[$krit->id] = $selisih;
        }

        // Normalisasi efek menjadi bobot
        $totalPengaruh = array_sum($pengaruh);
        $bobot = [];
        foreach ($kriteria as $krit) {
            $bobot[$krit->id] = $totalPengaruh == 0 ? 0 : $pengaruh[$krit->id] / $totalPengaruh;
        }
        $this->prosesPerhitungan["bobotMerec"] = $bobot;
        return $bobot;
    }

    private function normalisasiAras($matriks, $kriteria)
    {
        $hasil = [];
        foreach ($kriteria as $krit) {

            $kolomNilai = array_column($matriks, $krit->id);
            $min = min($kolomNilai);
            // dd($min);
            $max = max($kolomNilai);

            foreach ($matriks as $idAlt => $nilai) {

                $v = $nilai[$krit->id];
                if ($max == $min ) {
                    $hasil[$idAlt][$krit->id] = 0;
                } else {
                    if ($krit->type === 'benefit') {
                        $hasil[$idAlt][$krit->id] = $max == 0 ? 0 : $v / $max  ;
                    } else {
                        $hasil[$idAlt][$krit->id] = $v == 0 ? 0 : $min / $v;
                    }
                }
            }
        }
        $this->prosesPerhitungan["normalisasiAras"] = $hasil;
        return $hasil;
    }

    // Intermediate ARAS
    private function hitungIntermediateAras($normal, $bobot, $alternatif, $kriteria)
    {
        $weighted = [];
        $optimal = [];
        $utility = [];

        // Weighted normalized matrix
        foreach ($normal as $idAlt => $nilai) {
            foreach ($kriteria as $krit) {
                $weighted[$idAlt][$krit->id] = $nilai[$krit->id] * $bobot[$krit->id];
            }
        }

        // Optimality function (Si)
        foreach ($alternatif as $alt) {
            $id = $alt['id_lowongan'];
            $optimal[$id] = array_sum($weighted[$id]);
        }

        // Ideal solution (S0)
        $ideal = [];
        foreach ($kriteria as $krit) {
            $kolom = array_column($normal, $krit->id);
            $max = max($kolom);

            $ideal[$krit->id] =  $max == 0 ? 0 : 1;
        }
        $S0 = 0;
        foreach ($kriteria as $krit) {
            $S0 += $bobot[$krit->id] * $ideal[$krit->id];
        }

        // Utility degree (Ki)
        foreach ($alternatif as $alt) {
            $id = $alt['id_lowongan'];
            $utility[$id] = $S0 == 0 ? 0 : $optimal[$id] / $S0;
        }

        $this->prosesPerhitungan["weighted_normalized_matrix"] = $weighted;
        $this->prosesPerhitungan["optimality_function"] = $optimal;
        $this->prosesPerhitungan["utility_degree"] = $utility;

        return [$weighted, $optimal, $utility];
    }

    private function hitungAras($normal, $bobot, $alternatif, $kriteria)
    {
        // Hitung nilai ideal (S0)
        $ideal = [];
        foreach ($kriteria as $krit) {
            $kolom = array_column($normal, $krit->id);
            $max = max($kolom);

            $ideal[$krit->id] =  $max == 0 ? 0 : 1;
        }

        // Hitung skor ideal (S0)
        $S0 = 0;
        foreach ($kriteria as $krit) {
            $S0 += $bobot[$krit->id] * $ideal[$krit->id];
        }

        // Hitung skor utilitas tiap alternatif (Ki)
        $hasil = [];
        foreach ($alternatif as $alt) {
            $Si = 0;
            foreach ($kriteria as $krit) {
                $Si += $bobot[$krit->id] * $normal[$alt['id_lowongan']][$krit->id];
            }

            $Ki = $S0 == 0 ? 0 : $Si / $S0;

            $hasil[] = [
                'id_lowongan' => $alt['id_lowongan'],
                'alternatif' => $alt['nama_perusahaan'] . '-' . $alt['nama_lowongan'],
                'skor' => round($Ki, 4)
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);

        $this->prosesPerhitungan["hasil"] = $hasil;
        return $hasil;
    }
    public function prosesPerhitungan()
    {
        // Pastikan prosesPerhitungan sudah diisi sebelumnya
        $this->hitungRekomendasiMagang();
        if (!empty($this->prosesPerhitungan)) {
            // Kembalikan view dengan data perhitungan
            // dd($this->prosesPerhitungan);
            return response()->view('mahasiswa.spk.perhitungan', ['data' => $this->prosesPerhitungan]);
        }
    }
}
