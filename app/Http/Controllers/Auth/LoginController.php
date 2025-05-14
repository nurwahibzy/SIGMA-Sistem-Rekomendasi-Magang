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
            return redirect('/');
        }
        return view('tes.login');
        // return view('auth.login');
    }
    public function postLogin(Request $request)
    {
        // if ($request->ajax() || $request->wantsJson()) {
        $credentials = $request->only('id_user', 'password');
        if (Auth::attempt($credentials)) {
            $role = AkunModel::with('level:id_level,kode,role')->where('id_level', Auth::user()->id_level)->first();
            $kode = $role->level->kode;
            $status = $role->status;
            if ($kode == 'ADM' && $status == 'aktif') {
                return redirect('/admin/dashboard');
            } else if ($kode == 'MHS' && $status == 'aktif') {
                return redirect('/mahasiswa/dashboard');
            } else if ($kode == 'DSN' && $status == 'aktif'){
                return redirect('/dosen/dashboard'); 
            }
        }
        return response()->json([
            'status' => false,
            'message' => 'Login Gagal'
        ]);
    }

    public function getDashoboard(){
        $role = AkunModel::with('level:id_level,kode,role')->where('id_level', Auth::user()->id_level)->first();
        $kode = $role->level->kode;
        $status = $role->status;
        if ($kode == 'ADM' && $status == 'aktif') {
            return redirect('/admin/dashboard');
        } else if ($kode == 'MHS' && $status == 'aktif') {
            return redirect('/mahasiswa/dashboard');
        } else if ($kode == 'DSN' && $status == 'aktif'){
            return redirect('/dosen/dashboard'); 
        }
    }

    // public function postLogin(Request $request)
    // {
    //     // if ($request->ajax() || $request->wantsJson()) {
    //     $credentials = $request->only('username', 'password');
    //     if (Auth::attempt($credentials)) {
    //         $role = AkunModel::with('level:id_level,kode,role')->where('id_level', Auth::user()->id_level)->first();
    //         return response()->json($role->level->kode);
    //         // return response()->json(
    //         //     Auth::user()
    //         // );

    //         // return view('welcome');

    //         // return redirect('/');

    //         // return response()->json([
    //         //     'status' => true,
    //         //     'message' => 'Login Berhasil',
    //         //     'redirect' => url('/')
    //         // ]);
    //     }
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Login Gagal'
    //     ]);
    //     // }
    //     // return redirect('login');
    // }
}
