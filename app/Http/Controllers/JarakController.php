<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Location\Coordinate;
use Location\Distance\Vincenty;

class JarakController extends Controller
{
    public static function hitungJarak($Perusahaan, $Mahasiswa)
    {
        $koordinatPerusahaan = new Coordinate($Perusahaan->latitude, $Perusahaan->longitude); 
        $koordinatMahasiwa = new Coordinate($Mahasiswa->preferensi_lokasi_mahasiswa->latitude, $Mahasiswa->preferensi_lokasi_mahasiswa->longitude); 

        $calculator = new Vincenty();
        $distance = $calculator->getDistance($koordinatPerusahaan, $koordinatMahasiwa);// meter
        $distance = $calculator->getDistance($koordinatPerusahaan, $koordinatMahasiwa);// meter

        return round($distance / 1000, 2);
    }
}
