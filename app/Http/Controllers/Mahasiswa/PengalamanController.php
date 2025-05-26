<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\AkunModel;
use App\Models\PengalamanModel;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;

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
        return view('mahasiswa.pengalaman.tambah');
    }

    public function getEditPengalaman($id_pengalaman)
    {
        try {
            $id_mahasiswa = $this->idMahasiswa();
            $pengalaman = PengalamanModel::where('id_mahasiswa', $id_mahasiswa)
                ->where('id_pengalaman', $id_pengalaman)
                ->first(['id_pengalaman', 'deskripsi']);
            return view('mahasiswa.pengalaman.edit', ['pengalaman' => $pengalaman]);
        } catch (\Exception $e) {
            Log::error("Gagal mendapatkan data Pengalaman: " . $e->getMessage());
            abort(500, "Terjadi kesalahan.");
        }
    }

    public function postpengalaman(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(function () use ($request, ) {
                    $validator = Validator::make($request->all(), [
                        'deskripsi' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        return false;
                    }

                    $id_mahasiswa = $this->idMahasiswa();
                    PengalamanModel::insert([
                        'id_mahasiswa' => $id_mahasiswa,
                        'deskripsi' => $request->input('deskripsi')
                    ]);

                    return true;
                });
                return response()->json(['success' => $results]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan Pengalaman: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putEditpengalaman(Request $request, $id_pengalaman)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(function () use ($request, $id_pengalaman) {

                    $validator = Validator::make($request->all(), [
                        'deskripsi' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        return false;
                    }

                    $deskripsi = $request->input('deskripsi');
                    $id_mahasiswa = $this->idMahasiswa();
                    PengalamanModel::where('id_mahasiswa', $id_mahasiswa)
                        ->where('id_pengalaman', $id_pengalaman)
                        ->update([
                            'deskripsi' => $deskripsi
                        ]);

                    return true;
                });
                return response()->json(['success' => $results]);
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
                DB::transaction(function () use ($request, $id_pengalaman) {
                    $id_mahasiswa = $this->idMahasiswa();
                    PengalamanModel::where('id_mahasiswa', $id_mahasiswa)
                        ->where('id_pengalaman', $id_pengalaman)
                        ->delete();
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal hapus Pengalaman: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

}
