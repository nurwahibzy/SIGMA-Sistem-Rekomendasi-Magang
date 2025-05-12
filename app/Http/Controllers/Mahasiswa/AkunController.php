<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\BidangModel;
use App\Models\KeahlianDosenModel;
use App\Models\KeahlianMahasiswaModel;
use App\Models\PengalamanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkunController extends Controller
{
    public function getProfil(){
        $akun = AkunModel::with(
            'mahasiswa:id_mahasiswa,id_akun,id_prodi,nim,nama,alamat,telepon,tanggal_lahir,email',
            'mahasiswa.prodi:id_prodi,nama_prodi,nama_jurusan',
            'mahasiswa.pengalaman:id_pengalaman,id_mahasiswa,deskripsi',
            'mahasiswa.dokumen:id_dokumen,id_mahasiswa,nama,file_path',
            'mahasiswa.preferensi_lokasi_mahasiswa:id_preferensi_lokasi,id_mahasiswa,provinsi,daerah',
            'mahasiswa.preferensi_perusahaan_mahasiswa:id_preferensi_perusahaan,id_mahasiswa,id_jenis',
            'mahasiswa.preferensi_perusahaan_mahasiswa.jenis_perusahaan:id_jenis,jenis',
            'mahasiswa.kompetensi:id_kompetensi,id_mahasiswa,id_bidang',
            'mahasiswa.kompetensi.bidang:id_bidang,nama',
            'mahasiswa.keahlian_mahasiswa:id_keahlian_mahasiswa,id_mahasiswa,id_bidang,keahlian',
            'mahasiswa.keahlian_mahasiswa.bidang:id_bidang,nama',
            )
            ->where('id_akun', Auth::user()->id_akun)
            ->first();
        $bidang = BidangModel::get();
        return view('tes.keahlian', ['pengalaman' => $akun->mahasiswa->pengalaman, 'bidang' => $bidang, 'keahlian' => $akun->mahasiswa->keahlian_mahasiswa]);
        // return response()->json($akun);
    }

    public function postKeahlian(Request $request){

        $panjang = count($request->id_keahlian);

        [$removed_id, $lates_id] = $this->deleteKeahlian($request);
        $updated_id = $this->updateKeahlian($request, $removed_id, $lates_id, $panjang);
        $this->insertKeahlian($request, $panjang);

        // dd($request->all());
        return response()->json($request);
    }

    private function deleteKeahlian($request){
        $current_id = array_map('intval', $request->id_keahlian);;

        $lates_id = $akun = AkunModel::with(
            'mahasiswa:id_mahasiswa,id_akun',
            'mahasiswa.keahlian_mahasiswa:id_keahlian_mahasiswa,id_mahasiswa'
            )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level']);
        $lates_id = $lates_id->mahasiswa->keahlian_mahasiswa->pluck('id_keahlian_mahasiswa')->toArray();

        $removed_id = array_values(array_diff($lates_id, $current_id));
        
        $keahlian = KeahlianMahasiswaModel::destroy($removed_id);


        return [$removed_id, $lates_id];
    }

    private function updateKeahlian($request, $removed_id, $lates_id, $panjang){
        $updated_id = array_values(array_diff( $lates_id, $removed_id));

        for ($i = 0; $i < $panjang; $i++) {
            KeahlianMahasiswaModel::where('id_keahlian_mahasiswa', $request->id_keahlian[$i])
            ->update([
                'id_bidang' => $request->id_bidang[$i],
                'keahlian' => $request->keahlian[$i]
            ]);
        }

        return $updated_id;
    }

    private function insertKeahlian($request, $panjang){
        $id_mahasiswa = AkunModel::with('mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        for ($i = $panjang; $i < count($request->id_bidang); $i++) {
            KeahlianMahasiswaModel::insert([
                'id_mahasiswa' => $id_mahasiswa,
                'id_bidang' => $request->id_bidang[$i],
                'keahlian' => $request->keahlian[$i]
            ]);
        }
    }

    public function postPengalaman(Request $request){
        $panjang = count($request->id_pengalaman);

        [$removed_id, $lates_id] = $this->deletePengalaman($request);
        $updated_id = $this->updatePengalaman($request, $removed_id, $lates_id, $panjang);
        $this->insertPengalaman($request, $panjang);

        dd($request->all());
        // return response()->json($removed_id);
    }

    private function deletePengalaman($request){
        $current_id = array_map('intval', $request->id_pengalaman);;

        $lates_id = $akun = AkunModel::with(
            'mahasiswa:id_mahasiswa,id_akun',
            'mahasiswa.pengalaman:id_pengalaman,id_mahasiswa'
            )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level']);
        $lates_id = $lates_id->mahasiswa->pengalaman->pluck('id_pengalaman')->toArray();

        $removed_id = array_values(array_diff($lates_id, $current_id));
        
        $keahlian = PengalamanModel::destroy($removed_id);


        return [$removed_id, $lates_id];
    }

    private function updatePengalaman($request, $removed_id, $lates_id, $panjang){
        $updated_id = array_values(array_diff( $lates_id, $removed_id));

        for ($i = 0; $i < $panjang; $i++) {
            PengalamanModel::where('id_pengalaman', $request->id_pengalaman[$i])
            ->update(['deskripsi' => $request->deskripsi[$i]]);
        }

        return $updated_id;
    }

    private function insertPengalaman($request, $panjang){
        $id_mahasiswa = AkunModel::with('mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        for ($i = $panjang; $i < count($request->deskripsi); $i++) {
            PengalamanModel::insert([
                'id_mahasiswa' => $id_mahasiswa,
                'deskripsi' => $request->deskripsi[$i]
            ]);
        }
    }

    public function getAkun(){

    }

    public function getKeahlian(){

    }

    public function getDokumen(){
        
    }
}
