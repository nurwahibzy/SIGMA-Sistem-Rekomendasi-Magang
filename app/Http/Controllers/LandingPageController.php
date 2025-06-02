<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanModel;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
{
    $perusahaan = PerusahaanModel::withCount([
        'lowongan_magang as jumlah_lowongan'
    ])->get();
    // return response()->json($perusahaan);
    return view('landing_page.index', ['perusahaan' => $perusahaan]);
}

}
