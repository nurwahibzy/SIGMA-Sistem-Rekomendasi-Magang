<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\AkunModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\PerusahaanModel;
use App\Models\PreferensiLokasiMahasiswaModel;
use App\Models\PreferensiPerusahaanMahasiswaModel;
use App\Models\ProdiModel;
// use Date;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Storage;
use Str;
use Validator;

class MahasiswaController extends Controller
{
    public function getMahasiswa()
    {
        $mahasiswa = MahasiswaModel::with('akun')
            ->get();
        $aktif = MahasiswaModel::with('akun')
            ->whereHas('akun', function ($query) {
                $query->where('status', 'aktif');
            })
            ->count();
        // $aktif = DosenModel::withCount([
        //     'magang' => function ($query) {
        //         $query->where('status', 'diterima');
        //     }
        // ])
        //     ->orderByDesc('magang_count')
        //     ->first();

        $nonaktif = MahasiswaModel::with('akun')
            ->whereHas('akun', function ($query) {
                $query->where('status', 'nonaktif');
            })
            ->count();

        return view('admin.mahasiswa.index', ['mahasiswa' => $mahasiswa, 'aktif' => $aktif, 'nonaktif' => $nonaktif]);
        // return response()->json($aktif);
    }

    public function getAddMahasiswa()
    {
        $prodi = ProdiModel::where('status', 'aktif')->get();
        return view('admin.mahasiswa.tambah', ['prodi' => $prodi]);
        // return response()->json($prodi);
    }

    public function getAddExcelMahasiswa()
    {
        $prodi = ProdiModel::where('status', 'aktif')->get();
        return view('admin.mahasiswa.tambah-excel', ['prodi' => $prodi]);
    }

