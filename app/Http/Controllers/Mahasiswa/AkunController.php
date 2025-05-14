<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\DokumenModel;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter; // gunakan Guzzle7 adapter
use Illuminate\Support\Facades\Storage;


class AkunController extends Controller
{
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
        // return response()->json($akun);
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
    public function postAkun(Request $request)
    {

    }

    public function updateAkun()
    {

    }

    // crud keahlian
    public function getAddKeahlian()
    {
        $bidang = BidangModel::whereNotIn('id_bidang', $this->bidangDipilih())->get(['id_bidang', 'nama']);
        $jumlahBidangDipilih = count($this->bidangDipilih());
        $data = [
            'bidang' => $bidang,
            'prioritas' => $jumlahBidangDipilih + 1
        ];
        return view('tes.addKeahlian', ['data' => $data]);
        // return response()->json($data);
    }
    public function getKeahlian($id_keahlian)
    {
        $pilihanTerakhir = KeahlianMahasiswaModel::where('id_keahlian_mahasiswa', $id_keahlian)->first(['id_keahlian_mahasiswa', 'id_bidang', 'prioritas', 'keahlian']);
        $bidang = BidangModel::whereNotIn('id_bidang', array_diff($this->bidangDipilih(), [$pilihanTerakhir->id_bidang]))->get(['id_bidang', 'nama']);
        $jumlahBidangDipilih = count($this->bidangDipilih());
        $data = [
            'bidang' => $bidang,
            'prioritas' => $jumlahBidangDipilih,
            'pilihan_terakhir' => $pilihanTerakhir
        ];
        return view('tes.editKeahlian', ['data' => $data]);
        // return response()->json($data);
    }

