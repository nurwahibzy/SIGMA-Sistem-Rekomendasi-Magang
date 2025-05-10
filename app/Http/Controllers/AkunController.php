<?php

namespace App\Http\Controllers;

use App\Models\AkunModel;
use App\Models\MagangModel;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function tes(){
        // $akun = AkunModel::with('level')->with('dosen')->get();

        $magang = MagangModel::with('penilaian')->get();
        return response()->json( $magang[1]['status'] == 'lulus' && count($magang[1]['penilaian']) == 0);
    }    
}
