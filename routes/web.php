<?php

use App\Http\Controllers\Admin\LowonganMagangController;
use App\Http\Controllers\Admin\PeriodeMagangController as PeriodeMagangControllerAdmin;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\AktivitasController as AktivitasControllerDosen;
use App\Http\Controllers\Dosen\EvaluasiController;
use App\Http\Controllers\Dosen\KeahlianDosenController;
use App\Http\Controllers\GeolokasiController;
use App\Http\Controllers\JarakController;
use App\Http\Controllers\Admin\MagangController as MagangControllerAdmin;
use App\Http\Controllers\Mahasiswa\AktivitasController as AktivitasControllerMahasiswa;
use App\Http\Controllers\Mahasiswa\DokumenController;
use App\Http\Controllers\Mahasiswa\KeahlianMahasiswaController;
use App\Http\Controllers\Mahasiswa\MagangController as MagangControllerMahasiswa;
use App\Http\Controllers\Dosen\MagangController as MagangControllerDosen;
use App\Http\Controllers\Admin\AkunController as AkunControllerAdmin;
use App\Http\Controllers\Mahasiswa\AkunController as AkunControllerMahasiswa;
use App\Http\Controllers\Dosen\AkunController as AkunControllerDosen;
use App\Http\Controllers\Mahasiswa\PengalamanController;
use App\Http\Controllers\Mahasiswa\PenilaianController;
use App\Http\Controllers\Mahasiswa\PeriodeMagangController as PeriodeMagangControllerMahasiswa;
use App\Http\Controllers\Mahasiswa\PreferensiLokasiMahasiswaController;
use App\Http\Controllers\Mahasiswa\PreferensiPerusahaanMahasiswaController;
use App\Http\Controllers\TesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/akun', [AkunController::class, 'tes']);

// Route::get('/jarak', [JarakController::class, 'hitungJarak']);
// Route::get('/koordinat', [GeolokasiController::class, 'getKoordinat']);

// Route::pattern('id', '[0-9]+');
// Route::get('/login', [LoginController::class, 'getLogin'])->name('login');
// Route::post('/login', [LoginController::class, 'postLogin']);
// Route::get('/logout', [LogoutController::class, 'getLogout'])->middleware('auth');

// Route::put('/preferensi/lokasi/', [AkunControllerMahasiswa::class, 'putPreferensiLokasi']);

// 

Route::get('/', [TesController::class, 'index']);