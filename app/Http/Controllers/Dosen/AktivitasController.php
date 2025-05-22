<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMagangModel;
use App\Models\AkunModel;
use App\Models\MagangModel;
use Auth;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    // add try catch, transaction
    private function idDosen()
    {
        $id_dosen = AkunModel::with(relations: 'dosen:id_dosen,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->dosen
            ->id_dosen;
        return $id_dosen;
    }
    public function getMagangDiterima(){
        $id_dosen = $this->idDosen();
        $magang = MagangModel::where('id_dosen', $id_dosen)
        ->where('status', 'diterima')
        ->get();

        return view('dosen.mahasiswa.index', compact('magang'));
    }

    // public function getAktivitas($id_magang){
        
    // }
    public function getAktivitas($id_magang)
    {
        $id_dosen = $this->idDosen();
        $aktivitas = AktivitasMagangModel::with('magang:id_magang,id_dosen')
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_dosen) {
                $query->where('id_dosen', $id_dosen);
            })
            ->get();

        return response()->json($aktivitas);
    }

    public function getDetailAktivitas($id_magang, $id_aktivitas){
        
    }
}
