<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use Illuminate\Http\Request;
use Log;

class PeriodeMagangController extends Controller
{
    public function getPeriode()
    {
        $lowongan = PeriodeMagangModel::get();
        return response()->json($lowongan);
    }

    public function getAddPeriode()
    {
        $lowongan = LowonganMagangModel::with(
            'perusahaan:id_perusahaan,nama',
            'bidang:id_bidang,nama'
        )
            ->get(['id_perusahaan', 'id_bidang', 'nama']);
        return response()->json($lowongan);
    }

    public function getEditPeriode($id_periode)
    {
        $lowongan = LowonganMagangModel::with(
            'perusahaan:id_perusahaan,nama',
            'bidang:id_bidang,nama'
        )
            ->get(['id_perusahaan', 'id_bidang', 'nama']);
        $periode = PeriodeMagangModel::where('id_periode', $id_periode)->first();
        return response()->json($periode);
    }

    // public function getDetailPeriode($id_periode){
    //     $lowongan = LowonganMagangModel::with(
    //         'perusahaan:id_perusahaan,nama,telepon,deskripsi,foto_path,provinsi,daerah',
    //         'bidang:id_bidang,nama'
    //         )
    //     ->where('id_lowongan', $id_lowongan)
    //     ->first();
    //     return response()->json($lowongan);
    // }

    public function postPeriode(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {

                $id_lowongan = $request->input('id_lowongan');
                $nama = $request->input('nama');
                $tanggal_mulai = $request->input('tanggal_mulai');
                $tanggal_selesai = $request->input('tanggal_selesai');

                // $date = Carbon::parse(now())->toDateString();

                PeriodeMagangModel::insert([
                    'id_lowongan' => $id_lowongan,
                    'nama' => $nama,
                    'tanggal_mulai' => $tanggal_mulai,
                    'tanggal_selesai' => $tanggal_selesai,
                ]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan periode: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putLowongan(Request $request, $id_periode)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $id_lowongan = $request->input('id_lowongan');
                $nama = $request->input('nama');
                $tanggal_mulai = $request->input('tanggal_mulai');
                $tanggal_selesai = $request->input('tanggal_selesai');

                PeriodeMagangModel::where('id_periode', $id_periode)
                    ->update([
                        'id_lowongan' => $id_lowongan,
                        'nama' => $nama,
                        'tanggal_mulai' => $tanggal_mulai,
                        'tanggal_selesai' => $tanggal_selesai,
                    ]);

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update periode: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function deletePeriode(Request $request, $id_periode)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                PeriodeMagangModel::where('id_periode', $id_periode)
                    ->delete();

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menghapus periode: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
