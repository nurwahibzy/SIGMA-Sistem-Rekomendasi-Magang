<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\AkunModel;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Storage;

class AdminController extends Controller
{
    public function getAdmin()
    {
        $admin = AdminModel::with('akun')
            ->get();

        return view('admin.admin.index', ['admin' => $admin]);
    }

    public function getAddAdmin()
    {
        return view('admin.admin.tambah');
    }

    public function getDetailAdmin($id_akun)
    {
        $admin = AdminModel::with('akun')
            ->whereHas('akun', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->first();

        return view('admin.admin.detail', ['admin' => $admin]);
    }

    public function getEditAdmin($id_admin)
    {

    }

    public function postAdmin(Request $request)
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

    public function putAdmin(Request $request, $id_akun)
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

    private function renameFileOnly($data, $id_user)
    {
        $lama = $data->foto_path;
        $extension = pathinfo($lama, PATHINFO_EXTENSION);
        $file_path_baru = $id_user . '.' . $extension;

        if (Storage::disk('public')->exists("profil/akun/$lama")) {
            Storage::disk('public')->move("profil/akun/$lama", "profil/akun/$file_path_baru");
        }
    }

    public function deleteAdmin(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_akun) {

                        $akun = AkunModel::where('id_akun', $id_akun)
                            ->first(['foto_path']);

                        $foto_path = $akun->foto_path;

                        if (Storage::disk('public')->exists("profil/akun/$foto_path")) {
                            Storage::disk('public')->delete("profil/akun/$foto_path");
                        }

                        AkunModel::where('id_akun', $id_akun)
                            ->delete();
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menghapus lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

}
