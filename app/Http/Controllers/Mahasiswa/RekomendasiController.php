<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JarakController;
use App\Models\BidangModel;
use App\Models\LowonganMagangModel;
use App\Models\MahasiswaModel;
use App\Models\PerusahaanModel;

class RekomendasiController extends Controller
{
    public function getRekomendasi($mahasiswa_id)
    {
        $mahasiswa = MahasiswaModel::with(['preferensi_lokasi_mahasiswa', 'keahlian_mahasiswa'])->findOrFail($mahasiswa_id);
        $preferensiJenisPerusahaanMahasiswa = $mahasiswa->preferensi_perusahaan_mahasiswa->pluck('id_jenis')->toArray();
        $perusahaans = PerusahaanModel::with(['jenis_perusahaan', 'lowongan_magang'])->get();
        $preferensiKeahlianMahasiswa = BidangModel::all();
        $totalPrioritas = $preferensiKeahlianMahasiswa->count();
        $hariIni = date('Y-m-d');
        $lowonganMagang = LowonganMagangModel::with(['periode_magang.magang'])
            ->whereHas('periode_magang', function ($query) use ($hariIni) {
                $query->where('tanggal_mulai', '>', $hariIni); // Belum dilaksanakan
            })->get();


        // dd($hariIni,$lowonganMagang);
        // dd($preferensiKeahlianMahasiswa);
        // return response()->json($preferensiKeahlianMahasiswa);

        // $bobot_prioritas = [];
        // for ($i = 0; $i < $totalPrioritas; $i++) {
        //     // Mahasiswa ke-i memiliki prioritas ke-i+1
        //     $prioritas = $preferensiKeahlianMahasiswa[$i]->prioritas;
        //     $bobot_prioritas[$prioritas] = $totalPrioritas - $i;
        // }

        // dd($bobot_prioritas);

        $kriteria = [
            (object)['id' => 'jenis_perusahaan', 'type' => 'cost'],
            // (object)['id' => 'bidang_prioritas', 'type' => 'cost'],
            (object)['id' => 'fasilitas', 'type' => 'benefit'],
            (object)['id' => 'tugas', 'type' => 'benefit'],
            (object)['id' => 'kedisiplinan', 'type' => 'benefit'],
            (object)['id' => 'jarak', 'type' => 'cost']
        ];

        $data_array = [];

        foreach ($perusahaans as $perusahaan) {
            foreach ($perusahaan->lowongan_magang as $lowongan) {

                // Skip semua lowongan yang periodenya sudah dimulai
                $upcoming = $lowongan->periode_magang->filter(function ($periode) use ($hariIni) {
                    return $periode->tanggal_mulai > $hariIni;
                });

                if ($upcoming->isEmpty()) {
                    continue; // Lewati lowongan yang waktu pelaksanaannya sudah dimulai
                }

                $penilaian = null;
                $nilai_fasilitas = 0;
                $nilai_tugas = 0;
                $nilai_kedisiplinan = 0;

                foreach ($lowongan->periode_magang as $periode) {
                    foreach ($periode->magang as $magang) {
                        if ($magang->penilaian) {
                            $penilaian = $magang->penilaian;
                            $nilai_fasilitas = (int) $penilaian->fasilitas;
                            $nilai_tugas = (int) $penilaian->tugas;
                            $nilai_kedisiplinan = (int) $penilaian->kedisiplinan;
                            break 2; // Ambil satu penilaian pertama saja
                        }
                    }
                }

                $jarak = JarakController::hitungJarak($perusahaan, $mahasiswa);

                // foreach ($preferensiKeahlianMahasiswa->prioritas as $bidang) {
                //     foreach ($preferensiKeahlianMahasiswa as $preferensi) {
                //         if ($bidang->id_bidang == $preferensi->id_bidang) {
                //             $prioritas = $preferensi->prioritas;
                //             $bobot_bidang = $bobot_prioritas[$prioritas] ?? 0;
                //             break 2;
                //         }
                //     }
                // }

                $data_array[] = [
                    'id_perusahaan' => $perusahaan->id_perusahaan,
                    'nama_perusahaan' => $perusahaan->nama,
                    'jenis_perusahaan' => $perusahaan->jenis_perusahaan->id_jenis,
                    'id_lowongan' => $lowongan->id_lowongan,
                    'nama_lowongan' => $lowongan->nama,
                    // 'prioritas_keahlian' => $bobot_bidang,
                    'jarak' => $jarak,
                    'fasilitas' => $nilai_fasilitas,
                    'tugas' => $nilai_tugas,
                    'kedisiplinan' => $nilai_kedisiplinan
                ];
            }
        }


        // Bangun matriks keputusan dari data
        $matriksKeputusan = $this->bangunMatriksKeputusan($data_array, $kriteria);

        $normalisasiMatriks = $this->normalisasiMerec($matriksKeputusan, $kriteria);

        $bobot = $this->hitungBobotMerec($normalisasiMatriks, $kriteria);

        $normalisasiAras = $this->normalisasiAras($matriksKeputusan, $kriteria);

        $peringkat = $this->hitungAras($normalisasiAras, $bobot, $data_array, $kriteria);

        // dd($data_array);
        // dd($peringkat);

        return response()->json($peringkat); // Mengembalikan 10 rekomendasi teratas
    }

