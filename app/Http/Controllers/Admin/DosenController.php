<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\AkunModel;
use App\Models\DosenModel;
use App\Models\KeahlianDosenModel;
use App\Models\MagangModel;
use App\Models\MahasiswaModel;
use App\Models\PerusahaanModel;
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

        $aktif = DosenModel::with('akun')
            ->whereHas('akun', function ($query) {
                $query->where('status', 'aktif');
            })
            ->count();
        $topDosen = DosenModel::with('akun')
            ->withCount([
                'magang' => function ($query) {
                    $query->where('status', 'diterima');
                }
            ])->whereHas('akun', function ($query) {
                $query->where('status', 'aktif');
            })
            ->orderByDesc('magang_count')
            ->first();

        $nonaktif = DosenModel::with('akun')
            ->whereHas('akun', function ($query) {
                $query->where('status', 'nonaktif');
            })
            ->count();

        // return view('admin.mahasiswa.index', ['mahasiswa' => $mahasiswa, 'amountMahasiswa' => $amountMahasiswa, 'aktif' => $aktif, 'nonaktif' => $nonaktif]);
        // return response()->json($aktif);

        return view('admin.dosen.index', ['dosen' => $dosen, 'aktif' => $aktif, 'nonaktif' => $nonaktif, 'topDosen' => $topDosen]);
        // return response()->json($topDosen);
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

        $keahlian = KeahlianDosenModel::with('dosen', 'dosen.akun', 'bidang')
            ->whereHas('dosen.akun', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->get();

        $amountMahasiswaDiterima = MagangModel::with('dosen')
            ->whereHas('dosen', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->where('status', 'diterima')
            ->count();

        $amountMahasiswaLulus = MagangModel::with('dosen')
            ->whereHas('dosen', function ($query) use ($id_akun) {
                $query->where('id_akun', $id_akun);
            })
            ->where('status', 'lulus')
            ->count();

        $perusahaan = MagangModel::select(
            'perusahaan.id_perusahaan as id_perusahaan',
            'perusahaan.nama as nama',
            'perusahaan.foto_path as foto_path',
            DB::raw('COUNT(magang.id_magang) as total')
        )
            ->join('dosen', 'magang.id_dosen', '=', 'dosen.id_dosen')
            ->join('periode_magang', 'magang.id_periode', '=', 'periode_magang.id_periode')
            ->join('lowongan_magang', 'periode_magang.id_lowongan', '=', 'lowongan_magang.id_lowongan')
            ->join('perusahaan', 'lowongan_magang.id_perusahaan', '=', 'perusahaan.id_perusahaan')
            ->where('dosen.id_akun', $id_akun)
            ->groupBy('perusahaan.id_perusahaan')
            ->orderByDesc('total')
            ->first();

        // return response()->json(empty($perusahaan));
        return view('admin.dosen.detail', ['dosen' => $dosen, 'keahlian' => $keahlian, 'amountMahasiswaDiterima' => $amountMahasiswaDiterima, 'amountMahasiswaLulus' => $amountMahasiswaLulus, 'perusahaan' => $perusahaan]);
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

    public function postDosen(Request $request)
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
                            'gender' => 'required|in:l,p',
                            'email' => 'required|email|max:100',
                            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
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

                        $id_level = 3;
                        $id_user = $request->input('id_user');
                        $password = 'password';
                        $status = 'aktif';
                        $foto_path = "$id_user.jpg";
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $gender = $request->input('gender');
                        $email = $request->input('email');

                        if ($this->checkId($id_user)) {
                            return ['success' => false, 'message' => 'NIP Tidak Boleh Sama!!!'];
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

                        DosenModel::insert([
                            'id_akun' => $akun->id_akun,
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
                            'nama' => 'required|string|max:100',
                            'alamat' => 'required|string',
                            'telepon' => 'required|digits_between:1,30',
                            'tanggal_lahir' => 'required|date',
                            'gender' => 'required|in:l,p',
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

                        $id_user = $request->input('id_user');
                        $status = $request->input('status');
                        $nama = $request->input('nama');
                        $alamat = $request->input('alamat');
                        $telepon = $request->input('telepon');
                        $tanggal_lahir = $request->input('tanggal_lahir');
                        $gender = $request->input('gender');
                        $email = $request->input('email');

                        if ($this->checkId($id_user, $id_akun)) {
                            return ['success' => false, 'message' => 'NIP Tidak Boleh Sama!!!'];
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

    public function deleteDosen(Request $request, $id_akun)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $results = DB::transaction(
                    function () use ($request, $id_akun) {

                        $akun = AkunModel::with('dosen')
                            ->where('id_akun', $id_akun)
                            ->first();

                        $magang = MagangModel::where('id_dosen', $akun->dosen->id_dosen)
                            ->where('status', 'diterima')
                            ->count();

                        if ($magang != 0) {
                            return ['success' => false, 'message' => 'Tidak bisa menghapus Dosen jika masih memiliki Mahasiswa bimbingan.'];
                        }

                        $foto_path = $akun->foto_path;

                        if (Storage::disk('public')->exists("profil/akun/$foto_path")) {
                            Storage::disk('public')->delete("profil/akun/$foto_path");
                        }

                        MagangModel::where('id_dosen', $akun->dosen->id_dosen)->update([
                            'id_dosen' => null
                        ]);

                        AkunModel::where('id_akun', $id_akun)
                            ->delete();
    
                        return ['success' => true];
                    }
                );
                return response()->json($results);
            } catch (\Throwable $e) {
                Log::error("Gagal menghapus lowongan: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }
}