<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use App\Models\MahasiswaModel;
use App\Models\PreferensiLokasiMahasiswaModel;
use App\Models\ProdiModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;

class LoginController extends Controller
{
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect(to: '/');
        }
        return view('auth.login');
    }
    public function getRegister()
    {
        $prodi = ProdiModel::where('status', 'aktif')->get();
        return view('auth.register', ['prodi' => $prodi]);
    }

    public function postLogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('id_user', 'password');
            if (Auth::attempt($credentials)) {
                $role = AkunModel::with('level:id_level,kode,role')
                    ->where('id_akun', Auth::user()->id_akun)
                    ->where('id_level', Auth::user()->id_level)->first();
                $kode = $role->level->kode;
                $status = $role->status;
                if ($kode == 'ADM' && $status == 'aktif') {
                    return response()->json([
                        'success' => true,
                        'level' => 'admin'
                    ]);
                } else if ($kode == 'MHS' && $status == 'aktif') {
                    return response()->json([
                        'success' => true,
                        'level' => 'mahasiswa'
                    ]);
                } else if ($kode == 'DSN' && $status == 'aktif') {
                    return response()->json([
                        'success' => true,
                        'level' => 'dosen'
                    ]);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Login Gagal'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal'
            ]);
        }
    }

    public function postRegister(Request $request)
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
                        ]);


                        if ($validator->fails()) {
                            return ['success' => false, 'message' => 'Registrasi Tidak Valid'];
                        }

                        $id_level = 2;
                        $id_user = $request->input('id_user');
                        $password = $request->input('password');
                        $status = 'nonaktif';
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

    public function getDashoboard()
    {
        $role = AkunModel::with('level:id_level,kode,role')
            ->where('id_akun', Auth::user()->id_akun)
            ->where('id_level', Auth::user()->id_level)->first();
        $kode = $role->level->kode;
        $status = $role->status;
        if ($kode == 'ADM' && $status == 'aktif') {
            return redirect('/admin/dashboard');
        } else if ($kode == 'MHS' && $status == 'aktif') {
            return redirect('/mahasiswa/dashboard');
        } else if ($kode == 'DSN' && $status == 'aktif') {
            return redirect('/dosen/dashboard');
        }
    }
}
