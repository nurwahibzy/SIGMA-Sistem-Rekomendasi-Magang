<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\JenisPerusahaanModel;
use App\Models\LowonganMagangModel;
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


            $lowongan = DB::table('lowongan_magang')
            ->select(
                'lowongan_magang.id_lowongan',
                'lowongan_magang.nama as lowongan',
                'bidang.nama as bidang',
                DB::raw("COALESCE(AVG(penilaian.tugas), 0) as tugas"),
                DB::raw("COALESCE(AVG(penilaian.pembinaan), 0) as pembinaan"),
                DB::raw("COALESCE(AVG(penilaian.fasilitas), 0) as fasilitas"),
                DB::raw("CASE WHEN COUNT(penilaian.id_penilaian) = 0 THEN 'baru' ELSE 'lama' END as status")
            )
            ->leftJoin('periode_magang', 'periode_magang.id_lowongan', '=', 'lowongan_magang.id_lowongan')
            ->leftJoin('magang', 'magang.id_periode', '=', 'periode_magang.id_periode')
            ->leftJoin('penilaian', 'penilaian.id_magang', '=', 'magang.id_magang')
            ->join('perusahaan', 'perusahaan.id_perusahaan', '=', 'lowongan_magang.id_perusahaan')
            ->join('bidang', 'bidang.id_bidang', '=', 'lowongan_magang.id_bidang')
            ->where('perusahaan.id_perusahaan', $id_perusahaan)
            ->groupBy(
                'lowongan_magang.id_lowongan',
                'lowongan_magang.nama',
                'bidang.nama'
            )
            ->get();
        
        return view('admin.perusahaan.detail', ['perusahaan' => $perusahaan, 'lowongan' => $lowongan]);
        // return response()->json($data);
    }

    private function checkTelepon($telepon, $id_perusahaan = false)
    {
        if ($id_perusahaan) {
            $amount = PerusahaanModel::where('telepon', $telepon)
                ->whereNot('id_perusahaan', $id_perusahaan)
                ->count();
            if ($amount != 0) {
                return true;
            }
        } else {
            $amount = PerusahaanModel::where('telepon', $telepon)->count();
            if ($amount != 0) {
                return true;
            }
        }
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

    private function checkNama($nama, $id_perusahaan = false)
    {
        if ($id_perusahaan) {
            $amount = PerusahaanModel::where('nama', $nama)
                ->whereNot('id_perusahaan', $id_perusahaan)
                ->count();
            if ($amount != 0) {
                return true;
            }
        } else {
            $amount = PerusahaanModel::where('nama', $nama)->count();
            if ($amount != 0) {
                return true;
            }
        }
        return false;
    }

    public function postPerusahaan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request) {

                        $validator = Validator::make($request->all(), [
                            'file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                            'id_jenis' => 'required|exists:jenis_perusahaan,id_jenis',
                            'nama' => 'required|string|max:100',
                            'telepon' => 'required|digits_between:1,30',
                            'deskripsi' => 'required|string',
                            'provinsi' => 'required|string|max:30',
                            'daerah' => 'required|string|max:30',
                        ]);

                        if (!$request->hasFile('file')) {
                            return ['success' => false, 'message' => 'Logo Perusahaan Harus Diisi!!!'];
                        }

                        if ($validator->fails()) {
                            return ['success' => false, 'message' => 'Data Tidak Valid'];
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
                            return ['success' => false, 'message' => 'Nomor Telepon Tidak Boleh Sama!!!'];
                        }
                        if ($this->checkNama($nama)) {
                            return ['success' => false, 'message' => 'Nama Perusahaan Tidak Boleh Sama!!!'];
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

                            return ['success' => true];
                        } else {
                            return ['success' => false, 'message' => 'Lokasi Tidak Diketahui'];
                        }
                    }
                );
                return response()->json($results);
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
                            return ['success' => false, 'message' => 'Data Tidak Valid'];
                        }


                        $id_jenis = $request->input('id_jenis');
                        $nama = $request->input('nama');
                        $telepon = $request->input('telepon');
                        $deskripsi = $request->input('deskripsi');
                        $provinsi = $request->input('provinsi');
                        $daerah = $request->input('daerah');
                        $provinsi = ucwords(strtolower($provinsi));
                        $daerah = ucwords(strtolower($daerah));

                        if ($this->checkTelepon($telepon, $id_perusahaan)) {
                            return ['success' => false, 'message' => 'Nomor Telepon Tidak Boleh Sama!!!'];
                        }
                        if ($this->checkNama($nama, $id_perusahaan)) {
                            return ['success' => false, 'message' => 'Nama Perusahaan Tidak Boleh Sama!!!'];
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

                        return ['success' => true];
                    }
                );
                return response()->json($results);
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

                        $perusahaan = PerusahaanModel::with('lowongan_magang.periode_magang.magang.aktivitas_magang')
                            ->where('id_perusahaan', $id_perusahaan)
                            ->first();

                        $foto_path = $perusahaan->foto_path;

                        foreach ($perusahaan->lowongan_magang as $lowongan) {
                            foreach ($lowongan->periode_magang as $periode) {
                                foreach ($periode->magang as $magang) {
                                    foreach ($magang->aktivitas_magang as $aktivitas) {
                                        $foto_path = $aktivitas->foto_path;

                                        if (Storage::disk('public')->exists("aktivitas/$foto_path")) {
                                            Storage::disk('public')->delete("aktivitas/$foto_path");
                                        }
                                    }
                                }
                            }
                        }

                        return response()->json($perusahaan);

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
