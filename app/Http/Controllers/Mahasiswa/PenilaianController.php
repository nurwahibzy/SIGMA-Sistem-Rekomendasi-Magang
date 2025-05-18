<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MagangModel;
use App\Models\PenilaianModel;
use DB;
use Illuminate\Http\Request;
use Log;

class PenilaianController extends Controller
{

    private function idMahasiswa()
    {
        return auth()->user()->id_mahasiswa;
    }

  public function index()
{
    $id_mahasiswa = $this->idMahasiswa();

    $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
        ->where('status', 'diterima')
        ->with(
            'periode_magang:id_periode,id_lowongan,nama,tanggal_mulai,tanggal_selesai',
            'periode_magang.lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,foto_path',
            'periode_magang.lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
            'periode_magang.lowongan_magang.bidang:id_bidang,nama',
            'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        )
        ->get();

   return view('mahasiswa.penilaian.index', [
            'magang' => collect([
                (object)[
                    'id_magang' => 1,
                    'periode_magang' => (object)[
                        'tanggal_mulai' => '2025-08-01',
                        'tanggal_selesai' => '2025-10-31',
                        'lowongan_magang' => (object)[
                            'nama' => 'Intern UI/UX',
                            'perusahaan' => (object)['nama' => 'Tech Corp'],
                            'bidang' => (object)['nama' => 'Design'],
                        ]
                    ]
                ]
            ])
        ]);
}



    public function getPenilaian($id_magang)
    {
        $id_mahasiswa = $this->idMahasiswa();
        $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('id_magang', $id_magang)
            ->first(['id_magang']);
        return response()->json($magang->id_magang);
    }

    public function postPenilaian(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_magang) {
                    $request->validate([
                        'fasilitas' => 'required|numeric|min:1|max:5',
                        'tugas' => 'required|numeric|min:1|max:5',
                        'kedisiplinan' => 'required|numeric|min:1|max:5',
                    ]);

                    // Cek apakah penilaian sudah ada
                    $existing = PenilaianModel::where('id_magang', $id_magang)->first();
                    if ($existing) {
                        throw new \Exception("Penilaian sudah ada.");
                    }

                    PenilaianModel::create([
                        'id_magang' => $id_magang,
                        'fasilitas' => $request->input('fasilitas'),
                        'tugas' => $request->input('tugas'),
                        'kedisiplinan' => $request->input('kedisiplinan')
                    ]);
                });

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Penilaian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
    }
}
