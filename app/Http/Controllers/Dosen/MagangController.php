<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\MagangModel;
use Auth;
use Illuminate\Http\Request;

class MagangController extends Controller
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
    public function getDashboard(){

        $id_dosen = $this->idDosen();
        $peserta = MagangModel::where('id_dosen', $id_dosen)->get();
        return response()->json($peserta);
        // return view('welcome');
    }

    public function getRiwayat(){
        $id_dosen = $this->idDosen();
        $magang = MagangModel::where('id_dosen', $id_dosen)
        ->where('status', 'lulus')
        ->get();
        return response()->json($magang);
    }


}
