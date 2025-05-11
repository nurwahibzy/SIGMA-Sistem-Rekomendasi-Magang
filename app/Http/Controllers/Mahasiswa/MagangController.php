<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Controller;
use App\Models\PeriodeMagangModel;
use Illuminate\Http\Request;

class MagangController extends Controller
{
    public function getDashboard(){
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
        // return view('welcome');
    }

    public function getMagang($id_periode){
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

    public function getPerusahaan($id_magang){
        $magang = PeriodeMagangModel::with(
            'lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama,persyaratan,deskripsi,foto_path',
            'lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama,telepon,deskripsi,foto_path,provinsi,daerah',
            'lowongan_magang.bidang:id_bidang,nama',
            'lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            )
            ->get();
            return response()->json($magang);
    }
}