    public function getUnduhExcelMahasiswa()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Alamat');
        $sheet->setCellValue('D1', 'Telepon');
        $sheet->setCellValue('E1', 'Tanggal Lahir');
        $sheet->setCellValue('F1', 'Gender');
        $sheet->setCellValue('G1', 'Email');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $sheet->setTitle('Format Mahasiswa');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        $filename = 'format_mahasiswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
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
        $prodi = ProdiModel::where('status', 'aktif')->get();
        return view('admin.mahasiswa.edit', ['mahasiswa' => $mahasiswa, 'prodi' => $prodi]);
    }

    private function checkTelepon($telepon, $id_akun = false)
    {
        if ($id_akun) {
            $amount = PerusahaanModel::where('telepon', $telepon)
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = AdminModel::where('telepon', $telepon)
                ->whereHas('akun', function ($query) use ($id_akun) {
                    $query->where('id_akun', '!=', $id_akun);
                })
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = MahasiswaModel::where('telepon', $telepon)
                ->whereHas('akun', function ($query) use ($id_akun) {
                    $query->where('id_akun', '!=', $id_akun);
                })
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = DosenModel::where('telepon', $telepon)
                ->whereHas('akun', function ($query) use ($id_akun) {
                    $query->where('id_akun', '!=', $id_akun);
                })
                ->count();
            if ($amount != 0) {
                return true;
            }
        } else {

            $amount = PerusahaanModel::where('telepon', $telepon)->count();
            if ($amount != 0) {
                return true;
            }
            $amount = AdminModel::where('telepon', $telepon)
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = MahasiswaModel::where('telepon', $telepon)
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = DosenModel::where('telepon', $telepon)
                ->count();
            if ($amount != 0) {
                return true;
            }
        }
        return false;
    }

    private function checkEmail($email, $id_akun = false)
    {
        if ($id_akun) {
            $amount = AdminModel::where('email', $email)
                ->whereHas('akun', function ($query) use ($id_akun) {
                    $query->where('id_akun', '!=', $id_akun);
                })
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = MahasiswaModel::where('email', $email)
                ->whereHas('akun', function ($query) use ($id_akun) {
                    $query->where('id_akun', '!=', $id_akun);
                })
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = DosenModel::where('email', $email)
                ->whereHas('akun', function ($query) use ($id_akun) {
                    $query->where('id_akun', '!=', $id_akun);
                })
                ->count();
            if ($amount != 0) {
                return true;
            }
        } else {
            $amount = AdminModel::where('email', $email)
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = MahasiswaModel::where('email', $email)
                ->count();
            if ($amount != 0) {
                return true;
            }
            $amount = DosenModel::where('email', $email)
                ->count();
            if ($amount != 0) {
                return true;
            }
        }
        return false;
    }

    private function checkId($id_user, $id_akun = false)
    {
        if ($id_akun) {
            $amount = AkunModel::where('id_user', $id_user)
                ->where('id_akun', '!=', $id_akun)
                ->count();
            if ($amount != 0) {
                return true;
            }
        } else {
            $amount = AkunModel::where('id_user', $id_user)
                ->count();
            if ($amount != 0) {
                return true;
            }
        }
        return false;
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
                            'gender' => 'required|in:l,p',
                            'email' => 'required|email|max:100',
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                        ]);

                        if ($validator->fails()) {
                            return ['success' => false, 'message' => 'Data Tidak Valid'];
                        }

                        $id_level = 2;
                        $id_user = $request->input('id_user');
                        $password = 'password';
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $id_prodi = $request->input('id_prodi');
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $gender = $request->input('gender');
                        $email = $request->input('email');
                        $provinsi = 'Jawa Timur';
                        $daerah = 'Kota Malang';
                        $latitude = '-7.9771308';
                        $longitude = '112.6340265';

                        if ($this->checkId($id_user)) {
                            return ['success' => false, 'message' => 'NIM Tidak Boleh Sama!!!'];
                        }

                        if ($this->checkEmail($email)) {
                            return ['success' => false, 'message' => 'Email Tidak Boleh Sama!!!'];
                        }

                        if ($this->checkTelepon($telepon)) {
                            return ['success' => false, 'message' => 'Nomor Telepon Tidak Boleh Sama!!!'];
                        }

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
                            'gender' => $gender,
                            'email' => $email
                        ]);

                        PreferensiLokasiMahasiswaModel::create([
                            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                            'provinsi' => $provinsi,
                            'daerah' => $daerah,
                            'latitude' => $latitude,
                            'longitude' => $longitude
                        ]);
                        return ['success' => true];
                    }
                );
                return response()->json($results);
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
                            'gender' => 'required|in:l,p',
                            'email' => 'required|email|max:100',
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                            'password' => 'nullable|string|min:6|max:255'
                        ]);

                        if ($validator->fails()) {
                            return ['success' => false, 'message' => 'Data Tidak Valid'];
                        }

                        $id_user = $request->input('id_user');
                        $status = $request->input('status');
                        $id_prodi = $request->input('id_prodi');
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $gender = $request->input('gender');
                        $email = $request->input('email');

                        if ($this->checkId($id_user, $id_akun)) {
                            return ['success' => false, 'message' => 'NIM Tidak Boleh Sama!!!'];
                        }

                        if ($this->checkEmail($email, $id_akun)) {
                            return ['success' => false, 'message' => 'Email Tidak Boleh Sama!!!'];
                        }

                        if ($this->checkTelepon($telepon, $id_akun)) {
                            return ['success' => false, 'message' => 'Nomor Telepon Tidak Boleh Sama!!!'];
                        }

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
                                'gender' => $gender,
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

                        $akun = AkunModel::with('mahasiswa', 'mahasiswa.dokumen', 'mahasiswa.magang', 'mahasiswa.magang.aktivitas_magang')
                            ->where('id_akun', $id_akun)->first();

                        foreach ($akun->mahasiswa->magang as $magang) {
                            foreach ($magang->aktivitas_magang as $aktivitas) {
                                $foto_path = $aktivitas->foto_path;

                                if (Storage::disk('public')->exists("aktivitas/$foto_path")) {
                                    Storage::disk('public')->delete("aktivitas/$foto_path");
                                }
                            }
                        }
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
                Log::error("Gagal menghapus user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function postAddExcelMahasiswa(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {

                $request->validate([
                    'file_excel' => 'required|mimes:xlsx,xls',
                    'id_prodi' => 'nullable|exists:prodi,id_prodi',
                ]);
                set_time_limit(0);
                ini_set('memory_limit', '-1');

                $file = $request->file('file_excel');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);

                DB::transaction(function () use ($data, $request) {
                    $id_prodi = $request->input('id_prodi');
                    $provinsi = 'Jawa Timur';
                    $daerah = 'Kota Malang';
                    $latitude = '-7.9771308';
                    $longitude = '112.6340265';

                    foreach ($data as $index => $row) {
                        if ($index === 1) {
                            continue;
                        }

                        $id_user = trim($row['A']);
                        $nama = trim($row['B']);
                        $alamat = trim($row['C']);
                        $telepon = trim($row['D']);
                        $tanggal_lahir = trim($row['E']);
                        $gender = trim($row['F']);
                        $email = trim($row['G']);

                        if (is_numeric($tanggal_lahir)) {
                            $tanggal_lahir = Date::excelToDateTimeObject($tanggal_lahir)->format('Y-m-d');
                        }

                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            throw ValidationException::withMessages([
                                'email' => "Email tidak valid pada NIM: $id_user ($email)",
                            ]);
                        }

                        if (!preg_match('/^\d{8,}$/', $telepon)) {
                            throw ValidationException::withMessages([
                                'telepon' => "Nomor telepon tidak valid pada NIM: $id_user ($telepon)",
                            ]);
                        }

                        if ($this->checkId($id_user)) {
                            // return ['success' => false, 'message' => 'NIM Tidak Boleh Sama!!!'];
                            throw ValidationException::withMessages([
                                'NIM' => "NIM tidak boleh sama",
                            ]);
                        }

                        if ($this->checkEmail($email)) {
                            // return ['success' => false, 'message' => 'Email Tidak Boleh Sama!!!'];
                            throw ValidationException::withMessages([
                                'Email' => "Email tidak boleh sama",
                            ]);
                        }

                        if ($this->checkTelepon($telepon)) {
                            // return ['success' => false, 'message' => 'Nomor Telepon Tidak Boleh Sama!!!'];
                            throw ValidationException::withMessages([
                                'Telepon' => "Telepon tidak boleh sama",
                            ]);
                        }

                        $akun = AkunModel::create([
                            'id_user' => $id_user,
                            'id_level' => 2,
                            'password' => 'password',
                            'status' => 'aktif',
                            'foto_path' => "$id_user.jpg",
                        ]);

                        $mahasiswa = MahasiswaModel::create([
                            'id_akun' => $akun->id_akun,
                            'id_prodi' => $id_prodi,
                            'nama' => $nama,
                            'alamat' => $alamat,
                            'telepon' => $telepon,
                            'tanggal_lahir' => $tanggal_lahir,
                            'gender' => $gender,
                            'email' => $email,
                        ]);

                        PreferensiLokasiMahasiswaModel::create([
                            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                            'provinsi' => $provinsi,
                            'daerah' => $daerah,
                            'latitude' => $latitude,
                            'longitude' => $longitude
                        ]);
                    }
                });
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan user: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}