    public function postKeahlian(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            DB::transaction(function () use ($request) {
                $id_mahasiswa = $this->idMahasiswa();
                $id_bidang = $request->input('id_bidang');
                $prioritas = $request->input('prioritas');
                $keahlian = $request->input('keahlian');
                $jumlahBidangDipilih = count($this->bidangDipilih());

                if ($prioritas == $jumlahBidangDipilih + 1) {
                    $this->insertKeahlian($id_mahasiswa, $id_bidang, $prioritas, $keahlian);
                } else {
                    $this->updatePrioritas($id_mahasiswa, $prioritas);
                    $this->insertKeahlian($id_mahasiswa, $id_bidang, $prioritas, $keahlian);
                }
            });
        }
    }


    private function insertKeahlian($id_mahasiswa, $id_bidang, $prioritas, $keahlian)
    {
        KeahlianMahasiswaModel::insert([
            'id_mahasiswa' => $id_mahasiswa,
            'id_bidang' => $id_bidang,
            'prioritas' => $prioritas,
            'keahlian' => $keahlian
        ]);
    }

    private function updatePrioritas($id_mahasiswa, $prioritas)
    {
        KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('prioritas', '>=', $prioritas)
            ->orderBy('prioritas', 'desc')
            ->get()
            ->each(function ($keahlianMahasiswa) {
                $keahlianMahasiswa->prioritas += 1;
                $keahlianMahasiswa->save();
            });
    }

    public function putKeahlian(Request $request, $id_keahlian)
    {
        if ($request->ajax() || $request->wantsJson()) {
            DB::transaction(function () use ($request, $id_keahlian) {
                $pilihanTerakhir = KeahlianMahasiswaModel::where('id_keahlian_mahasiswa', $id_keahlian)
                    ->first(['id_keahlian_mahasiswa', 'id_mahasiswa', 'id_bidang', 'prioritas', 'keahlian']);

                $id_bidang = $request->input('id_bidang');
                $prioritasBaru = $request->input('prioritas');
                $keahlian = $request->input('keahlian');
                $prioritasLama = $pilihanTerakhir->prioritas;
                $id_mahasiswa = $pilihanTerakhir->id_mahasiswa;

                KeahlianMahasiswaModel::where('id_keahlian_mahasiswa', $id_keahlian)
                    ->update([
                        'prioritas' => null
                    ]);

                if ($prioritasBaru < $prioritasLama) {
                    $this->updateTurunPrioritas($id_mahasiswa, $prioritasBaru, $prioritasLama);
                } elseif ($prioritasBaru > $prioritasLama) {
                    $this->updateNaikPrioritas($id_mahasiswa, $prioritasLama + 1, $prioritasBaru + 1);
                }

                $this->updateKeahlian($id_keahlian, $id_bidang, $prioritasBaru, $keahlian);
            });
        }
    }

    private function updateTurunPrioritas($id_mahasiswa, $prioritasAwal, $prioritasAkhir)
    {
        KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('prioritas', '>=', $prioritasAwal)
            ->where('prioritas', '<', $prioritasAkhir)
            ->orderBy('prioritas', 'desc')
            ->get()
            ->each(function ($keahlian) {
                $keahlian->prioritas += 1;
                $keahlian->save();
            });
    }

    private function updateNaikPrioritas($id_mahasiswa, $prioritasAwal, $prioritasAkhir)
    {
        KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('prioritas', '>=', $prioritasAwal)
            ->where('prioritas', '<', $prioritasAkhir)
            ->orderBy('prioritas', 'asc')
            ->get()
            ->each(function ($keahlian) {
                $keahlian->prioritas -= 1;
                $keahlian->save();
            });
    }

    public function updateKeahlian($id_keahlian, $id_bidang, $prioritas, $keahlian)
    {
        KeahlianMahasiswaModel::where('id_keahlian_mahasiswa', $id_keahlian)
            ->update([
                'id_bidang' => $id_bidang,
                'prioritas' => $prioritas,
                'keahlian' => $keahlian
            ]);
    }

    public function deleteKeahlian(Request $request, $id_keahlian, $prioritas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            DB::transaction(function () use ($request, $id_keahlian, $prioritas) {
                $id_mahasiswa = $this->idMahasiswa();
                KeahlianMahasiswaModel::where('id_keahlian_mahasiswa', $id_keahlian)->delete();
                KeahlianMahasiswaModel::where('id_mahasiswa', $id_mahasiswa)
                    ->where('prioritas', '>', $prioritas)
                    ->orderBy('prioritas', 'asc')
                    ->get()
                    ->each(function ($keahlianMahasiswa) {
                        $keahlianMahasiswa->prioritas -= 1;
                        $keahlianMahasiswa->save();
                    });
            });
        }
    }

    private function bidangDipilih()
    {
        $bidangTerakhir = KeahlianMahasiswaModel::where('id_mahasiswa', $this->idMahasiswa())->get();
        $bidangDipilih = $bidangTerakhir->pluck('id_bidang')->toArray();
        return $bidangDipilih;
    }

    // crud pengelaman
    public function getAddPengalaman()
    {
        return view('tes.pengalaman');
    }
    public function getPengalaman($id_pengalaman)
    {
        $pengalaman = PengalamanModel::where('id_pengalaman', $id_pengalaman)->first(['id_pengalaman', 'deskripsi']);
        return view('tes.editPengalaman', ['pengalaman' => $pengalaman]);
    }

    public function postpengalaman(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            DB::transaction(function () use ($request, ) {
                $id_mahasiswa = $this->idMahasiswa();
                PengalamanModel::insert([
                    'id_mahasiswa' => $id_mahasiswa,
                    'deskripsi' => $request->input('deskripsi')
                ]);
                return response()->json([
                    'status' => 'success'
                ]);
            });
        }
    }

    public function putpengalaman(Request $request, $id_pengalaman)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $deskripsi = $request->input('deskripsi');
            PengalamanModel::where('id_pengalaman', $id_pengalaman)
                ->update([
                    'deskripsi' => $deskripsi
                ]);
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function deletepengalaman(Request $request, $id_pengalaman)
    {
        if ($request->ajax() || $request->wantsJson()) {
            PengalamanModel::where('id_pengalaman', $id_pengalaman)->delete();
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }


    // crud preferensi perusahaan
    public function postPreferensiPerusahaan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            DB::transaction(function () use ($request, ) {
                $panjang = count($request->input('id_jenis') ?? []);

                [$removed_id, $lates_id] = $this->deletePreferensiPerusahaan($request, $panjang);
                $this->insertPreferensiPerusahaan($request, $panjang, $lates_id);
            });
        }
    }

    //     public function postPreferensiPerusahaan(Request $request)
