<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\DosenModel;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Storage;
use Validator;

class DosenController extends Controller
{
    public function getDosen()
    {
        $dosen = DosenModel::with('akun')
            ->get();

        return view('admin.dosen.index', ['dosen' => $dosen]);
    }

    public function getAddDosen()
    {
        return view('admin.dosen.tambah');
    }

    public function getDetailDosen($id_akun)
    {
        $dosen = DosenModel::with('akun')
            ->whereHas('akun', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->first();

        return view('admin.dosen.detail', ['dosen' => $dosen]);
    }

    public function getEditDosen($id_akun)
    {
        $dosen = DosenModel::with('akun')
            ->whereHas('akun', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->first();
        return view('admin.dosen.edit', ['dosen' => $dosen]);
    }

    public function postDosen(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request) {

                        $validator = Validator::make($request->all(), [
                            'id_user' => 'required|digits_between:1,20',
                            'nama' => 'required|string|max:255',
                            'alamat' => 'required|string',
                            'telepon' => 'required|digits_between:1,15',
                            'tanggal_lahir' => 'required|date',
                            'email' => 'required|email|max:255',
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                        ]);

                        if ($validator->fails()) {
                            return false;
                        }

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

                        if ($request->hasFile('file')) {
                            $foto_path = $this->handleFileUpload($request, $id_user, $foto_path);
                        }

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

                        return true;
                    }
                );
                return response()->json(['success' => $results]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambah user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putDosen(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request, $id_akun) {

                        $validator = Validator::make($request->all(), [
                            'id_user' => 'required|digits_between:1,20',
                            'status' => 'required|in:aktif,nonaktif',
                            'nama' => 'required|string|max:255',
                            'alamat' => 'required|string',
                            'telepon' => 'required|digits_between:1,15',
                            'tanggal_lahir' => 'required|date',
                            'email' => 'required|email|max:255',
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                            'password' => 'nullable|string|min:6'
                        ]);

                        if ($validator->fails()) {
                            return false;
                        }

                        $id_user = $request->input('id_user');
                        $status = $request->input('status');
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $email = $request->input('email');

                        $data = AkunModel::where('id_akun', $id_akun)->first();

                        $foto_path = $data->foto_path;

                        if ($request->hasFile('file')) {
                            $foto_path = $this->handleFileUpload($request, $id_user, $foto_path);
                        } else if ($data->id_user != $id_user) {
                            $foto_path = $this->renameFileOnly($foto_path, $id_user);
                        }

                        if ($request->filled('password')) {
                            $password = $request->input('password');
                            AkunModel::where('id_akun', $id_akun)
                                ->update([
                                    'id_user' => $id_user,
                                    'password' => Hash::make($password),
                                    'status' => $status,
                                    'foto_path' => $foto_path
                                ]);
                        } else {
                            AkunModel::where('id_akun', $id_akun)
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

    private function renameFileOnly($foto_path, $id_user)
    {
        $extension = pathinfo($foto_path, PATHINFO_EXTENSION);
        $file_path_baru = $id_user . '.' . $extension;

        if (Storage::disk('public')->exists("profil/akun/$foto_path")) {
            Storage::disk('public')->move("profil/akun/$foto_path", "profil/akun/$file_path_baru");
        }

        return $file_path_baru;
    }

    public function deleteDosen(Request $request, $id_akun)
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