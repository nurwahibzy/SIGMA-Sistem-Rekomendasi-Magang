<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter; // gunakan Guzzle7 adapter

class GeolokasiController extends Controller
{
    public function getKoordinat(Request $request)
    {
        $alamat = $request->input('alamat', 'Kota Blitar, Jawa Timur, Indonesia');

        // Gunakan Guzzle7 adapter
        $httpClient = new GuzzleAdapter();
        $provider = Nominatim::withOpenStreetMapServer($httpClient, 'my-laravel-app');
        $geocoder = new StatefulGeocoder($provider, 'en');

        $result = $geocoder->geocodeQuery(GeocodeQuery::create($alamat))->first();

        if (!$result) {
            return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
        }

        return response()->json([
            'alamat'   => $alamat,
            'latitude' => $result->getCoordinates()->getLatitude(),
            'longitude'=> $result->getCoordinates()->getLongitude(),
        ]);
    }
}
