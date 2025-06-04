<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\MagangModel;
use App\Models\PenilaianModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;

class PenilaianController extends Controller
{

    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }
    public function index()
    {
        try {
            return DB::transaction(function () {
                $id_mahasiswa = $this->idMahasiswa();

                $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
                    ->where('status', 'lulus')
                    ->with([
                        'periode_magang:id_periode,id_lowongan,nama,tanggal_mulai,tanggal_selesai',
                        'periode_magang.lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                        'periode_magang.lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
                        'periode_magang.lowongan_magang.bidang:id_bidang,nama',
                        'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
                    ])
                    ->get();

                return view('mahasiswa.penilaian.index', [
                    'magang' => $magang
                ]);

                // return response()->json($magang);
            });
        } catch (\Throwable $e) {
            Log::error("Gagal memuat halaman penilaian: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }


    public function getPenilaian($id_magang)
    {
        try {
            return DB::transaction(function () use ($id_magang) {
                $id_mahasiswa = $this->idMahasiswa();

                $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
                    ->where('id_magang', $id_magang)
                    ->where('status', 'lulus')
                    ->first();
                
                $penilaian = PenilaianModel::where('id_magang', $id_magang)->first();

                if ($magang) {
                    if ($magang->id_mahasiswa != $id_mahasiswa) {
                        return view('mahasiswa.penilaian.rating');
                    }
                } else {
                    return view('mahasiswa.penilaian.rating');
                }

                if ($penilaian) {
                    return view('mahasiswa.penilaian.rating');
                }

                return view('mahasiswa.penilaian.rating', [
                    'id_magang' => $id_magang
                ]);

            });
        } catch (\Throwable $e) {
            Log::error("Gagal memberikan penilaian: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }


    public function postPenilaian(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $request->validate([
                    'fasilitas' => 'required|numeric|min:1|max:5',
                    'tugas' => 'required|numeric|min:1|max:5',
                    'kedisiplinan' => 'required|numeric|min:1|max:5',
                ]);
                PenilaianModel::create([
                    'id_magang' => $id_magang,
                    'fasilitas' => $request->fasilitas,
                    'tugas' => $request->tugas,
                    'kedisiplinan' => $request->kedisiplinan,
                ]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal memberikan penilaian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

}
