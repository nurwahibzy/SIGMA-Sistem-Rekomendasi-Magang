<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\PreferensiLokasiMahasiswaModel;
use App\Models\PreferensiPerusahaanMahasiswaModel;
use App\Models\ProdiModel;
use Date;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Storage;
use Str;
use Validator;

class MahasiswaController extends Controller
{
    public function getMahasiswa()
    {
        $mahasiswa = MahasiswaModel::with('akun')
            ->get();

        return view('admin.mahasiswa.index', ['mahasiswa' => $mahasiswa]);
    }

    public function getAddMahasiswa()
    {
        $prodi = ProdiModel::get();
        return view('admin.mahasiswa.tambah', ['prodi' => $prodi]);
        // return response()->json($prodi);
    }

    public function getDetailMahasiswa($id_akun)
    {
        $mahasiswa = MahasiswaModel::with('akun')
            ->whereHas('akun', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->first();

        return view('admin.mahasiswa.detail', ['mahasiswa' => $mahasiswa]);
        // return response()->json($mahasiswa);
    }

    public function getEditMahasiswa($id_akun)
    {
        $mahasiswa = MahasiswaModel::with('akun')
            ->whereHas('akun', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->first();
        $prodi = ProdiModel::get();
        return view('admin.mahasiswa.edit', ['mahasiswa' => $mahasiswa, 'prodi' => $prodi]);
    }

    public function postMahasiswa(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request) {
                        $validator = Validator::make($request->all(), [
                            'id_user' => 'required|digits_between:1,20',
                            'id_prodi' => 'required|exists:prodi,id_prodi',
                            'nama' => 'required|string|max:100',
                            'alamat' => 'required|string',
                            'telepon' => 'required|digits_between:1,30',
                            'tanggal_lahir' => 'required|date',
                            'email' => 'required|email|max:100',
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                        ]);

                        if ($validator->fails()) {
                            return false;
                        }

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
                        $provinsi = 'Jawa Timur';
                        $daerah = 'Kota Malang';
                        $latitude = '-7.9771308';
                        $longitude = '112.6340265';

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

                        $mahasiswa = MahasiswaModel::create([
                            'id_akun' => $akun->id_akun,
                            'id_prodi' => $id_prodi,
                            'nama' => $nama,
                            'alamat' => $alamat,
                            'telepon' => $telepon,
                            'tanggal_lahir' => $tanggal_lahir,
                            'email' => $email
                        ]);

                        PreferensiLokasiMahasiswaModel::create([
                            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                            'provinsi' => $provinsi,
                            'daerah' => $daerah,
                            'latitude' => $latitude,
                            'longitude' => $longitude
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

    public function putMahasiswa(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request, $id_akun) {
                        $validator = Validator::make($request->all(), [
                            'id_user' => 'required|digits_between:1,20',
                            'status' => 'required|in:aktif,nonaktif',
                            'id_prodi' => 'required|exists:prodi,id_prodi',
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

                        $id_user = $request->input('id_user');
                        $status = $request->input('status');
                        $id_prodi = $request->input('id_prodi');
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

                        MahasiswaModel::with('akun:id_akun')
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

    public function deleteMahasiswa(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_akun) {

                        $akun = AkunModel::with('mahasiswa', 'mahasiswa.dokumen')
                            ->where('id_akun', $id_akun)->first();

                            foreach ($akun->mahasiswa->dokumen as $dokumen) {
                                $file_path = $dokumen->file_path;

                                if (Storage::disk('public')->exists("dokumen/$file_path")) {
                                    Storage::disk('public')->delete("dokumen/$file_path");
                                }
                            }

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

    public function postMahasiswaExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls',
            'id_prodi' => 'nullable|exists:prodi,id_prodi',
        ]);

        $file = $request->file('file_excel');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);

        DB::transaction(function () use ($data, $request) {
            foreach ($data as $index => $row) {
                if ($index === 1) {
                    continue;
                }

                $nim = trim($row['A']);
                $nama = trim($row['B']);
                $alamat = trim($row['C']);
                $telepon = trim($row['D']);
                $tanggal_lahir = trim($row['E']);
                $email = trim($row['F']);

                if (is_numeric($tanggal_lahir)) {
                    $tanggal_lahir = Date::excelToDateTimeObject($tanggal_lahir)->format('Y-m-d');
                }

                $akun = AkunModel::create([
                    'id_user' => $nim,
                    'id_level' => 2,
                    'password' => bcrypt('defaultPassword123'),
                    'status' => 'aktif',
                    'foto_path' => "$nim.jpg",
                ]);

                MahasiswaModel::create([
                    'id_mahasiswa' => $nim,
                    'id_akun' => $akun->id_akun,
                    'id_prodi' => 1,
                    'nama' => $nama,
                    'alamat' => $alamat,
                    'telepon' => $telepon,
                    'tanggal_lahir' => $tanggal_lahir,
                    'email' => $email,
                ]);
            }
        });

        // return redirect()->back()
        //     ->with('success', 'Semua data dari Excel berhasil diimport.');

        return response()->json($data);
    }

}
