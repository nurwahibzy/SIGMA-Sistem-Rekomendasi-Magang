<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\AkunModel;
use App\Models\PreferensiPerusahaanMahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PreferensiPerusahaanMahasiswaController extends Controller
{
    private function idMahasiswa()
    {
        return AkunModel::with('mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first()
            ->mahasiswa
            ->id_mahasiswa;
    }

    public function postPreferensiPerusahaan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request) {
                    $id_mahasiswa = $this->idMahasiswa();
                    $inputIds = array_map('intval', $request->input('id_jenis', []));

                    $existingIds = PreferensiPerusahaanMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
                        ->pluck('id_jenis')
                        ->toArray();

                    $toDelete = array_diff($existingIds, $inputIds);
                    if (!empty($toDelete)) {
                        PreferensiPerusahaanMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
                            ->whereIn('id_jenis', $toDelete)
                            ->delete();
                    }

                    $toInsert = array_diff($inputIds, $existingIds);
                    $insertData = array_map(function ($id_jenis) use ($id_mahasiswa) {
                        return [
                            'id_mahasiswa' => $id_mahasiswa,
                            'id_jenis' => $id_jenis
                        ];
                    }, $toInsert);

                    if (!empty($insertData)) {
                        PreferensiPerusahaanMahasiswaModel::insert($insertData);
                    }
                });

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Preferensi Perusahaan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
