<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\DosenModel;
use App\Models\MagangModel;
use Auth;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    private function idDosen()
    {
        $id_dosen = AkunModel::with(relations: 'dosen:id_dosen,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->dosen
            ->id_dosen;
        return $id_dosen;
    }

    private function allDataProfil(){
        $akun = AkunModel::with(
            'dosen:id_dosen,id_akun,nama,alamat,telepon,tanggal_lahir,email',
            'dosen.keahlian_dosen:id_keahlian_dosen,id_dosen,id_bidang,keahlian',
            'dosen.keahlian_dosen.bidang:id_bidang,nama',
        )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_user']);
        return $akun;
    }
    public function getProfil(){
        $akun = $this->allDataProfil();
        return response()->json($akun);
    }

    public function getEditProfil(){
        $akun = $this->allDataProfil();
        return response()->json($akun);
    }

    public function putAkun(){

    }

    public function getProfilMahasiswa($id_magang){
        // return response()->json('a');
        $id_dosen = $this->idDosen();
        $akun = MagangModel::with(
            'mahasiswa:id_mahasiswa,id_akun,id_prodi,nama,alamat,telepon,tanggal_lahir,email',
            'mahasiswa.prodi:id_prodi,nama_prodi,nama_jurusan',
            'mahasiswa.pengalaman:id_pengalaman,id_mahasiswa,deskripsi',
            'mahasiswa.dokumen:id_dokumen,id_mahasiswa,nama,file_path',
            'mahasiswa.preferensi_lokasi_mahasiswa:id_preferensi_lokasi,id_mahasiswa,provinsi,daerah',
            'mahasiswa.preferensi_perusahaan_mahasiswa:id_preferensi_perusahaan,id_mahasiswa,id_jenis',
            'mahasiswa.preferensi_perusahaan_mahasiswa.jenis_perusahaan:id_jenis,jenis',
            'mahasiswa.keahlian_mahasiswa:id_keahlian_mahasiswa,id_mahasiswa,id_bidang,keahlian',
            'mahasiswa.keahlian_mahasiswa.bidang:id_bidang,nama',
        )
        ->where('id_dosen', $id_dosen)
        ->where('id_magang', $id_magang)
        ->first();

        return response()->json($akun);
    }
}
