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
use Validator;


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
            'mahasiswa.profil',
            [
                'pengalaman' => $akun->mahasiswa->pengalaman,
                'bidang' => $bidang,
                'keahlian' => $akun->mahasiswa->keahlian_mahasiswa->sortBy('prioritas'),
                'kompetensi' => $akun->mahasiswa->kompetensi,
                'jenis' => $jenis,
                'preferensi_perusahaan' => $akun->mahasiswa->preferensi_perusahaan_mahasiswa,
                'preferensi_lokasi' => $akun->mahasiswa->preferensi_lokasi_mahasiswa,
                'dokumen' => $akun->mahasiswa->dokumen,
            ]
        );
        // return response()->json($akun);
    }

    public function getEditProfil()
    {
        $akun = $this->allDataProfil();
        $bidang = BidangModel::get();
        $jenis = JenisPerusahaanModel::get();
        return view(
            'mahasiswa.akun.edit-profil',
            [
                'pengalaman' => $akun->mahasiswa->pengalaman,
                'bidang' => $bidang,
                'keahlian' => $akun->mahasiswa->keahlian_mahasiswa->sortBy('prioritas'),
                'kompetensi' => $akun->mahasiswa->kompetensi,
                'jenis' => $jenis,
                'preferensi_perusahaan' => $akun->mahasiswa->preferensi_perusahaan_mahasiswa,
                'preferensi_lokasi' => $akun->mahasiswa->preferensi_lokasi_mahasiswa,
                'dokumen' => $akun->mahasiswa->dokumen,
            ]
        );
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
            'mahasiswa.keahlian_mahasiswa:id_keahlian_mahasiswa,id_mahasiswa,id_bidang,prioritas,keahlian',
            'mahasiswa.keahlian_mahasiswa.bidang:id_bidang,nama',
        )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_user']);
        return $akun;
    }
    public function putAkun(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request) {
                        $validator = Validator::make($request->all(), [
                            'id_user' => 'required|digits_between:1,20',
                            'nama' => 'required|string|max:100',
                            'alamat' => 'required|string',
                            'telepon' => 'required|digits_between:1,30',
                            'tanggal_lahir' => 'required|date',
                            'email' => 'required|email|max:100',
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                            'password' => 'nullable|string|min:6|max:255'
                        ]);

                        if ($validator->fails()) {
                            return false;
                        }

                        $id_akun = Auth::user()->id_akun;
                        $id_user = $request->input('id_user');
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $data = AkunModel::where('id_akun', $id_akun)->first();

                        $foto_path = $data->foto_path;

                        if ($request->hasFile('file')) {
                            $foto_path = $this->handleFileUpload($request, $id_user, $foto_path);
                        } 

                        if ($request->filled('password')) {
                            $password = $request->input('password');
                            AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'password' => Hash::make($password),
                                    'foto_path' => $foto_path
                                ]);
                        } else {
                            AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'foto_path' => $foto_path
                                ]);
                        }

                        MahasiswaModel::with('akun:id_akun')
                            ->whereHas('akun', function ($query) use ($id_akun) {
                                $query->where('id_akun', $id_akun);
                            })
                            ->update([
                                'id_akun' => $id_akun,
                                'nama' => $nama,
                                'alamat' => $alamat,
                                'telepon' => $telepon,
                                'tanggal_lahir' => $tanggal_lahir,
                                'email' => $email
                            ]);

                        return true;
                    }
                );
                return response()->json(['success' => $results]);
            } catch (\Throwable $e) {
                Log::error("Gagal update user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    private function handleFileUpload(Request $request, $id_user, $foto_path)
    {
        $file = $request->file('file');
        $filename = $id_user . "." . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("profil/akun/{$foto_path}");
        $file->storeAs('public/profil/akun', $filename);
        return $filename;
    }


    public function getFoto()
    {
        $id_mahasiswa = $this->idMahasiswa();
        $foto_path = MahasiswaModel::with('akun:id_akun,foto_path')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->first(['id_akun']);
        return response()->json($foto_path);
    }
}