// {
//     if ($request->ajax() || $request->wantsJson()) {
//         try {
//             $result = DB::transaction(function () use ($request) {
//                 $panjang = count($request->input('id_jenis') ?? []);

    //                 [$removed_id, $lates_id] = $this->deletePreferensiPerusahaan($request, $panjang);
//                 $this->insertPreferensiPerusahaan($request, $panjang, $lates_id);

    //                 return response()->json([
//                     'success' => true,
//                     'message' => 'Preferensi perusahaan berhasil diperbarui.',
//                     'deleted_ids' => $removed_id,
//                     'latest_id' => $lates_id
//                 ]);
//             });

    //             return $result;

    //         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Terjadi kesalahan saat menyimpan preferensi.',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }

    //     return response()->json([
//         'success' => false,
//         'message' => 'Request tidak valid.'
//     ], 400);
// }


    private function deletePreferensiPerusahaan($request, $panjang)
    {
        if ($panjang == 0) {
            $current_id = [];
        } else {
            $current_id = array_map('intval', $request->id_jenis);
        }

        $lates_id = $akun = AkunModel::with(
            'mahasiswa:id_mahasiswa,id_akun',
            'mahasiswa.preferensi_perusahaan_mahasiswa:id_preferensi_perusahaan,id_mahasiswa,id_jenis'
        )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level']);

        $lates_id = $lates_id->mahasiswa->preferensi_perusahaan_mahasiswa->pluck('id_jenis')->toArray();
        $removed_id = array_values(array_diff($lates_id, $current_id));

        PreferensiPerusahaanMahasiswaModel::whereIn('id_jenis', $removed_id)->delete();

        return [$removed_id, $lates_id];
    }

    private function insertPreferensiPerusahaan($request, $panjang, $lates_id)
    {
        if ($panjang == 0) {
            $id_jenis = [];
        } else {
            $id_jenis = array_map('intval', $request->id_jenis);
        }

        $id_jenis = array_values(array_diff($id_jenis, $lates_id));

        $id_mahasiswa = AkunModel::with('mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        for ($i = 0; $i < count($id_jenis); $i++) {
            PreferensiPerusahaanMahasiswaModel::insert([
                'id_mahasiswa' => $id_mahasiswa,
                'id_jenis' => $id_jenis[$i]
            ]);
        }
    }

    // crud preferensi lokasi
    public function putPreferensiLokasi(Request $request, $id_preferensi)
    {
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

        return response()->json([
            'alamat' => $alamat,
            'latitude' => $result->getCoordinates()->getLatitude(),
            'longitude' => $result->getCoordinates()->getLongitude(),
        ]);
    }

    // crud dokumen
    public function getAddDokumen()
    {
        return view('tes.dokumen');
    }
    public function getDokumen($id_dokumen)
    {
        $dokumen = DokumenModel::where('id_dokumen', $id_dokumen)->first(['id_dokumen', 'nama', 'file_path']);
        return view('tes.editDokumen', ['dokumen' => $dokumen]);
        // return view('tes.editPengalaman', ['dokumen' => $dokumen]);
        // return response()->json($dokumen);
    }

    public function postDokumen(Request $request)
    {
        try {
            if ($request->ajax() || $request->wantsJson()) {
                if ($request->hasFile('file')) {
                    $id_mahasiswa = $this->idMahasiswa();
                    $file = $request->file('file');
                    $nama = $request->input('nama');

                    $filename = $id_mahasiswa . '_' . $nama . '.' . $file->getClientOriginalExtension();
                    DokumenModel::where('id_mahasiswa', $id_mahasiswa)
                        ->insert([
                            'id_mahasiswa' => $id_mahasiswa,
                            'nama' => $nama,
                            'file_path' => $filename
                        ]);

                    $path = $file->storeAs('public/dokumen', $filename);

                    return response()->json([
                        'status' => true,
                        'message' => 'Dokumen berhasil diunggah.',
                        'redirect' => url('/'),
                        'path' => $path
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Dokumen tidak berhasil diunggah.',
                'redirect' => url('/')
            ]);
        }
    }

    public function putDokumen(Request $request, $id_dokumen)
    {
        try {
            if ($request->ajax() || $request->wantsJson()) {
                $id_mahasiswa = $this->idMahasiswa();
                $data = DokumenModel::where('id_dokumen', $id_dokumen)
                    ->firstOrFail(['file_path', 'nama']);

                $nama = $request->input('nama');

                if ($request->hasFile('file')) {
                    return $this->handleFileUpload($request, $data, $id_mahasiswa, $id_dokumen, $nama);
                }

                if ($data->nama !== $nama) {
                    return $this->renameFileOnly($data, $id_mahasiswa, $id_dokumen, $nama);
                }

                return response()->json(['status' => true, 'message' => 'Tidak ada perubahan.']);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Dokumen tidak berhasil diubah.',
                'error' => $th->getMessage(),
                'redirect' => url('/')
            ]);
        }
    }
    private function handleFileUpload(Request $request, $data, $id_mahasiswa, $id_dokumen, $nama)
    {
        $file = $request->file('file');
        $filename = $id_mahasiswa . '_' . $nama . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("dokumen/{$data->file_path}");
        $path = $file->storeAs('public/dokumen', $filename);
        DokumenModel::where('id_dokumen', $id_dokumen)
            ->update([
                'nama' => $nama,
                'file_path' => $filename
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Dokumen berhasil diunggah.',
            'redirect' => url('/'),
            'path' => $path
        ]);
    }

    private function renameFileOnly($data, $id_mahasiswa, $id_dokumen, $nama)
    {
        $lama = $data->file_path;
        $extension = pathinfo($lama, PATHINFO_EXTENSION);
        $file_path_baru = $id_mahasiswa . '_' . $nama . '.' . $extension;

        if (Storage::disk('public')->exists("dokumen/$lama")) {
            Storage::disk('public')->move("dokumen/$lama", "dokumen/$file_path_baru");

            DokumenModel::where('id_dokumen', $id_dokumen)
                ->update([
                    'nama' => $nama,
                    'file_path' => $file_path_baru
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Nama dokumen berhasil diperbarui.',
                'file_path_lama' => $lama,
                'file_path_baru' => $file_path_baru
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "File lama tidak ditemukan: dokumen/$lama"
        ]);
    }

    public function deleteDokumen(Request $request, $id_dokumen)
    {
        try {
            if ($request->ajax() || $request->wantsJson()) {
                $id_mahasiswa = $this->idMahasiswa();

                $dokumen = DokumenModel::where('id_dokumen', $id_dokumen)
                    ->where('id_mahasiswa', $id_mahasiswa)
                    ->firstOrFail(['file_path']);

                $file_path = $dokumen->file_path;

                if (Storage::disk('public')->exists("dokumen/$file_path")) {
                    Storage::disk('public')->delete("dokumen/$file_path");
                }

                DokumenModel::where('id_dokumen', $id_dokumen)
                    ->where('id_mahasiswa', $id_mahasiswa)
                    ->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Dokumen berhasil dihapus.',
                    'file_path' => $file_path
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Permintaan tidak valid.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus dokumen.',
                'error' => $th->getMessage()
            ]);
        }
    }
}
