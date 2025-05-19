<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdiModel;
use DB;
use Illuminate\Http\Request;
use Log;

class ProdiController extends Controller
{
    // add peringatan, try catch, transaction
    public function getProdi()
    {
        $prodi = ProdiModel::get();
        return view('admin.prodi.index', ['prodi' => $prodi]);
    }

    public function getAddProdi()
    {
        // return view('tes.lowongan', ['data' => $data]);
    }

    public function getEditProdi($id_prodi)
    {
        $prodi = ProdiModel::where('id_prodi', $id_prodi)->first();
        return response()->json($prodi);
    }

    public function postProdi(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $nama_prodi = $request->input('nama_prodi');
                $nama_jurusan = 'Teknologi Informasi';

                ProdiModel::create([
                    'nama_prodi' => $nama_prodi,
                    'nama_jurusan' => $nama_jurusan
                ]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putProdi(Request $request, $id_prodi)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $nama_prodi = $request->input('nama_prodi');


                ProdiModel::where('id_prodi', $id_prodi)
                    ->update([
                        'nama_prodi' => $nama_prodi
                    ]);

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function deleteProdi(Request $request, $id_prodi)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                ProdiModel::where('id_prodi', $id_prodi)
                    ->delete();

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menghapus lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
