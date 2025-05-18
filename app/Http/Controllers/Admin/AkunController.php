<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\AkunModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Storage;

class AkunController extends Controller
{
    private function idAdmin()
    {
        $id_admin = AkunModel::with(relations: 'admin:id_admin,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->admin
            ->id_admin;
        return $id_admin;
    }

    private function allDataProfil()
    {
        $akun = AkunModel::with(
            'admin:id_admin,id_akun,nama,alamat,telepon,tanggal_lahir,email'
        )
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_user']);
        return $akun;
    }
    public function getProfil()
    {
        $akun = $this->allDataProfil();
        return response()->json($akun);
    }

    public function getEditProfil()
    {
        $akun = $this->allDataProfil();
        return response()->json($akun);
    }

    public function putAkun(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(function () use ($request) {
                    $id_admin = $this->idAdmin();
                    $nama = $request->input('nama');
                    $alamat = $request->input('alamat');
                    $telepon = $request->input('telepon');
                    $tanggal_lahir = $request->input('tanggal_lahir');
                    $email = $request->input('email');

                    if ($request->filled('password')) {
                        $password = $request->input('password');
                        AkunModel::with('admin:id_akun,id_admin')
                            ->whereHas('admin', function ($query) use ($id_admin) {
                                $query->where('id_admin', $id_admin);
                            })
                            ->update([
                                'password' => Hash::make($password)
                            ]);
                    }

                    AdminModel::where('id_admin', $id_admin)
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
        $id_admin = $this->idAdmin();
        $foto_path = AdminModel::with('akun:id_akun,foto_path')
            ->where('id_admin', $id_admin)
            ->first(['id_akun']);
        return response()->json($foto_path);
    }

    public function putFoto(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request) {
                        $id_admin = $this->idAdmin();
                        $data = AdminModel::with('akun:id_akun,id_user,foto_path')
                            ->where('id_admin', $id_admin)
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


    public function postUserAdmin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request) {

                        $id_level = 1;
                        $id_user = $request->input('id_user');
                        $password = Hash::make('password');
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $akun = AkunModel::create([
                            'id_level' => $id_level,
                            'id_user' => $id_user,
                            'password' => $password,
                            'status' => $status,
                            'foto_path' => $foto_path
                        ]);

                        AdminModel::insert([
                            'id_akun' => $akun->id_akun,
                            'nama' => $nama,
                            'alamat' => $alamat,
                            'telepon' => $telepon,
                            'tanggal_lahir' => $tanggal_lahir,
                            'email' => $email
                        ]);
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambah user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putUserAdmin(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_akun) {

                        $id_user = $request->input('id_user');
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $data = AkunModel::where('id_akun', $id_akun)->first();
                        if ($data->id_user != $id_user) {
                            $this->renameFileOnly($data, $id_user);
                        }

                        if ($request->filled('password')) {
                            $password = $request->input('password');
                            $akun = AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'password' => Hash::make($password),
                                    'status' => $status,
                                    'foto_path' => $foto_path
                                ]);
                        } else {
                            $akun = AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'status' => $status,
                                    'foto_path' => $foto_path
                                ]);
                        }

                        AdminModel::with('akun:id_akun')
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
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function postUserMahasiswa(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request) {

                        $id_level = 2;
                        $id_user = $request->input('id_user');
                        $password = Hash::make('password');
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $id_prodi = $request->input('id_prodi');
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $akun = AkunModel::create([
                            'id_level' => $id_level,
                            'id_user' => $id_user,
                            'password' => $password,
                            'status' => $status,
                            'foto_path' => $foto_path
                        ]);

                        MahasiswaModel::insert([
                            'id_akun' => $akun->id_akun,
                            'id_prodi' => $id_prodi,
                            'nama' => $nama,
                            'alamat' => $alamat,
                            'telepon' => $telepon,
                            'tanggal_lahir' => $tanggal_lahir,
                            'email' => $email
                        ]);
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambah user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putUserMahasiswa(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_akun) {

                        $id_user = $request->input('id_user');
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $id_prodi = $request->input('id_prodi');
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $data = AkunModel::where('id_akun', $id_akun)->first();
                        if ($data->id_user != $id_user) {
                            $this->renameFileOnly($data, $id_user);
                        }

                        if ($request->filled('password')) {
                            $password = $request->input('password');
                            $akun = AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'password' => Hash::make($password),
                                    'status' => $status,
                                    'foto_path' => $foto_path
                                ]);
                        } else {
                            $akun = AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'status' => $status,
                                    'foto_path' => $foto_path
                                ]);
                        }

                        DosenModel::with('akun:id_akun')
                            ->whereHas('akun', function ($query) use ($id_akun) {
                                $query->where('id_akun', $id_akun);
                            })
                            ->update([
                                'id_akun' => $id_akun,
                                'id_prodi' => $id_prodi,
                                'nama' => $nama,
                                'alamat' => $alamat,
                                'telepon' => $telepon,
                                'tanggal_lahir' => $tanggal_lahir,
                                'email' => $email
                            ]);
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function postUserDosen(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request) {

                        $id_level = 3;
                        $id_user = $request->input('id_user');
                        $password = Hash::make('password');
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $akun = AkunModel::create([
                            'id_level' => $id_level,
                            'id_user' => $id_user,
                            'password' => $password,
                            'status' => $status,
                            'foto_path' => $foto_path
                        ]);

                        DosenModel::insert([
                            'id_akun' => $akun->id_akun,
                            'nama' => $nama,
                            'alamat' => $alamat,
                            'telepon' => $telepon,
                            'tanggal_lahir' => $tanggal_lahir,
                            'email' => $email
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

    public function putUserDosen(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_akun) {

                        $id_user = $request->input('id_user');
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $data = AkunModel::where('id_akun', $id_akun)->first();
                        if ($data->id_user != $id_user) {
                            $this->renameFileOnly($data, $id_user);
                        }

                        if ($request->filled('password')) {
                            $password = $request->input('password');
                            $akun = AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'password' => Hash::make($password),
                                    'status' => $status,
                                    'foto_path' => $foto_path
                                ]);
                        } else {
                            $akun = AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'status' => $status,
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
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    private function renameFileOnly($data, $id_user)
    {
        $lama = $data->foto_path;
        $extension = pathinfo($lama, PATHINFO_EXTENSION);
        $file_path_baru = $id_user . '.' . $extension;

        if (Storage::disk('public')->exists("dokumen/profil/akun/$lama")) {
            Storage::disk('public')->move("dokumen/profil/akun/$lama", "dokumen/profil/akun/$file_path_baru");
        }
    }

    public function deleteUser(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                AkunModel::where('id_akun', $id_akun)
                    ->delete();

                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menghapus lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    // public function postUserAdminExcel(Request $request){
    //      $file = $request->file('file_barang'); // ambil file dari request
    //         $reader = IOFactory::createReader('Xlsx'); // load reader file excel
    //         $reader->setReadDataOnly(true); // hanya membaca data
    //         $spreadsheet = $reader->load($file->getRealPath()); // load file excel
    //         $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
    //         $data = $sheet->toArray(null, false, true, true); // ambil data excel
    //         $insert = [];
    //         if (count($data) > 1) { // jika data lebih dari 1 baris
    //             foreach ($data as $baris => $value) {
    //                 if ($baris > 1) { // baris ke 1 adalah header, maka lewati
    //                     $insert[] = [
    //                         'kategori_id' => $value['A'],
    //                         'barang_kode' => $value['B'],
    //                         'barang_nama' => $value['C'],
    //                         'harga_beli' => $value['D'],
    //                         'harga_jual' => $value['E'],
    //                         'created_at' => now(),
    //                     ];
    //                 }
    //             }
                // if (count($insert) > 0) {
                //     // insert data ke database, jika data sudah ada, maka diabaikan
                //     BarangModel::insertOrIgnore($insert);
                // }
    //         }
    // }
}
