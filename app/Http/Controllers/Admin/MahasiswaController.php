<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function getMahasiswa()
    {
        $mahasiswa = MahasiswaModel::with('akun')
        ->get();

        return view('admin.mahasiswa.index', ['mahasiswa' => $mahasiswa]);
    }

    
}
