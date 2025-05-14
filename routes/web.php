<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeolokasiController;
use App\Http\Controllers\JarakController;
use App\Http\Controllers\Admin\MagangController as MagangControllerAdmin;
use App\Http\Controllers\Mahasiswa\MagangController as MagangControllerMahasiswa;
use App\Http\Controllers\Dosen\MagangController as MagangControllerDosen;
use App\Http\Controllers\Admin\AkunController as AkunControllerAdmin;
use App\Http\Controllers\Mahasiswa\AkunController as AkunControllerMahasiswa;
use App\Http\Controllers\Dosen\AkunController as AkunControllerDosen;
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

Route::pattern('id', '[0-9]+');
Route::get('/login', [LoginController::class, 'getLogin'])->name('login');
Route::post('/login', [LoginController::class, 'postLogin']);
Route::get('/logout', [LogoutController::class, 'getLogout'])->middleware('auth');
// Route::put('/preferensi/lokasi/', [AkunControllerMahasiswa::class, 'putPreferensiLokasi']);

Route::middleware(['auth'])->group(function () {
    // Route::get('/', function () {
    //     return view('welcome');
    // });
    Route::get('/', [LoginController::class, 'getDashoboard']);
    Route::middleware(['authorize:ADM'])->prefix(prefix: 'admin')->group(function () {
        Route::get('/dashboard', [MagangControllerAdmin::class, 'getDashboard']);

        // Route::prefix('dashboard')->group(function (){

        // });
    });

    Route::middleware(['authorize:MHS'])->prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [MagangControllerMahasiswa::class, 'getDashboard']);
        Route::prefix('profil')->group(function () {
            Route::get('/', [AkunControllerMahasiswa::class, 'getProfil']);

            Route::prefix('edit')->group(function () {
                Route::get('/', [AkunControllerMahasiswa::class, 'getEditProfil']);

                Route::prefix('keahlian')->group(callback: function () {
                    Route::get('/', [AkunControllerMahasiswa::class, 'getAddKeahlian']);
                    Route::get('{id_keahlian}', [AkunControllerMahasiswa::class, 'getKeahlian']);
                    Route::post('/', [AkunControllerMahasiswa::class, 'postKeahlian']);
                    Route::put('{id_keahlian}', [AkunControllerMahasiswa::class, 'putKeahlian']);
                    Route::delete('{id_keahlian}/{prioritas}', [AkunControllerMahasiswa::class, 'deleteKeahlian']);
                });

                Route::prefix('pengalaman')->group(function () {
                    Route::get('/', [AkunControllerMahasiswa::class, 'getAddPengalaman']);
                    Route::get('{id_pengalaman}', [AkunControllerMahasiswa::class, 'getpengalaman']);
                    Route::post('/', [AkunControllerMahasiswa::class, 'postpengalaman']);
                    Route::put('{id_pengalaman}', [AkunControllerMahasiswa::class, 'putpengalaman']);
                    Route::delete('{id_pengalaman}', [AkunControllerMahasiswa::class, 'deletepengalaman']);
                });

                Route::prefix('dokumen')->group(function () {
                    Route::get('/', [AkunControllerMahasiswa::class, 'getAddDokumen']);
                    Route::get('{id_dokumen}', [AkunControllerMahasiswa::class, 'getDokumen']);
                    Route::post('/', [AkunControllerMahasiswa::class, 'postDokumen']);
                    Route::post('{id_dokumen}', [AkunControllerMahasiswa::class, 'putDokumen']);
                    Route::delete('{id_dokumen}', [AkunControllerMahasiswa::class, 'deleteDokumen']);
                });
 
                Route::post('/preferensi/perusahaan', [AkunControllerMahasiswa::class, 'postPreferensiPerusahaan']); //put
                Route::put('/preferensi/lokasi/{id_preferensi}', [AkunControllerMahasiswa::class, 'putPreferensiLokasi']);
                
            });
        });
        // Route::get('/magang/{id_magang}', [MagangControllerMahasiswa::class, 'getMagang']);
        // Route::get('/perusahaan/{id_perusahaan}', [MagangControllerMahasiswa::class, 'getPerusahaan']);
        // Route::get('/profil', [AkunControllerMahasiswa::class, 'getProfil']);
        // Route::get('/akun', [AkunControllerMahasiswa::class, 'getAkun']);
        // Route::post('/keahlian', [AkunControllerMahasiswa::class, 'postKeahlian']);
        // Route::post('/pengalaman', [AkunControllerMahasiswa::class, 'postPengalaman']);
        // Route::post('/kompetensi', [AkunControllerMahasiswa::class, 'postKompetensi']);
        // Route::post('/preferensi/perusahaan', [AkunControllerMahasiswa::class, 'postPreferensiPerusahaan']);
    });

    Route::middleware(['authorize:DSN'])->prefix('dosen')->group(function () {
        Route::get('/dashboard', [MagangControllerDosen::class, 'getDashboard']);
    });
});