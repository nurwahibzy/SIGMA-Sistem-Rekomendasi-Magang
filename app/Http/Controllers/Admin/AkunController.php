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
