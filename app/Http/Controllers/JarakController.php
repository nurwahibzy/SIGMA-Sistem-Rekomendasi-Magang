<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Location\Coordinate;
use Location\Distance\Vincenty;

class JarakController extends Controller
{
    public function hitungJarak()
    {
        $coordinate1 = new Coordinate(-8.1017, 112.1686); // Blitar
        $coordinate2 = new Coordinate(-7.9819, 112.6265); // Malang

        $calculator = new Vincenty();
        $distance = $calculator->getDistance($coordinate1, $coordinate2); // meter

        return response()->json([
            'jarak_km' => round($distance / 1000, 2)
        ]);
    }
}
