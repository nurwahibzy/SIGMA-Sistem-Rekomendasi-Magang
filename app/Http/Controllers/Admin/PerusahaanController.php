<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPerusahaanModel;
use App\Models\PerusahaanModel;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;
use Str;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Validator;

class PerusahaanController extends Controller
{
    // add peringatan, try catch, transaction
    public function getPerusahaan()
    {
        $perusahaan = PerusahaanModel::get();
        return view('admin.perusahaan.index', ['perusahaan' => $perusahaan]);

    }

    public function getAddPerusahaan()
    {
        $jenis = JenisPerusahaanModel::get(['id_jenis', 'jenis']);
        return view('admin.perusahaan.tambah', ['jenis' => $jenis]);
    }

    public function getEditPerusahaan($id_perusahaan)
    {
        $perusahaan = PerusahaanModel::with('jenis_perusahaan:id_jenis,jenis')
        ->where('id_perusahaan', $id_perusahaan)->first();
        return view('admin.perusahaan.edit', ['perusahaan' => $perusahaan]);
    }

    public function getDetailPerusahaan($id_perusahaan)
    {
        $perusahaan = PerusahaanModel::with('jenis_perusahaan:id_jenis,jenis')
            ->where('id_perusahaan', $id_perusahaan)->first();
        // return response()->json($perusahaan);
        return view('admin.perusahaan.detail', ['perusahaan' => $perusahaan]);
    }
    public function postPerusahaan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {

                $validator = Validator::make($request->all(), [
                    'file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                    'id_jenis' => 'required|exists:jenis_perusahaan,id_jenis',
                    'nama' => 'required|string|max:255',
                    'telepon' => 'required|digits_between:8,15',
                    'deskripsi' => 'required|string',
                    'provinsi' => 'required|string|max:255',
                    'daerah' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $id_jenis = $request->input('id_jenis');
                $nama = $request->input('nama');
                $telepon = $request->input('telepon');
                $deskripsi = $request->input('deskripsi');
                $provinsi = $request->input('provinsi');
                $daerah = $request->input('daerah');
                $file = $request->file('file');
                $slugifiedName = Str::slug($nama, '_');
                $filename = $slugifiedName . "." . $file->getClientOriginalExtension();

                $lokasi = $this->latitudeLongitude($provinsi, $daerah);

                if ($lokasi) {
                    PerusahaanModel::insert([
                        'id_jenis' => $id_jenis,
                        'nama' => $nama,
                        'telepon' => $telepon,
                        'deskripsi' => $deskripsi,
                        'foto_path' => $filename,
                        'provinsi' => $provinsi,
                        'daerah' => $daerah,
                        'latitude' => $lokasi->getCoordinates()->getLatitude(),
                        'longitude' => $lokasi->getCoordinates()->getLongitude(),
                    ]);
                    $file->storeAs('public/profil/perusahaan', $filename);
                }
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal menambahkan perusahaan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function latitudeLongitude($provinsi, $daerah)
    {
        $alamat = "$daerah, $provinsi, Indonesia";

        $httpClient = new GuzzleAdapter();
        $provider = Nominatim::withOpenStreetMapServer($httpClient, 'my-laravel-app');
        $geocoder = new StatefulGeocoder($provider, 'en');

        $result = $geocoder->geocodeQuery(GeocodeQuery::create($alamat))->first();
        return $result;

    }

    public function putPerusahaan(Request $request, $id_perusahaan)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_perusahaan) {
                        $id_jenis = $request->input('id_jenis');
                        $nama = $request->input('nama');
                        $telepon = $request->input('telepon');
                        $deskripsi = $request->input('deskripsi');
                        $provinsi = $request->input('provinsi');
                        $daerah = $request->input('daerah');

                        $data = PerusahaanModel::where('id_perusahaan', $id_perusahaan)
                            ->first();

                        $latitude = $data->latitude;
                        $longitude = $data->longitude;


                        if ($data->provinsi != $provinsi || $data->daerah != $daerah) {
                            $lokasi = $this->latitudeLongitude($provinsi, $daerah);
                            $latitude = $lokasi->getCoordinates()->getLatitude();
                            $longitude = $lokasi->getCoordinates()->getLongitude();
                        }


                        if ($request->hasFile('file')) {
                            $this->handleFileUpload($request, $data, $id_perusahaan, $id_jenis, $nama, $telepon, $deskripsi, $provinsi, $daerah, $latitude, $longitude);
                        }

                        if ($data->nama !== $nama) {
                            $this->renameFileOnly($data, $id_perusahaan, $id_jenis, $nama, $telepon, $deskripsi, $provinsi, $daerah, $latitude, $longitude);
                        }
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update perusahaan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    private function handleFileUpload(Request $request, $data, $id_perusahaan, $id_jenis, $nama, $telepon, $deskripsi, $provinsi, $daerah, $latitude, $longitude)
    {
        $file = $request->file('file');
        $slugifiedName = Str::slug($nama, '_');
        $filename = $slugifiedName . "." . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("profil/perusahaan/{$data->foto_path}");
        $file->storeAs('public/profil/perusahaan', $filename);
        PerusahaanModel::where('id_perusahaan', $id_perusahaan)
            ->update([
                'id_jenis' => $id_jenis,
                'nama' => $nama,
                'telepon' => $telepon,
                'deskripsi' => $deskripsi,
                'foto_path' => $filename,
                'provinsi' => $provinsi,
                'daerah' => $daerah,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
    }

    private function renameFileOnly($data, $id_perusahaan, $id_jenis, $nama, $telepon, $deskripsi, $provinsi, $daerah, $latitude, $longitude)
    {
        $lama = $data->foto_path;
        $extension = pathinfo($lama, PATHINFO_EXTENSION);
        $file_path_baru = $nama . '.' . $extension;

        if (Storage::disk('public')->exists("profil/perusahaan/$lama")) {
            Storage::disk('public')->move("profil/perusahaan/$lama", "profil/perusahaan/$file_path_baru");

            PerusahaanModel::where('id_perusahaan', $id_perusahaan)
                ->update([
                    'id_jenis' => $id_jenis,
                    'nama' => $nama,
                    'telepon' => $telepon,
                    'deskripsi' => $deskripsi,
                    'foto_path' => $file_path_baru,
                    'provinsi' => $provinsi,
                    'daerah' => $daerah,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);
        }
    }

    public function deletePerusahaan(Request $request, $id_perusahaan)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_perusahaan) {

                        $dokumen = PerusahaanModel::where('id_perusahaan', $id_perusahaan)
                            ->first(['foto_path']);

                        $foto_path = $dokumen->foto_path;

                        if (Storage::disk('public')->exists("profil/perusahaan/$foto_path")) {
                            Storage::disk('public')->delete("profil/perusahaan/$foto_path");
                        }

                        PerusahaanModel::where('id_perusahaan', $id_perusahaan)
                            ->delete();
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menghapus perusahaan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
