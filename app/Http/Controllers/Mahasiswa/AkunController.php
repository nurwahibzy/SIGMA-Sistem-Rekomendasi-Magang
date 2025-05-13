<?php

namespace App\Http\Controllers\Mahasiswa;

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

class AkunController extends Controller
{
    public function getProfil()
    {
        $akun = $this->allDataProfil();
        // $bidang = BidangModel::get();
        // $jenis = JenisPerusahaanModel::get();
        // return view(
        //     'tes.keahlian',
        //     [
        //         'pengalaman' => $akun->mahasiswa->pengalaman,
        //         'bidang' => $bidang,
        //         'keahlian' => $akun->mahasiswa->keahlian_mahasiswa,
        //         'kompetensi' => $akun->mahasiswa->kompetensi,
        //         'jenis' => $jenis,
        //         'preferensi_perusahaan' => $akun->mahasiswa->preferensi_perusahaan_mahasiswa,
        //     ]
        // );
        return response()->json($akun);
    }

    public function getEditProfil()
    {
        $akun = $this->allDataProfil();
        return response()->json($akun);
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
    public function getKeahlian($id_keahlian)
    {
        $keahlian = KeahlianMahasiswaModel::with('bidang:id_bidang,nama')
            ->where('id_keahlian_mahasiswa', $id_keahlian)
            ->get(['id_keahlian_mahasiswa', 'id_bidang', 'prioritas', 'keahlian']);
        return response()->json($keahlian);
    }

    public function postKeahlian()
    {

    }

    public function putKeahlian()
    {

    }

    public function deleteKeahlian()
    {

    }

    private function checkLastPrioritas()
    {

    }

    // crud pengelaman
    public function getPengalaman($id_pengalaman)
    {
        $pengalaman = PengalamanModel::where('id_pengalaman', $id_pengalaman)->first(['id_pengalaman', 'deskripsi']);
        // return response()->json($pengalaman);
        return view('tes.pengalaman', ['pengalaman' => $pengalaman]);
    }

    public function postpengalaman(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $id_mahasiswa = $this->idMahasiswa();
            PengalamanModel::insert([
                'id_mahasiswa' => $id_mahasiswa,
                'deskripsi' => $request->input('deskripsi')
            ]);
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function putpengalaman(Request $request, $id_pengalaman)
    {
        if ($request->ajax() || $request->wantsJson()) {
            PengalamanModel::where('id_pengalaman', $id_pengalaman)
            ->update([
                'deskripsi' => $request->input('deskripsi')
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


    // public function postKeahlian(Request $request)
    // {

    //     $panjang = count($request->id_keahlian);

    //     [$removed_id, $lates_id] = $this->deleteKeahlian($request);
    //     $updated_id = $this->updateKeahlian($request, $removed_id, $lates_id, $panjang);
    //     $this->insertKeahlian($request, $panjang);

    //     // dd($request->all());
    //     return response()->json($request);
    // }

    // private function deleteKeahlian($request)
    // {
    //     $current_id = array_map('intval', $request->id_keahlian);
    //     ;

    //     $lates_id = $akun = AkunModel::with(
    //         'mahasiswa:id_mahasiswa,id_akun',
    //         'mahasiswa.keahlian_mahasiswa:id_keahlian_mahasiswa,id_mahasiswa'
    //     )
    //         ->where('id_akun', Auth::user()->id_akun)
    //         ->first(['id_akun', 'id_level']);
    //     $lates_id = $lates_id->mahasiswa->keahlian_mahasiswa->pluck('id_keahlian_mahasiswa')->toArray();

    //     $removed_id = array_values(array_diff($lates_id, $current_id));

    //     $keahlian = KeahlianMahasiswaModel::destroy($removed_id);


    //     return [$removed_id, $lates_id];
    // }

    // private function updateKeahlian($request, $removed_id, $lates_id, $panjang)
    // {
    //     $updated_id = array_values(array_diff($lates_id, $removed_id));

    //     for ($i = 0; $i < $panjang; $i++) {
    //         KeahlianMahasiswaModel::where('id_keahlian_mahasiswa', $request->id_keahlian[$i])
    //             ->update([
    //                 'id_bidang' => $request->id_bidang[$i],
    //                 'keahlian' => $request->keahlian[$i]
    //             ]);
    //     }

    //     return $updated_id;
    // }

    // private function insertKeahlian($request, $panjang)
    // {
    //     $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
    //         ->where('id_akun', Auth::user()->id_akun)
    //         ->first(['id_akun', 'id_level'])
    //         ->mahasiswa
    //         ->id_mahasiswa;
    //     for ($i = $panjang; $i < count($request->id_bidang); $i++) {
    //         KeahlianMahasiswaModel::insert([
    //             'id_mahasiswa' => $id_mahasiswa,
    //             'id_bidang' => $request->id_bidang[$i],
    //             'keahlian' => $request->keahlian[$i]
    //         ]);
    //     }
    // }

    // crud pengalaman
    // public function postPengalaman(Request $request)
    // {
    //     $panjang = count($request->id_pengalaman);

    //     [$removed_id, $lates_id] = $this->deletePengalaman($request);
    //     $updated_id = $this->updatePengalaman($request, $removed_id, $lates_id, $panjang);
    //     $this->insertPengalaman($request, $panjang);

    //     // dd($request->all());
    //     return response()->json($removed_id);
    // }

    // private function deletePengalaman($request)
    // {
    //     $current_id = array_map('intval', $request->id_pengalaman);

    //     $lates_id = $akun = AkunModel::with(
    //         'mahasiswa:id_mahasiswa,id_akun',
    //         'mahasiswa.pengalaman:id_pengalaman,id_mahasiswa'
    //     )
    //         ->where('id_akun', Auth::user()->id_akun)
    //         ->first(['id_akun', 'id_level']);
    //     $lates_id = $lates_id->mahasiswa->pengalaman->pluck('id_pengalaman')->toArray();

    //     $removed_id = array_values(array_diff($lates_id, $current_id));

    //     $keahlian = PengalamanModel::destroy($removed_id);


    //     return [$removed_id, $lates_id];
    // }

    // private function updatePengalaman($request, $removed_id, $lates_id, $panjang)
    // {
    //     $updated_id = array_values(array_diff($lates_id, $removed_id));

    //     for ($i = 0; $i < $panjang; $i++) {
    //         PengalamanModel::where('id_pengalaman', $request->id_pengalaman[$i])
    //             ->update(['deskripsi' => $request->deskripsi[$i]]);
    //     }

    //     return $updated_id;
    // }

    // private function insertPengalaman($request, $panjang)
    // {
    //     $id_mahasiswa = AkunModel::with('mahasiswa:id_mahasiswa,id_akun')
    //         ->where('id_akun', Auth::user()->id_akun)
    //         ->first(['id_akun', 'id_level'])
    //         ->mahasiswa
    //         ->id_mahasiswa;
    //     for ($i = $panjang; $i < count($request->deskripsi); $i++) {
    //         PengalamanModel::insert([
    //             'id_mahasiswa' => $id_mahasiswa,
    //             'deskripsi' => $request->deskripsi[$i]
    //         ]);
    //     }
    // }

    // crud kompetensi
    // public function postKompetensi(Request $request)
    // {
    //     $panjang = count($request->id_bidang ?? []);

    //     [$removed_id, $lates_id] = $this->deleteKompetensi($request, $panjang);
    //     $this->insertKompetensi($request, $panjang, $lates_id);

    //     // dd(vars: $request->all());
    //     return response()->json($removed_id);
    // }

    // private function deleteKompetensi($request, $panjang)
    // {
    //     if ($panjang == 0) {
    //         $current_id = [];
    //     } else {
    //         $current_id = array_map('intval', $request->id_bidang);
    //     }

    //     $lates_id = $akun = AkunModel::with(
    //         'mahasiswa:id_mahasiswa,id_akun',
    //         'mahasiswa.kompetensi:id_kompetensi,id_mahasiswa,id_bidang'
    //     )
    //         ->where('id_akun', Auth::user()->id_akun)
    //         ->first(['id_akun', 'id_level']);
    //     $lates_id = $lates_id->mahasiswa->kompetensi->pluck('id_bidang')->toArray();

    //     $removed_id = array_values(array_diff($lates_id, $current_id));

    //     KompetensiModel::whereIn('id_bidang', $removed_id)->delete();

    //     return [$removed_id, $lates_id];
    // }


    // private function insertKompetensi($request, $panjang, $lates_id)
    // {
    //     if ($panjang == 0) {
    //         $id_bidang = [];
    //     } else {
    //         $id_bidang = array_map('intval', $request->id_bidang);
    //     }

    //     $id_bidang = array_values(array_diff($id_bidang, $lates_id));

    //     $id_mahasiswa = AkunModel::with('mahasiswa:id_mahasiswa,id_akun')
    //         ->where('id_akun', Auth::user()->id_akun)
    //         ->first(['id_akun', 'id_level'])
    //         ->mahasiswa
    //         ->id_mahasiswa;
    //     for ($i = 0; $i < count($id_bidang); $i++) {
    //         KompetensiModel::insert([
    //             'id_mahasiswa' => $id_mahasiswa,
    //             'id_bidang' => $id_bidang[$i]
    //         ]);
    //     }
    // }

    // crud preferensi perusahaan
    public function postPreferensiPerusahaan(Request $request)
    {
        // try {
        $panjang = count($request->id_jenis ?? []);

        [$removed_id, $lates_id] = $this->deletePreferensiPerusahaan($request, $panjang);
        $this->insertPreferensiPerusahaan($request, $panjang, $lates_id);

        dd(vars: $request->all());
        // return response()->json($id_jenis);
        // return response()->json($lates_id);
        // } catch (\Throwable $th) {
        //     return response()->json($request);
        // }
    }

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
        // return $id_jenis;

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

    // crud dokumen
    public function getDokumen()
    {

    }

    public function getDetailDokumen()
    {

    }
}
