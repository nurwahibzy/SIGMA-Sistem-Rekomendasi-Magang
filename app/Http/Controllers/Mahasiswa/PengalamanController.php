<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\AkunModel;
use App\Models\PengalamanModel;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class PengalamanController extends Controller
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
    public function getAddPengalaman()
    {
        return view('tes.pengalaman');
    }

    public function getPengalaman($id_pengalaman)
    {
        try {
            $pengalaman = PengalamanModel::where('id_pengalaman', $id_pengalaman)->first(['id_pengalaman', 'deskripsi']);
            return view('tes.editPengalaman', ['pengalaman' => $pengalaman]);
        } catch (\Exception $e) {
            Log::error("Gagal mendapatkan data Pengalaman: " . $e->getMessage());
            abort(500, "Terjadi kesalahan.");
        }
    }

    public function postpengalaman(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, ) {
                    $id_mahasiswa = $this->idMahasiswa();
                    PengalamanModel::insert([
                        'id_mahasiswa' => $id_mahasiswa,
                        'deskripsi' => $request->input('deskripsi')
                    ]);
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan Pengalaman: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putpengalaman(Request $request, $id_pengalaman)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $deskripsi = $request->input('deskripsi');
                PengalamanModel::where('id_pengalaman', $id_pengalaman)
                    ->update([
                        'deskripsi' => $deskripsi
                    ]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal update Pengalaman: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function deletepengalaman(Request $request, $id_pengalaman)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                PengalamanModel::where('id_pengalaman', $id_pengalaman)->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal hapus Pengalaman: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

}
