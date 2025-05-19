<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidangModel;
use App\Models\LowonganMagangModel;
use App\Models\PerusahaanModel;
use DB;
use Illuminate\Http\Request;
use Log;

class LowonganMagangController extends Controller
{
    // add peringatan, try catch, transaction
    public function getLowongan()
    {
        $lowongan = LowonganMagangModel::with('perusahaan')
        ->get();

        return view('admin.lowongan.index', ['lowongan' => $lowongan]);
    }

    public function getAddLowongan()
    {
        $perusahaan = PerusahaanModel::get(['id_perusahaan', 'nama']);
        $bidang = BidangModel::get(['id_bidang', 'nama']);
        $data = [
            'perusahaan' => $perusahaan,
            'bidang' => $bidang
        ];
        return view('tes.lowongan', ['data' => $data]);
    }

    public function getEditLowongan($id_lowongan)
    {
        $perusahaan = PerusahaanModel::get(['id_perusahaan', 'nama']);
        $bidang = BidangModel::get(['id_bidang', 'nama']);
        $lowongan = LowonganMagangModel::where('id_lowongan', $id_lowongan)->first();
        return response()->json($lowongan);
    }

    public function getDetailLowongan($id_lowongan){
        $lowongan = LowonganMagangModel::with(
            'perusahaan:id_perusahaan,nama,telepon,deskripsi,foto_path,provinsi,daerah',
            'bidang:id_bidang,nama'
            )
        ->where('id_lowongan', $id_lowongan)
        ->first();
        return response()->json($lowongan);
    }

    public function postLowongan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {

                $id_perusahaan = $request->input('id_perusahaan');
                $id_bidang = $request->input('id_bidang');
                $nama = $request->input('nama');
                $persyaratan = $request->input('persyaratan');
                $deskripsi = $request->input('deskripsi');


                LowonganMagangModel::insert([
                    'id_perusahaan' => $id_perusahaan,
                    'id_bidang' => $id_bidang,
                    'nama' => $nama,
                    'persyaratan' => $persyaratan,
                    'deskripsi' => $deskripsi,
                ]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putLowongan(Request $request, $id_lowongan)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $id_perusahaan = $request->input('id_perusahaan');
                $id_bidang = $request->input('id_bidang');
                $nama = $request->input('nama');
                $persyaratan = $request->input('persyaratan');
                $deskripsi = $request->input('deskripsi');


                LowonganMagangModel::where('id_lowongan', $id_lowongan)
                    ->update([
                        'id_perusahaan' => $id_perusahaan,
                        'id_bidang' => $id_bidang,
                        'nama' => $nama,
                        'persyaratan' => $persyaratan,
                        'deskripsi' => $deskripsi,
                    ]);

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function deleteLowongan(Request $request, $id_lowongan)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                LowonganMagangModel::where('id_lowongan', $id_lowongan)
                    ->delete();

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menghapus lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
