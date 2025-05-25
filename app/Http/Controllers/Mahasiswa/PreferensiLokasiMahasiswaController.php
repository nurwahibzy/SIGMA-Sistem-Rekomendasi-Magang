<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PreferensiLokasiMahasiswaModel;
use DB;
use Illuminate\Http\Request;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Log;

class PreferensiLokasiMahasiswaController extends Controller
{

    public function latitudeLongitude($provinsi, $daerah)
    {
        $alamat = "$daerah, $provinsi, Indonesia";

        $httpClient = new GuzzleAdapter();
        $provider = Nominatim::withOpenStreetMapServer($httpClient, 'my-laravel-app');
        $geocoder = new StatefulGeocoder($provider, 'en');

        $result = $geocoder->geocodeQuery(GeocodeQuery::create($alamat))->first();
        return $result;

    }

    public function putPreferensiLokasi(Request $request, $id_preferensi)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request, $id_preferensi) {
                        // $validator = Validator::make($request->all(), [
                        //     'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                        //     'id_jenis' => 'required|exists:jenis_perusahaan,id_jenis',
                        //     'nama' => 'required|string|max:100',
                        //     'telepon' => 'required|digits_between:1,30',
                        //     'deskripsi' => 'required|string',
                        //     'provinsi' => 'required|string|max:30',
                        //     'daerah' => 'required|string|max:30',
                        // ]);
    
                        // if ($validator->fails()) {
                        //     return false;
                        // }
    


                        $provinsi = $request->input('provinsi');
                        $daerah = $request->input('daerah');
                        $provinsi = ucwords(strtolower($provinsi));
                        $daerah = ucwords(strtolower($daerah));

                        $data = PreferensiLokasiMahasiswaModel::where('id_preferensi_lokasi', $id_preferensi)
                            ->first();

                        $latitude = $data->latitude;
                        $longitude = $data->longitude;



                        if ($data->provinsi != $provinsi || $data->daerah != $daerah) {
                            $lokasi = $this->latitudeLongitude($provinsi, $daerah);
                            $latitude = $lokasi->getCoordinates()->getLatitude();
                            $longitude = $lokasi->getCoordinates()->getLongitude();
                        }


                        PreferensiLokasiMahasiswaModel::where('id_preferensi_lokasi', $id_preferensi)
                            ->update([
                                'provinsi' => $provinsi,
                                'daerah' => $daerah,
                                'latitude' => $latitude,
                                'longitude' => $longitude,
                            ]);

                        return true;
                    }
                );
                return response()->json(['success' => $results]);
            } catch (\Throwable $e) {
                Log::error("Gagal update perusahaan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