    // Fungsi untuk membangun matriks keputusan dari data alternatif dan nilai kriteria
    private function bangunMatriksKeputusan($alternatif, $kriteria)
    {
        $matriks = [];
        foreach ($alternatif as $alt) {
            $matriks[$alt['id_perusahaan']][$kriteria[0]->id] = $alt['jenis_perusahaan'];
            $matriks[$alt['id_perusahaan']][$kriteria[1]->id] = $alt['fasilitas'];
            $matriks[$alt['id_perusahaan']][$kriteria[2]->id] = $alt['tugas'];
            $matriks[$alt['id_perusahaan']][$kriteria[3]->id] = $alt['kedisiplinan'];
            $matriks[$alt['id_perusahaan']][$kriteria[4]->id] = $alt['jarak'];
            // $matriks[$alt['id_perusahaan']][$kriteria[5]->id] = $alt['prioritas_keahlian'];
        }
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
                if ($max == $min) {
                    $hasil[$idAlt][$krit->id] = 0;
                } else {
                    if ($krit->type === 'benefit') {
                        $hasil[$idAlt][$krit->id] = ($v - $min) / ($max - $min);
                    } else {
                        $hasil[$idAlt][$krit->id] = ($max - $v) / ($max - $min);
                    }
                }
            }
        }
        return $hasil;
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

        return $bobot;
    }

    private function normalisasiAras($matriks, $kriteria)
    {
        $hasil = [];
        foreach ($kriteria as $krit) {
            $kolom = array_column($matriks, $krit->id);

            foreach ($matriks as $idAlt => $nilai) {
                $v = $nilai[$krit->id];

                if ($krit->type === 'benefit') {
                    $jumlah = array_sum($kolom);
                    $hasil[$idAlt][$krit->id] = $jumlah == 0 ? 0 : $v / $jumlah;
                } else {
                    $kebalikan = array_map(fn($x) => $x == 0 ? 0 : 1 / $x, $kolom);
                    $jumlahKebalikan = array_sum($kebalikan);
                    $hasil[$idAlt][$krit->id] = $v == 0 || $jumlahKebalikan == 0 ? 0 : (1 / $v) / $jumlahKebalikan;
                }
            }
        }
        return $hasil;
    }
    private function hitungAras($normal, $bobot, $alternatif, $kriteria)
    {
        // Hitung nilai ideal (S0)
        $ideal = [];
        foreach ($kriteria as $krit) {
            $kolom = array_column($normal, $krit->id);
            $ideal[$krit->id] = $krit->type === 'benefit' ? max($kolom) : min($kolom);
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
                $Si += $bobot[$krit->id] * $normal[$alt['id_perusahaan']][$krit->id];
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
        return $hasil;
    }
}
