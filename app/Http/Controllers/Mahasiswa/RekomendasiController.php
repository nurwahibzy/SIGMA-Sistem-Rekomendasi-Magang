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
        $mahasiswa = MahasiswaModel::with(['preferensi_lokasi_mahasiswa', 'keahlian_mahasiswa', 'preferensi_perusahaan_mahasiswa'])->findOrFail($id_mahasiswa);

        if (!count($mahasiswa->preferensi_perusahaan_mahasiswa)) {
            return false;
        }

        // Ambil semua data perusahaan dan lowongan yang terhubung dengan periode magang dan penilaian
        $preferensiPerusahaanMahasiswa = $mahasiswa->preferensi_perusahaan_mahasiswa;
        $idJenisArray = $preferensiPerusahaanMahasiswa->pluck('id_jenis')->toArray();
        $perusahaans = PerusahaanModel::with([
            'jenis_perusahaan',
            'lowongan_magang.periode_magang.magang.penilaian'
        ])
            ->whereHas('jenis_perusahaan', function ($query) use ($idJenisArray) {
                $query->whereIn('id_jenis', $idJenisArray);
            })
            ->get();


        // Ambil preferensi keahlian mahasiswa berdasarkan prioritas
        $preferensiKeahlianMahasiswa = KeahlianMahasiswaModel::with(['bidang'])
            ->where('id_mahasiswa', $id_mahasiswa)
            ->orderBy('prioritas', 'asc')
            ->get(['id_bidang', 'prioritas']);

        // Validasi jika belum ada preferensi
        if (!count($preferensiKeahlianMahasiswa)) {
            return false;
        }

        $totalPrioritas = $preferensiKeahlianMahasiswa->count();
        $hariIni = now();

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

        $global_total_fasilitas = 0;
        $global_total_tugas = 0;
        $global_total_pembinaan = 0;
        $global_total_penilaian = 0;

        // First pass: Calculate global averages
        foreach ($perusahaans as $perusahaan) {
            foreach ($perusahaan->lowongan_magang as $lowongan) {
                foreach ($lowongan->periode_magang as $periode) {
                    foreach ($periode->magang as $magang) {
                        if ($magang->penilaian) {
                            $global_total_fasilitas += (int) $magang->penilaian->fasilitas;
                            $global_total_tugas += (int) $magang->penilaian->tugas;
                            $global_total_pembinaan += (int) $magang->penilaian->pembinaan;
                            $global_total_penilaian++;
                        }
                    }
                }
            }
        }

        // Calculate global averages
        $global_avg_fasilitas = $global_total_penilaian > 0 ? $global_total_fasilitas / $global_total_penilaian : 2.5;
        $global_avg_tugas = $global_total_penilaian > 0 ? $global_total_tugas / $global_total_penilaian : 2.5;
        $global_avg_pembinaan = $global_total_penilaian > 0 ? $global_total_pembinaan / $global_total_penilaian : 2.5;

        // Second pass: Process data with imputation
        foreach ($perusahaans as $perusahaan) {
            $jarak = JarakController::hitungJarak($perusahaan, $mahasiswa);

            foreach ($perusahaan->lowongan_magang as $lowongan) {
                // Skip if all periods have passed
                $upcoming = $lowongan->periode_magang->filter(fn($periode) => $periode->tanggal_mulai >= $hariIni);
                if ($upcoming->isEmpty()) {
                    continue;
                }

                $total_fasilitas = 0;
                $total_tugas = 0;
                $total_pembinaan = 0;
                $total_penilaian = 0;

                foreach ($lowongan->periode_magang as $periode) {
                    foreach ($periode->magang as $magang) {
                        if ($magang->penilaian) {
                            $total_fasilitas += (int) $magang->penilaian->fasilitas;
                            $total_tugas += (int) $magang->penilaian->tugas;
                            $total_pembinaan += (int) $magang->penilaian->pembinaan;
                            $total_penilaian++;
                        }
                    }
                }

                // Use local average if available, otherwise use global average
                $rata_fasilitas = $total_penilaian > 0
                    ? $total_fasilitas / $total_penilaian
                    : $global_avg_fasilitas;

                $rata_tugas = $total_penilaian > 0
                    ? $total_tugas / $total_penilaian
                    : $global_avg_tugas;

                $rata_pembinaan = $total_penilaian > 0
                    ? $total_pembinaan / $total_penilaian
                    : $global_avg_pembinaan;

                $id_bidang = $lowongan->id_bidang;
                $bobot_bidang = $bobot_prioritas[$id_bidang] ?? ($totalPrioritas + 1);

                $data_array[] = [
                    'id_perusahaan' => $perusahaan->id_perusahaan,
                    'nama_perusahaan' => $perusahaan->nama,
                    'jenis_perusahaan' => $perusahaan->jenis_perusahaan->id_jenis,
                    'id_lowongan' => $lowongan->id_lowongan,
                    'bidang' => $lowongan->bidang->nama,
                    'prioritas_keahlian' => $bobot_bidang,
                    'jarak' => $jarak,
                    'fasilitas' => $rata_fasilitas,
                    'tugas' => $rata_tugas,
                    'pembinaan' => $rata_pembinaan,
                ];
            }
        }

        // $data_array = array_slice($data_array, 0, 8);
        // Proses perhitungan keputusan MEREC & ARAS
        $matriksKeputusan = $this->bangunMatriksKeputusan($data_array, $kriteria);

        if (!count($matriksKeputusan)) {
            return false;
        }
        $normalisasiMatriks = $this->normalisasiMerec($matriksKeputusan, $kriteria);
        $this->hitungIntermediateMerec($normalisasiMatriks, $kriteria);
        $bobot = $this->hitungBobotMerec($normalisasiMatriks, $kriteria);


        $normalisasiAras = $this->normalisasiAras($matriksKeputusan, $kriteria);
        $this->hitungIntermediateAras($normalisasiAras, $bobot, $data_array, $kriteria);
        $peringkat = $this->hitungAras($normalisasiAras, $bobot, $data_array, $kriteria);

        return array_slice($peringkat, 0, 3);
    }

    public function tampilkanHasilRekomendasi()
    {
        $peringkat = $this->hitungRekomendasiMagang();

        // return response()->json($peringkat);

        if ($peringkat == false) {
            return view('mahasiswa.periode.index');

            // return response(url('/mahasiswa/periode'));
            // return redirect('/mahasiswa/profil')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu. Kemungkinan Keahlian atau Preferensi Perusahaan Anda tidak ada yang sesuai');
        }

        $topLowonganIds = array_column($peringkat, 'id_lowongan');

        // Ambil detail periode untuk lowongan terbaik
        $periode = PeriodeMagangModel::with([
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
            'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah,foto_path',
            'lowongan_magang.bidang:id_bidang,nama',
            'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        ])
            ->whereIn('id_lowongan', $topLowonganIds)
            ->where('tanggal_mulai', '>=', now())
            ->orderByRaw('FIELD(id_lowongan, ' . implode(',', $topLowonganIds) . ')')
            ->get();

        // Hitung data statistik
        // $perusahaans = PerusahaanModel::with('jenis_perusahaan')->get();

        // $idPerusahaan = collect();
        // $idJenisPerusahaan = collect();
        // $idBidang = collect();

        // foreach ($periode as $p) {
        //     if ($p->lowongan_magang) {
        //         $perusahaan = $p->lowongan_magang->perusahaan;
        //         $bidang = $p->lowongan_magang->bidang;

        //         if ($perusahaan) {
        //             $idPerusahaan->push($perusahaan->id_perusahaan);
        //             if ($perusahaan->jenis_perusahaan) {
        //                 $idJenisPerusahaan->push($perusahaan->jenis_perusahaan->id_jenis);
        //             }
        //         }

        //         if ($bidang) {
        //             $idBidang->push($bidang->id_bidang);
        //         }
        //     }
        // }

        // $jumlahPerusahaan = $idPerusahaan->unique()->count();
        // $jumlahJenisPerusahaan = $idJenisPerusahaan->unique()->count();
        // $jumlahBidang = $idBidang->unique()->count();

        return view('mahasiswa.periode.index', [
            'rekomendasi' => $periode
        ]);

        // return response()->json($periode);
    }


    // Fungsi untuk membangun matriks keputusan dari data alternatif dan nilai kriteria
    private function bangunMatriksKeputusan($alternatif, $kriteria)
    {
        $matriks = [];
        foreach ($alternatif as $alt) {
            $matriks[$alt['id_lowongan']][$kriteria[0]->id] = $alt['prioritas_keahlian'];
            $matriks[$alt['id_lowongan']][$kriteria[1]->id] = $alt['fasilitas'];
            $matriks[$alt['id_lowongan']][$kriteria[2]->id] = $alt['tugas'];
            $matriks[$alt['id_lowongan']][$kriteria[3]->id] = $alt['pembinaan'];
            $matriks[$alt['id_lowongan']][$kriteria[4]->id] = $alt['jarak'];
        }
        $this->prosesPerhitungan["matriksKeputusan"] = $matriks;
        return $matriks;
    }

    private function normalisasiMerec($matriks, $kriteria)
    {
        $hasil = [];
        $minMax = [];

        // Hitung min/max tiap kriteria terlebih dahulu
        foreach ($kriteria as $krit) {
            $kolomNilai = array_column($matriks, $krit->id);
            $minMax[$krit->id] = [
                'min' => min($kolomNilai),
                'max' => max($kolomNilai),
                'type' => $krit->type
            ];
        }

        foreach ($matriks as $idAlt => $nilai) {
            foreach ($kriteria as $krit) {
                $v = $nilai[$krit->id];
                $min = $minMax[$krit->id]['min'];
                $max = $minMax[$krit->id]['max'];
                $type = $minMax[$krit->id]['type'];

                if ($type === 'benefit') {
                    $hasil[$idAlt][$krit->id] = $max == 0 ? 0 : $v / $max;
                } else {
                    $hasil[$idAlt][$krit->id] = $v == 0 ? 0 : $min / $v;
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
        $n = count($kriteria);

        // 1. Hitung initial_s_j (Si) dengan formula logaritma
        foreach ($normal as $idAlt => $nilai) {
            $sumLn = 0;
            foreach ($kriteria as $krit) {
                $r_ij = max($nilai[$krit->id], 0.000001); // Hindari log(0)
                $sumLn += abs(log($r_ij));
            }
            $initial_s_j[$idAlt] = log(1 + (1 / $n) * $sumLn);
        }

        // 2. Hitung s_prime_j (Sij') dengan menghilangkan tiap kriteria
        foreach ($kriteria as $krit) {
            foreach ($normal as $idAlt => $nilai) {
                $sumLn = 0;
                foreach ($kriteria as $k) {
                    if ($k->id == $krit->id) continue;
                    $r_ij = max($nilai[$k->id], 0.000001);
                    $sumLn += abs(log($r_ij));
                }
                $s_prime_j[$idAlt][$krit->id] = log(1 + (1 / ($n - 1)) * $sumLn);
            }
        }

        // 3. Hitung e_j (efek penghapusan)
        foreach ($kriteria as $krit) {
            $sumEj = 0;
            foreach ($normal as $idAlt => $nilai) {
                $sumEj += abs($s_prime_j[$idAlt][$krit->id] - $initial_s_j[$idAlt]);
            }
            $e_j[$krit->id] = $sumEj;
        }

        $this->prosesPerhitungan["initial_s_j"] = $initial_s_j;
        $this->prosesPerhitungan["s_prime_j"] = $s_prime_j;
        $this->prosesPerhitungan["e_j"] = $e_j;

        return [$initial_s_j, $s_prime_j, $e_j];
    }

    private function hitungBobotMerec($normal, $kriteria)
    {
        // Gunakan hasil dari hitungIntermediateMerec
        list($initial_s_j, $s_prime_j, $e_j) = $this->hitungIntermediateMerec($normal, $kriteria);

        // Hitung total efek
        $totalPengaruh = array_sum($e_j);

        // Hitung bobot
        $bobot = [];
        foreach ($kriteria as $krit) {
            $bobot[$krit->id] = $totalPengaruh == 0 ? 0 : $e_j[$krit->id] / $totalPengaruh;
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

            if ($krit->type == 'benefit') {
                $matriks[0][$krit->id] = $max;
            } else {
                $matriks[0][$krit->id] = $min;
            }
            $kolomNilai = array_column($matriks, $krit->id);

            foreach ($matriks as $idAlt => $nilai) {

                $v = $nilai[$krit->id];
                if ($max == $min) {
                    $hasil[$idAlt][$krit->id] = 0;
                } else {
                    if ($krit->type === 'benefit') {
                        $hasil[$idAlt][$krit->id] = $max == 0 ? 0 : $v / array_sum($kolomNilai);
                    } else {
                        $pembalik = array_map(fn($x) => $x == 0 ? 0 : 1 / $x, $kolomNilai);
                        $hasil[$idAlt][$krit->id] = $v == 0 ? 0 : (1 / $v) / array_sum($pembalik);
                    }
                }
            }
        }
        $this->prosesPerhitungan["matriksKeputusanAras"] = $matriks;
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
        foreach ($normal as $key => $value) {
            $id = $key;
            $optimal[$id] = array_sum($weighted[$id]);
        }

        // // Ideal solution (S0)
        // $ideal = [];
        // foreach ($kriteria as $krit) {
        //     $kolom = array_column($normal, $krit->id);
        //     $max = max($kolom);

        //     $ideal[$krit->id] = $max == 0 ? 0 : 1;
        // }
        $S0 = 0;
        foreach ($kriteria as $krit) {
            $S0 += $bobot[$krit->id] * $normal[0][$krit->id];
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
        // $ideal = [];
        // foreach ($kriteria as $krit) {
        //     $kolom = array_column($normal, $krit->id);
        //     $max = max($kolom);

        //     $ideal[$krit->id] = $max == 0 ? 0 : 1;
        // }

        // Hitung skor ideal (S0)
        $S0 = 0;
        foreach ($kriteria as $krit) {
            $S0 += $bobot[$krit->id] * $normal[0][$krit->id];
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
                'alternatif' => $alt['nama_perusahaan'] . ' - ' . $alt['bidang'],
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
