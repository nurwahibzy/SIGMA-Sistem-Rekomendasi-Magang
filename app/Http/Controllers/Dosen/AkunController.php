<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\DosenModel;
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
}
