<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidangModel;
use App\Models\LowonganMagangModel;
use App\Models\PerusahaanModel;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;
use Validator;

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
        $data = DB::transaction(function () {
            $perusahaan = PerusahaanModel::get(['id_perusahaan', 'nama']);
            $bidang = BidangModel::get(['id_bidang', 'nama']);

            return [
                'perusahaan' => $perusahaan,
                'bidang' => $bidang,
            ];
        });

        return view('admin.lowongan.tambah', $data);
    }

    public function getEditLowongan($id_lowongan)
    {
        $data = DB::transaction(function () use ($id_lowongan) {
            $perusahaan = PerusahaanModel::get(['id_perusahaan', 'nama']);
            $bidang = BidangModel::get(['id_bidang', 'nama']);
            $lowongan = LowonganMagangModel::where('id_lowongan', $id_lowongan)->firstOrFail();

            return [
                'perusahaan' => $perusahaan,
                'bidang' => $bidang,
                'lowongan' => $lowongan,
            ];
        });

        return view('admin.lowongan.edit', $data);
    }

    public function getDetailLowongan($id_lowongan)
    {
        $lowongan = LowonganMagangModel::with(
            'perusahaan:id_perusahaan,nama,telepon,deskripsi,foto_path,provinsi,daerah',
            'bidang:id_bidang,nama'
        )
            ->where('id_lowongan', $id_lowongan)
            ->first();

        return view('admin.lowongan.detail', ['lowongan' => $lowongan]);
        // return response()->json($lowongan);
        // return response()->json($lowongan);
    }

    public function postLowongan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $validator = Validator::make($request->all(), [
                    'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
                    'id_bidang' => 'required|exists:bidang,id_bidang',
                    'nama' => 'required|string|max:100',
                    'persyaratan' => 'required|string',
                    'deskripsi' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response()->json(['success' => false]);
                }

                $id_perusahaan = $request->input('id_perusahaan');
                $id_bidang = $request->input('id_bidang');
                $nama = $request->input('nama');
                $persyaratan = $request->input('persyaratan');
                $deskripsi = $request->input('deskripsi');


                LowonganMagangModel::create([
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
                $validator = Validator::make($request->all(), [
                    'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
                    'id_bidang' => 'required|exists:bidang,id_bidang',
                    'nama' => 'required|string|max:100',
                    'persyaratan' => 'required|string',
                    'deskripsi' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response()->json(['success' => false]);
                }

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

                $lowongan = LowonganMagangModel::with('periode_magang.magang.aktivitas_magang')
                    ->where('id_lowongan', $id_lowongan)
                    ->first();

                foreach ($lowongan->periode_magang as $periode) {
                    foreach ($periode->magang as $magang) {
                        foreach ($magang->aktivitas_magang as $aktivitas) {
                            $foto_path = $aktivitas->foto_path;

                            if (Storage::disk('public')->exists("aktivitas/$foto_path")) {
                                Storage::disk('public')->delete("aktivitas/$foto_path");
                            }
                        }
                    }
                }

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
