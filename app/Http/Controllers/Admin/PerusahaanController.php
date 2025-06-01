<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\JenisPerusahaanModel;
use App\Models\MahasiswaModel;
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
        $data = DB::transaction(function () use ($id_perusahaan) {
            $perusahaan = PerusahaanModel::with('jenis_perusahaan:id_jenis,jenis')
                ->where('id_perusahaan', $id_perusahaan)
                ->first();

            $jenis = JenisPerusahaanModel::get(['id_jenis', 'jenis']);

            return [
                'perusahaan' => $perusahaan,
                'jenis' => $jenis
            ];
        });

        return view('admin.perusahaan.edit', $data);
    }

    public function getDetailPerusahaan($id_perusahaan)
    {
        $perusahaan = PerusahaanModel::with('jenis_perusahaan:id_jenis,jenis')
            ->where('id_perusahaan', $id_perusahaan)->first();
        return view('admin.perusahaan.detail', ['perusahaan' => $perusahaan]);
    }

    private function checkTelepon( $telepon)
    {
        $amount = AdminModel::where('telepon', $telepon)->count();
        if ($amount != 0) {
            return true;
        }
        $amount = MahasiswaModel::where('telepon', $telepon)->count();
        if ($amount != 0) {
            return true;
        }
        $amount = DosenModel::where('telepon', $telepon)->count();
        if ($amount != 0) {
            return true;
        }
        return false;
    }
    public function postPerusahaan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {

                $validator = Validator::make($request->all(), [
                    'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                    'id_jenis' => 'required|exists:jenis_perusahaan,id_jenis',
                    'nama' => 'required|string|max:100',
                    'telepon' => 'required|digits_between:1,30',
                    'deskripsi' => 'required|string',
                    'provinsi' => 'required|string|max:30',
                    'daerah' => 'required|string|max:30',
                ]);

                if ($validator->fails()) {
                    return response()->json(['success' => false]);
                }

                $id_jenis = $request->input('id_jenis');
                $nama = $request->input('nama');
                $telepon = $request->input('telepon');
                $deskripsi = $request->input('deskripsi');
                $provinsi = $request->input('provinsi');
                $daerah = $request->input('daerah');
                $file = $request->file('file');
                $provinsi = ucwords(strtolower($provinsi));
                $daerah = ucwords(strtolower($daerah));
                $slugifiedName = Str::slug($nama, '_');
                $filename = $slugifiedName . "." . $file->getClientOriginalExtension();

                if ($this->checkTelepon($telepon)) {
                    return false;
                }

                $lokasi = $this->latitudeLongitude($provinsi, $daerah);

                if ($lokasi) {
                    PerusahaanModel::create([
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
                $results = DB::transaction(
                    function () use ($request, $id_perusahaan) {
                        $validator = Validator::make($request->all(), [
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                            'id_jenis' => 'required|exists:jenis_perusahaan,id_jenis',
                            'nama' => 'required|string|max:100',
                            'telepon' => 'required|digits_between:1,30',
                            'deskripsi' => 'required|string',
                            'provinsi' => 'required|string|max:30',
                            'daerah' => 'required|string|max:30',
                        ]);

                        if ($validator->fails()) {
                            return false;
                        }


                        $id_jenis = $request->input('id_jenis');
                        $nama = $request->input('nama');
                        $telepon = $request->input('telepon');
                        $deskripsi = $request->input('deskripsi');
                        $provinsi = $request->input('provinsi');
                        $daerah = $request->input('daerah');
                        $provinsi = ucwords(strtolower($provinsi));
                        $daerah = ucwords(strtolower($daerah));

                        if ($this->checkTelepon($telepon)) {
                            return false;
                        }

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
                        } else if ($data->nama !== $nama) {
                            $this->renameFileOnly($data, $id_perusahaan, $id_jenis, $nama, $telepon, $deskripsi, $provinsi, $daerah, $latitude, $longitude);
                        } else {
                            PerusahaanModel::where('id_perusahaan', $id_perusahaan)
                                ->update([
                                    'id_jenis' => $id_jenis,
                                    'telepon' => $telepon,
                                    'deskripsi' => $deskripsi,
                                    'provinsi' => $provinsi,
                                    'daerah' => $daerah,
                                    'latitude' => $latitude,
                                    'longitude' => $longitude
                                ]);
                        }

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
        $slugifiedName = Str::slug($nama, '_');
        $file_path_baru = $slugifiedName . '.' . $extension;

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
