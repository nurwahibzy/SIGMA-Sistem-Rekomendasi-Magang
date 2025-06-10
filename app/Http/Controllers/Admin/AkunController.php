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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Storage;
use Validator;

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
        // $akun = $this->allDataProfil();
        // return response()->json($akun);
        return view('admin.profil');
    }

    public function getEditProfil()
    {
        // $akun = $this->allDataProfil();
        // return response()->json($akun);
        return view('admin.edit-profil');
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

                        if ($request->hasFile('file')) {
                            $file = $request->file('file');
                            $max_size = 2 * 1024 * 1024;
    
                            if ($file->getSize() > $max_size) {
                                return ['success' => false, 'message' => 'Ukuran file tidak boleh lebih dari 2MB.'];
                            }
                        }

                        if ($validator->fails()) {
                            return ['success' => false, 'message' => 'Data Tidak Valid'];
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
                            return ['success' => true];
                    }
                );
                return response()->json($results);
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

    public function tes(){
        return view('tes.excelForm');
    }
}
