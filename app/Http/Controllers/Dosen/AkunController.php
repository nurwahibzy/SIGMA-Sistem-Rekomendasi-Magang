<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\DosenModel;
use App\Models\MagangModel;
use Illuminate\Support\Facades\Validator;

use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Storage;

class AkunController extends Controller
{
    private function idDosen()
    {
        $id_dosen = AkunModel::with(relations: 'dosen:id_dosen,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->dosen
            ->id_dosen;
        return $id_dosen;
    }

    private function allDataProfil(){
        $akun = AkunModel::with(
            'dosen:id_dosen,id_akun,nama,alamat,telepon,tanggal_lahir,email',
            'dosen.keahlian_dosen:id_keahlian_dosen,id_dosen,id_bidang,keahlian',
            'dosen.keahlian_dosen.bidang:id_bidang,nama',
        )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_user']);
        return $akun;
    }
    public function getProfil(){
        $akun = $this->allDataProfil();
        return view('dosen.profil');
    }

    public function getEditProfil(){
        $akun = $this->allDataProfil();
        return view('dosen.edit-profil');
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
                        //  else if ($data->id_user != $id_user) {
                        //     $foto_path = $this->renameFileOnly($foto_path, $id_user);
                        // }

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

                        DosenModel::with('akun:id_akun')
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
                return response()->json(['success' => $request->all()]);
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
        $id_dosen = $this->idDosen();
        $foto_path = DosenModel::with('akun:id_akun,foto_path')
            ->where('id_dosen', $id_dosen)
            ->first(['id_akun']);
        return response()->json($foto_path);
    }

    public function putFoto(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request) {
                        $id_dosen = $this->idDosen();
                        $data = DosenModel::with('akun:id_akun,id_user,foto_path')
                            ->where('id_dosen', $id_dosen)
                            ->first(['id_akun']);

                        $file = $request->file('file');
                        $filename = $data->akun->id_user . '.' . $file->getClientOriginalExtension();
                        Storage::disk('public')->delete("profil/akun/{$data->akun->foto_path}");
                        $file->storeAs('public/profil/akun', $filename);
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

    public function getProfilMahasiswa($id_magang){
        // return response()->json('a');
        $id_dosen = $this->idDosen();
        $akun = MagangModel::with(
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
        ->where('id_dosen', $id_dosen)
        ->where('id_magang', $id_magang)
        ->first();

        return response()->json($akun);
    }
}
