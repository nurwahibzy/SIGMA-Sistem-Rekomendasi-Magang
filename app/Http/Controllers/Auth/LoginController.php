<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AkunModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect(to: '/');
        }
        return view('auth.login');
        // return view('auth.login');
        // return response()->json('a');
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
