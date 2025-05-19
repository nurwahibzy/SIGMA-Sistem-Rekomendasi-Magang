<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function getDosen()
    {
        $dosen = DosenModel::with('akun')
        ->get();

        return view('admin.dosen.index', ['dosen' => $dosen]);
    }
}
