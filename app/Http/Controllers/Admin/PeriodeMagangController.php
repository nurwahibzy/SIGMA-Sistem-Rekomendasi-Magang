<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Validator;

class PeriodeMagangController extends Controller
{
    public function getPeriode()
    {
        $periode = PeriodeMagangModel::with(
            'lowongan_magang',
            'lowongan_magang.perusahaan'
        )
            ->get();
        return view('admin.periode.index', ['periode' => $periode]);
    }

    public function getAddPeriode()
    {
        $lowongan = LowonganMagangModel::with(
            'perusahaan',
            'bidang'
        )
            ->get();
        return view('admin.periode.tambah', ['lowongan' => $lowongan, 'now' => Carbon::now()->toDateString(), 'tomorrow' => Carbon::tomorrow()->toDateString()]);
    }

    public function getEditPeriode($id_periode)
    {
        $data = DB::transaction(function () use ($id_periode) {
            $lowongan = LowonganMagangModel::with('perusahaan', 'bidang')->get();

            $periode = PeriodeMagangModel::where('id_periode', $id_periode)->firstOrFail(); // agar error kalau tidak ada

            return [
                'periode' => $periode,
                'lowongan' => $lowongan,
                'now' => Carbon::now()->toDateString(),
                'tomorrow' => Carbon::tomorrow()->toDateString(),
            ];
        });

        return view('admin.periode.edit', $data);
    }

    public function getDetailPeriode($id_periode)
    {

        $periode = PeriodeMagangModel::with('lowongan_magang', 'lowongan_magang.perusahaan', 'lowongan_magang.bidang')
            ->where('id_periode', $id_periode)->first();
        return view('admin.periode.detail', ['periode' => $periode]);
    }

    public function postPeriode(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $validator = Validator::make($request->all(), [
                    'id_lowongan' => 'required|exists:lowongan_magang,id_lowongan',
                    'nama' => 'required|string|max:100',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date',
                ]);

                if ($validator->fails()) {
                    return response()->json(['success' => false]);
                }

                $id_lowongan = $request->input('id_lowongan');
                $nama = $request->input('nama');
                $tanggal_mulai = $request->input('tanggal_mulai');
                $tanggal_selesai = $request->input('tanggal_selesai');

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

    public function putPeriode(Request $request, $id_periode)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $validator = Validator::make($request->all(), [
                    'id_lowongan' => 'required|exists:lowongan_magang,id_lowongan',
                    'nama' => 'required|string|max:100',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date',
                ]);

                if ($validator->fails()) {
                    return response()->json(['success' => false]);
                }
                
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
