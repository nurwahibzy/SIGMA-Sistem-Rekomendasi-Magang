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
    // try catch and transaction
    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }
//   public function getDashboard()
// {
//     $magang = PeriodeMagangModel::with(
//         'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
//         'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
//         'lowongan_magang.bidang:id_bidang,nama',
//         'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
//     )
//         ->where('tanggal_mulai', '>', now())
//         ->get(['id_lowongan', 'tanggal_mulai', 'tanggal_selesai']);

//         return view('mahasiswa.index', [
//             'magang' => $magang,
//             'activeMenu' => 'dashboard'
//         ]);
        
        
// }


//     public function getMagang($id_periode)
//     {
//         $id_mahasiswa = $this->idMahasiswa();
//         $magang = PeriodeMagangModel::with(
//             'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi',
//             'lowongan_magang.periode_magang:id_lowongan,nama,tanggal_mulai,tanggal_selesai',
//             'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,provinsi,daerah',
//             'lowongan_magang.bidang:id_bidang,nama',
//             'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
//         )
//             ->where('id_periode', $id_periode)
//             ->where('tanggal_mulai', '>', now())
//             ->get();
//         $status = MagangModel::where('id_mahasiswa', $id_mahasiswa)
//             ->whereIn('status', ['diterima', 'lulus'])
//             ->first();

//         // $status = $status == null ? true : false;
//         // return response()->json($status == null);
//         return response()->json($magang);
//     }

    public function postMagang($id_periode)
    {
        $id_mahasiswa = $this->idMahasiswa();
        $tanggal_pengajuan = now();

        MagangModel::create([
            'id_mahasiswa' => $id_mahasiswa,
            'id_periode' => $id_periode,
            'tanggal_pengajuan' => $tanggal_pengajuan
        ]);
    }

    public function getPerusahaan($id_magang)
    {
        $magang = PeriodeMagangModel::with(
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi',
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
                'periode_magang.lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                'periode_magang.lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
                'periode_magang.lowongan_magang.bidang:id_bidang,nama',
                'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            )
            ->get();
        return response()->json($magang);
    }

}
