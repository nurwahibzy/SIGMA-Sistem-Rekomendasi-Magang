<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\DokumenModel;
use App\Models\MahasiswaModel;
use App\Models\PreferensiLokasiMahasiswaModel;
use DB;
use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\BidangModel;
use App\Models\JenisPerusahaanModel;
use App\Models\KeahlianDosenModel;
use App\Models\KeahlianMahasiswaModel;
use App\Models\KompetensiModel;
use App\Models\PengalamanModel;
use App\Models\PreferensiPerusahaanMahasiswaModel;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Illuminate\Support\Facades\Storage;
use Log;


class AkunController extends Controller
{
    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }
    public function getProfil()
    {
        $akun = $this->allDataProfil();
        $bidang = BidangModel::get();
        $jenis = JenisPerusahaanModel::get();
        return view(
            'tes.keahlian',
            [
                'pengalaman' => $akun->mahasiswa->pengalaman,
                'bidang' => $bidang,
                'keahlian' => $akun->mahasiswa->keahlian_mahasiswa,
                'kompetensi' => $akun->mahasiswa->kompetensi,
                'jenis' => $jenis,
                'preferensi_perusahaan' => $akun->mahasiswa->preferensi_perusahaan_mahasiswa,
            ]
        );
    }

    public function getEditProfil()
    {
        $akun = $this->allDataProfil();
        return view('tes.dashboard', ['akun' => $akun]);
        // return response()->json($akun);
    }

    private function allDataProfil()
    {
        $akun = AkunModel::with(
            'mahasiswa:id_mahasiswa,id_akun,id_prodi,nama,alamat,telepon,tanggal_lahir,email',
            'mahasiswa.prodi:id_prodi,nama_prodi,nama_jurusan',
            'mahasiswa.pengalaman:id_pengalaman,id_mahasiswa,deskripsi',
            'mahasiswa.dokumen:id_dokumen,id_mahasiswa,nama,file_path',
            'mahasiswa.preferensi_lokasi_mahasiswa:id_preferensi_lokasi,id_mahasiswa,provinsi,daerah',
            'mahasiswa.preferensi_perusahaan_mahasiswa:id_preferensi_perusahaan,id_mahasiswa,id_jenis',
            'mahasiswa.preferensi_perusahaan_mahasiswa.jenis_perusahaan:id_jenis,jenis',
            'mahasiswa.keahlian_mahasiswa:id_keahlian_mahasiswa,id_mahasiswa,id_bidang,keahlian',
            'mahasiswa.keahlian_mahasiswa.bidang:id_bidang,nama',
        )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_user']);
        return $akun;
    }

    // crud akun

    public function putAkun(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request) {
                    $id_mahasiswa = $this->idMahasiswa();
                    $nama = $request->input('nama');
                    $alamat = $request->input('alamat');
                    $telepon = $request->input('telepon');
                    $tanggal_lahir = $request->input('tanggal_lahir');
                    $email = $request->input('email');

                    if ($request->filled('password')) {
                        $password = $request->input('password');
                        AkunModel::with('mahasiswa:id_akun,id_mahasiswa')
                            ->whereHas('mahasiswa', function ($query) use ($id_mahasiswa) {
                                $query->where('id_mahasiswa', $id_mahasiswa);
                            })
                            ->update([
                                'password' => Hash::make($password)
                            ]);
                    }

                    MahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
                        ->update([
                            'nama' => $nama,
                            'alamat' => $alamat,
                            'telepon' => $telepon,
                            'tanggal_lahir' => $tanggal_lahir,
                            'email' => $email
                        ]);
                });
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Gagal update profil: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function getFoto()
    {
        $id_mahasiswa = $this->idMahasiswa();
        $foto_path = MahasiswaModel::with('akun:id_akun,foto_path')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->first(['id_akun']);
        return response()->json($foto_path);
    }

    public function putFoto(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request) {
                        $id_mahasiswa = $this->idMahasiswa();
                        $data = MahasiswaModel::with('akun:id_akun,id_user,foto_path')
                            ->where('id_mahasiswa', $id_mahasiswa)
                            ->first(['id_akun']);

                        $file = $request->file('file');
                        $filename = $data->akun->id_user . '.' . $file->getClientOriginalExtension();
                        Storage::disk('public')->delete("dokumen/profil/akun/{$data->akun->foto_path}");
                        $file->storeAs('public/dokumen/profil/akun', $filename);
                        AkunModel::where('id_akun', $data->akun->id_akun)
                            ->update([
                                'foto_path' => $filename
                            ]);
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update foto: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    // crud preferensi lokasi
    // public function putPreferensiLokasi(Request $request, $id_preferensi)
    // {
    //     $provinsi = $request->input('provinsi');
    //     $daerah = $request->input('daerah');
    //     $alamat = "$daerah, $provinsi, Indonesia";

    //     $httpClient = new GuzzleAdapter();
    //     $provider = Nominatim::withOpenStreetMapServer($httpClient, 'my-laravel-app');
    //     $geocoder = new StatefulGeocoder($provider, 'en');

    //     $result = $geocoder->geocodeQuery(GeocodeQuery::create($alamat))->first();

    //     if (!$result) {
    //         return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
    //     }

    //     PreferensiLokasiMahasiswaModel::where('id_preferensi_lokasi', $id_preferensi)
    //         ->update([
    //             'provinsi' => $provinsi,
    //             'daerah' => $daerah,
    //             'latitude' => $result->getCoordinates()->getLatitude(),
    //             'longitude' => $result->getCoordinates()->getLongitude(),
    //         ]);

    //     return response()->json([
    //         'alamat' => $alamat,
    //         'latitude' => $result->getCoordinates()->getLatitude(),
    //         'longitude' => $result->getCoordinates()->getLongitude(),
    //     ]);
    // }
}
