<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\MagangModel;
use App\Models\PeriodeMagangModel;
use Auth;
use Illuminate\Http\Request;

class PeriodeMagangController extends Controller
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
        return redirect('/mahasiswa/periode');
    }
    public function getPeriode(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai_filter');
        $tanggal_selesai = $request->input('tanggal_selesai_filter');

        if ($tanggal_mulai != null && $tanggal_selesai != null) {
            $periode = PeriodeMagangModel::with([
                'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
                'lowongan_magang.bidang:id_bidang,nama',
                'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            ])
                ->where('tanggal_mulai', '>=', $tanggal_mulai)
                ->where('tanggal_selesai', '<=', $tanggal_selesai)
                ->get(['id_periode', 'id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);
        } else if ($tanggal_mulai != null) {
            $periode = PeriodeMagangModel::with([
                'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
                'lowongan_magang.bidang:id_bidang,nama',
                'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            ])
                ->where('tanggal_mulai', '>=', $tanggal_mulai)
                ->get(['id_periode', 'id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);
        } else if ($tanggal_selesai != null) {
            $periode = PeriodeMagangModel::with([
                'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
                'lowongan_magang.bidang:id_bidang,nama',
                'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            ])
                ->where('tanggal_selesai', '<=', $tanggal_selesai)
                ->get(['id_periode', 'id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);
        } else {
            $periode = PeriodeMagangModel::with([
                'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
                'lowongan_magang.bidang:id_bidang,nama',
                'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            ])
                ->get(['id_periode', 'id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);
        }


        $idPerusahaan = collect();
        $idJenisPerusahaan = collect();
        $idBidang = collect();

        foreach ($periode as $p) {
            if ($p->lowongan_magang) {
                $perusahaan = $p->lowongan_magang->perusahaan;
                $bidang = $p->lowongan_magang->bidang;

                if ($perusahaan) {
                    $idPerusahaan->push($perusahaan->id_perusahaan);
                    if ($perusahaan->jenis_perusahaan) {
                        $idJenisPerusahaan->push($perusahaan->jenis_perusahaan->id_jenis);
                    }
                }

                if ($bidang) {
                    $idBidang->push($bidang->id_bidang);
                }
            }
        }

        $jumlahPerusahaan = $idPerusahaan->unique()->count();
        $jumlahJenisPerusahaan = $idJenisPerusahaan->unique()->count();
        $jumlahBidang = $idBidang->unique()->count();

        return view('mahasiswa.periode.index', [
            'periode' => $periode,
            'activeMenu' => 'dashboard',
            'jumlahPerusahaan' => $jumlahPerusahaan,
            'jumlahJenisPerusahaan' => $jumlahJenisPerusahaan,
            'jumlahBidang' => $jumlahBidang,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai
        ]);
    }

    public function getDetailPeriode($id_periode)
    {
        $id_mahasiswa = $this->idMahasiswa();
        // $periode = PeriodeMagangModel::with(
        //     'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi',
        //     'lowongan_magang.periode_magang:id_lowongan,nama,tanggal_mulai,tanggal_selesai',
        //     'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
        //     'lowongan_magang.bidang:id_bidang,nama',
        //     'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
        // )
        $periode = PeriodeMagangModel::with(
            'lowongan_magang.perusahaan.jenis_perusahaan',
            'lowongan_magang.bidang'
        )
            ->where('id_periode', $id_periode)
            ->where('tanggal_mulai', '>', now())
            ->first();

        $status = MagangModel::where('id_mahasiswa', $id_mahasiswa)
            ->whereIn('status', ['proses', 'diterima'])
            ->count();

        // return response()->json(data: $periode);
        return view('mahasiswa.periode.detail', ['periode' => $periode, 'status' => $status]);
    }
}
