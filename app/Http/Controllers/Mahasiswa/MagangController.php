<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\MagangModel;
use App\Models\PenilaianModel;
use App\Models\PeriodeMagangModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;

class MagangController extends Controller
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
    public function getDashboard()
    {
        // $magang = PeriodeMagangModel::with(
        //     'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi,foto_path',
        //     'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,telepon,deskripsi,foto_path,provinsi,daerah',
        //     'lowongan_magang.bidang:id_bidang,nama',
        //     'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        //     )
        //     ->get();
        $magang = PeriodeMagangModel::with(
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,foto_path',
            'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
            'lowongan_magang.bidang:id_bidang,nama',
            'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        )
            ->get(['id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);
        return response()->json($magang);
    }

    public function getMagang($id_periode)
    {
        $magang = PeriodeMagangModel::with(
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi,foto_path',
            'lowongan_magang.periode_magang:id_lowongan,nama,tanggal_mulai,tanggal_selesai',
            'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
            'lowongan_magang.bidang:id_bidang,nama',
            'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        )
            ->where('id_periode', $id_periode)
            ->get();
        return response()->json($magang);
    }

    public function getPerusahaan($id_magang)
    {
        $magang = PeriodeMagangModel::with(
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi,foto_path',
            'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,telepon,deskripsi,foto_path,provinsi,daerah',
            'lowongan_magang.bidang:id_bidang,nama',
            'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        )
            ->get();
        return response()->json($magang);
    }

    public function getRiwayat()
    {
        $id_mahasiswa = $this->idMahasiswa();
        $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
            ->with(
                'periode_magang:id_periode,id_lowongan,nama,tanggal_mulai,tanggal_selesai',
                'periode_magang.lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,foto_path',
                'periode_magang.lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
                'periode_magang.lowongan_magang.bidang:id_bidang,nama',
                'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            )
            ->get();
        return response()->json($magang);
    }

    public function getPenilaian($id_magang)
    {
        $id_mahasiswa = $this->idMahasiswa();
        $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('id_magang', $id_magang)
            ->first(['id_magang']);
        return response()->json($magang->id_magang);
    }

    public function postPenilaian(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request, $id_magang) {
                    $id_mahasiswa = $this->idMahasiswa();
                    $fasilitas = $request->input('fasilitas');
                    $tugas = $request->input('tugas');
                    $kedisiplinan = $request->input('kedisiplinan');

                    PenilaianModel::insert([
                        'id_magang' => $id_magang,
                        'fasilitas' => $fasilitas,
                        'tugas' => $tugas,
                        'kedisiplinan' => $kedisiplinan
                    ]);
                });
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

}
