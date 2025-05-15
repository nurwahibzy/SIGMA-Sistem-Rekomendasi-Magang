<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PreferensiLokasiMahasiswaModel;
use Illuminate\Http\Request;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Log;

class PreferensiLokasiMahasiswaController extends Controller
{
    public function putPreferensiLokasi(Request $request, $id_preferensi)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $provinsi = $request->input('provinsi');
                $daerah = $request->input('daerah');
                $alamat = "$daerah, $provinsi, Indonesia";

                $httpClient = new GuzzleAdapter();
                $provider = Nominatim::withOpenStreetMapServer($httpClient, 'my-laravel-app');
                $geocoder = new StatefulGeocoder($provider, 'en');

                $result = $geocoder->geocodeQuery(GeocodeQuery::create($alamat))->first();

                if (!$result) {
                    return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
                }

                PreferensiLokasiMahasiswaModel::where('id_preferensi_lokasi', $id_preferensi)
                    ->update([
                        'provinsi' => $provinsi,
                        'daerah' => $daerah,
                        'latitude' => $result->getCoordinates()->getLatitude(),
                        'longitude' => $result->getCoordinates()->getLongitude(),
                    ]);
                    
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Preferensi Perusahaan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
