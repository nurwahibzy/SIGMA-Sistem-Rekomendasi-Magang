<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdiModel;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    // add peringatan, try catch, transaction
    public function getProdi()
    {
        $prodi = ProdiModel::get();
        return response()->json($prodi);
    }

    // public function getAddLowongan()
    // {
    //     $perusahaan = PerusahaanModel::get(['id_perusahaan', 'nama']);
    //     $bidang = BidangModel::get(['id_bidang', 'nama']);
    //     $data = [
    //         'perusahaan' => $perusahaan,
    //         'bidang' => $bidang
    //     ];
    //     return view('tes.lowongan', ['data' => $data]);
    // }

    // public function getEditLowongan($id_lowongan)
    // {
    //     $perusahaan = PerusahaanModel::get(['id_perusahaan', 'nama']);
    //     $bidang = BidangModel::get(['id_bidang', 'nama']);
    //     $lowongan = LowonganMagangModel::where('id_lowongan', $id_lowongan)->first();
    //     return response()->json($lowongan);
    // }

    // public function postLowongan(Request $request)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         try {
    //             DB::transaction(function () use ($request) {
    //                 $id_perusahaan = $request->input('id_perusahaan');
    //                 $id_bidang = $request->input('id_bidang');
    //                 $nama = $request->input('nama');
    //                 $persyaratan = $request->input('persyaratan');
    //                 $deskripsi = $request->input('deskripsi');


    //                 LowonganMagangModel::insert([
    //                     'id_perusahaan' => $id_perusahaan,
    //                     'id_bidang' => $id_bidang,
    //                     'nama' => $nama,
    //                     'persyaratan' => $persyaratan,
    //                     'deskripsi' => $deskripsi,
    //                 ]);
    //             });
    //             return response()->json(['success' => true]);
    //         } catch (\Exception $e) {
    //             Log::error("Gagal menambahkan lowongan: " . $e->getMessage());
    //             return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
    //         }
    //     }
    // }

    // public function putLowongan(Request $request, $id_lowongan)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         try {
    //             DB::transaction(
    //                 function () use ($request, $id_lowongan) {
    //                     $id_perusahaan = $request->input('id_perusahaan');
    //                     $id_bidang = $request->input('id_bidang');
    //                     $nama = $request->input('nama');
    //                     $persyaratan = $request->input('persyaratan');
    //                     $deskripsi = $request->input('deskripsi');


    //                     LowonganMagangModel::where('id_lowongan', $id_lowongan)
    //                         ->update([
    //                             'id_perusahaan' => $id_perusahaan,
    //                             'id_bidang' => $id_bidang,
    //                             'nama' => $nama,
    //                             'persyaratan' => $persyaratan,
    //                             'deskripsi' => $deskripsi,
    //                         ]);
    //                 }
    //             );
    //             return response()->json(['success' => true]);
    //         } catch (\Throwable $e) {
    //             Log::error("Gagal update lowongan: " . $e->getMessage());
    //             return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
    //         }
    //     }
    // }

    // public function deleteLowongan(Request $request, $id_lowongan)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         try {
    //             DB::transaction(
    //                 function () use ($request, $id_lowongan) {

    //                     LowonganMagangModel::where('id_lowongan', $id_lowongan)
    //                         ->delete();
    //                 }
    //             );
    //             return response()->json(['success' => true]);
    //         } catch (\Throwable $e) {
    //             Log::error("Gagal menghapus lowongan: " . $e->getMessage());
    //             return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
    //         }
    //     }
    // }
}
