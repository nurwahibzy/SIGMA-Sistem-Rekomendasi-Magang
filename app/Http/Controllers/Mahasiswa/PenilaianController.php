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
                    $id_mahasiswa = $this->idMahasiswa();
                    $fasilitas = $request->input('fasilitas');
                    $tugas = $request->input('tugas');
                    $kedisiplinan = $request->input('kedisiplinan');

                    PenilaianModel::insert([
                        'id_magang' => $id_magang,
                        'fasilitas' => $fasilitas,
                        'tugas' => $tugas,
                        'kedisiplinan' => $kedisiplinan
                    ]);
                });
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Penilaian: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
