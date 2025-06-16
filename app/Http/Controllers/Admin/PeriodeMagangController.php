<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidangModel;
use App\Models\LowonganMagangModel;
use App\Models\PeriodeMagangModel;
use App\Models\PerusahaanModel;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;
use Validator;

class PeriodeMagangController extends Controller
{
    public function getPeriode(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai_filter');
        $tanggal_selesai = $request->input('tanggal_selesai_filter');
        $waktu = $request->input('waktu');


        $selesai = PeriodeMagangModel::where('tanggal_selesai', '<=', now())->count();
        $berlangsung = PeriodeMagangModel::where('tanggal_mulai', '<', now())->where('tanggal_selesai', '>', now())->count();
        $segera = PeriodeMagangModel::where('tanggal_mulai', '>=', now())->count();


        if ($tanggal_mulai != null && $tanggal_selesai != null) {
            if ($waktu != null) {
                $periode = PeriodeMagangModel::with(
                    'lowongan_magang',
                    'lowongan_magang.perusahaan'
                )
                    ->where('tanggal_mulai', '<=', $tanggal_mulai)
                    ->where('tanggal_selesai', '>=', $tanggal_selesai)
                    ->orderBy('created_at')
                    ->get();
    
                return view('admin.periode.index', ['periode' => $periode, 'selesai' => $selesai, 'berlangsung' => $berlangsung, 'segera' => $segera]);
            }

            $periode = PeriodeMagangModel::with(
                'lowongan_magang',
                'lowongan_magang.perusahaan'
            )
                ->where('tanggal_mulai', '>=', $tanggal_mulai)
                ->where('tanggal_selesai', '<=', $tanggal_selesai)
                ->orderBy('created_at')
                ->get();

            return view('admin.periode.index', ['periode' => $periode, 'selesai' => $selesai, 'berlangsung' => $berlangsung, 'segera' => $segera, 'tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai]);
        } else if ($tanggal_mulai != null) {
            $periode = PeriodeMagangModel::with(
                'lowongan_magang',
                'lowongan_magang.perusahaan'
            )
                ->where('tanggal_mulai', '>=', $tanggal_mulai)
                ->orderBy('created_at')
                ->get();
            
                if ($waktu != null) {
                    return view('admin.periode.index', ['periode' => $periode, 'selesai' => $selesai, 'berlangsung' => $berlangsung, 'segera' => $segera]);
                }

            return view('admin.periode.index', ['periode' => $periode, 'selesai' => $selesai, 'berlangsung' => $berlangsung, 'segera' => $segera, 'tanggal_mulai' => $tanggal_mulai]);

        } else if ($tanggal_selesai != null) {
            $periode = PeriodeMagangModel::with(
                'lowongan_magang',
                'lowongan_magang.perusahaan'
            )
                ->where('tanggal_selesai', '<=', $tanggal_selesai)
                ->orderBy('created_at')
                ->get();

            if ($waktu != null) {
                return view('admin.periode.index', ['periode' => $periode, 'selesai' => $selesai, 'berlangsung' => $berlangsung, 'segera' => $segera]);
            }

            return view('admin.periode.index', ['periode' => $periode, 'selesai' => $selesai, 'berlangsung' => $berlangsung, 'segera' => $segera, 'tanggal_selesai' => $tanggal_selesai]);

        } else {
            $periode = PeriodeMagangModel::with(
                'lowongan_magang',
                'lowongan_magang.perusahaan'
            )
                ->orderBy('created_at')
                ->get();

            return view('admin.periode.index', ['periode' => $periode, 'selesai' => $selesai, 'berlangsung' => $berlangsung, 'segera' => $segera]);
        }
    }

    public function getPeriodeLowongan($id_perusahaan, $id_bidang)
    {
        $lowongan = LowonganMagangModel::where('id_perusahaan', $id_perusahaan)
            ->where('id_bidang', $id_bidang)
            ->get();

        return response()->json($lowongan);
    }

    public function getAddPeriode()
    {
        $lowongan = LowonganMagangModel::with(
            'perusahaan',
            'bidang'
        )
            ->get();

        $perusahaan = PerusahaanModel::get();
        $bidang = BidangModel::get();
        return view('admin.periode.tambah', ['lowongan' => $lowongan, 'perusahaan' => $perusahaan, 'bidang' => $bidang, 'now' => Carbon::now()->toDateString(), 'tomorrow' => Carbon::tomorrow()->toDateString()]);
    }

    public function getEditPeriode($id_periode)
    {
        $data = DB::transaction(function () use ($id_periode) {
            $perusahaan = PerusahaanModel::get();
            $bidang = BidangModel::get();

            $periode = PeriodeMagangModel::with('lowongan_magang')
                ->where('id_periode', $id_periode)->firstOrFail();

            return [
                'periode' => $periode,
                'perusahaan' => $perusahaan,
                'bidang' => $bidang,
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

                $tanggal_selesai_terakhir = PeriodeMagangModel::where('id_lowongan', $id_lowongan)
                    ->orderByDesc('tanggal_selesai')
                    ->first();

                if ($tanggal_selesai_terakhir) {
                    if (strtotime($tanggal_mulai) < strtotime($tanggal_selesai_terakhir->tanggal_selesai)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Lowongan sebelumnya masih berlangsung hingga ' . $tanggal_selesai_terakhir->tanggal_selesai
                        ]);
                    }
                }

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

                $tanggal_selesai_terakhir = PeriodeMagangModel::where('id_lowongan', $id_lowongan)
                    ->whereNot('id_periode', $id_periode)
                    ->orderByDesc('tanggal_selesai')
                    ->first();
                    
                if ($tanggal_selesai_terakhir) {
                    if (strtotime($tanggal_mulai) < strtotime($tanggal_selesai_terakhir->tanggal_selesai)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Lowongan sebelumnya masih berlangsung hingga ' . $tanggal_selesai_terakhir->tanggal_selesai
                        ]);
                    }
                }

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

                $periode = PeriodeMagangModel::with('magang.aktivitas_magang')
                    ->where('id_periode', $id_periode)
                    ->first();

                foreach ($periode->magang as $magang) {
                    foreach ($magang->aktivitas_magang as $aktivitas) {
                        $foto_path = $aktivitas->foto_path;

                        if (Storage::disk('public')->exists("aktivitas/$foto_path")) {
                            Storage::disk('public')->delete("aktivitas/$foto_path");
                        }
                    }
                }

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
