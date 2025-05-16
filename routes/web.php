<?php

use App\Http\Controllers\Admin\LowonganMagangController;
use App\Http\Controllers\Admin\PerusahaanController;
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
use App\Http\Controllers\Mahasiswa\PeriodeMagangController;
use App\Http\Controllers\Mahasiswa\PreferensiLokasiMahasiswaController;
use App\Http\Controllers\Mahasiswa\PreferensiPerusahaanMahasiswaController;
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
    Route::middleware(['authorize:ADM'])->prefix( 'admin')->group(function () {
        Route::get('/dashboard', [MagangControllerAdmin::class, 'getDashboard']);
        Route::prefix('profil')->group(function () {

        });
        Route::prefix('perusahaan')->group(function () {
            Route::get('/', [PerusahaanController::class, 'getPerusahaan']);
            Route::get('/tambah', [PerusahaanController::class, 'getAddPerusahaan']);
            Route::post('/tambah', [PerusahaanController::class, 'postPerusahaan']);
            Route::get('/detail/{id_perusahaan}', [PerusahaanController::class, 'getDetailPerusahaan']);
            Route::get('/edit/{id_perusahaan}', [PerusahaanController::class, 'getEditPerusahaan']);
            Route::post('/edit/{id_perusahaan}', [PerusahaanController::class, 'putPerusahaan']);
            Route::delete('/edit/{id_perusahaan}', [PerusahaanController::class, 'deletePerusahaan']);
        });

        Route::prefix('lowongan')->group(function () {
            Route::get('/', [LowonganMagangController::class, 'getLowongan']);
            Route::get('/tambah', [LowonganMagangController::class, 'getAddLowongan']);
            Route::post('/tambah', [LowonganMagangController::class, 'postLowongan']);
            Route::get('/detail/{id_lowongan}', [LowonganMagangController::class, 'getDetailLowongan']);
            Route::get('/edit/{id_lowongan}', [LowonganMagangController::class, 'getEditLowongan']);
            Route::post('/edit/{id_lowongan}', [LowonganMagangController::class, 'putLowongan']);
            Route::delete('/edit/{id_lowongan}', [LowonganMagangController::class, 'deleteLowongan']);
        });

    });

    Route::middleware(['authorize:MHS'])->prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [PeriodeMagangController::class, 'getDashboard']);
        Route::prefix('profil')->group(function () {
            Route::get('/', [AkunControllerMahasiswa::class, 'getProfil']);

            Route::prefix('edit')->group(function () {
                Route::get('/', [AkunControllerMahasiswa::class, 'getEditProfil']);

                Route::prefix('keahlian')->group(callback: function () {
                    Route::get('/', [KeahlianMahasiswaController::class, 'getAddKeahlian']);
                    Route::get('{id_keahlian}', [KeahlianMahasiswaController::class, 'getKeahlian']);
                    Route::post('/', [KeahlianMahasiswaController::class, 'postKeahlian']);
                    Route::put('{id_keahlian}', [KeahlianMahasiswaController::class, 'putKeahlian']);
                    Route::delete('{id_keahlian}/{prioritas}', [KeahlianMahasiswaController::class, 'deleteKeahlian']);
                });

                Route::prefix('pengalaman')->group(function () {
                    Route::get('/', [PengalamanController::class, 'getAddPengalaman']);
                    Route::get('{id_pengalaman}', [PengalamanController::class, 'getpengalaman']);
                    Route::post('/', [PengalamanController::class, 'postpengalaman']);
                    Route::put('{id_pengalaman}', [PengalamanController::class, 'putpengalaman']);
                    Route::delete('{id_pengalaman}', [PengalamanController::class, 'deletepengalaman']);
                });

                Route::prefix('dokumen')->group(callback: function () {
                    Route::get('/', [DokumenController::class, 'getAddDokumen']);
                    Route::get('{id_dokumen}', [DokumenController::class, 'getDokumen']);
                    Route::post('/', [DokumenController::class, 'postDokumen']);
                    Route::post('{id_dokumen}', [DokumenController::class, 'putDokumen']);
                    Route::delete('{id_dokumen}', [DokumenController::class, 'deleteDokumen']);
                });

                Route::post('/preferensi/perusahaan', [PreferensiPerusahaanMahasiswaController::class, 'postPreferensiPerusahaan']); //put
                Route::put('/preferensi/lokasi/{id_preferensi}', [PreferensiLokasiMahasiswaController::class, 'putPreferensiLokasi']);

            });
        });

        Route::prefix('aktivitas')->group( function () {
            Route::get('/', [AktivitasControllerMahasiswa::class, 'getMagangDiterima']);
            Route::get('/{id_magang}', [AktivitasControllerMahasiswa::class, 'getAktivitas']);
            Route::get('/{id_magang}/tambah', [AktivitasControllerMahasiswa::class, 'getAddAktivitas']);
            Route::post('/{id_magang}/tambah', [AktivitasControllerMahasiswa::class, 'postAktivitas']);
            Route::get('/{id_magang}/edit/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'getEditAktivitas']);
            Route::post('/{id_magang}/edit/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'putAktivitas']);
            Route::delete('/{id_magang}/edit/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'deleteAktivitas']);
        });

        Route::prefix('riwayat')->group(function () {
            Route::get('/', [MagangControllerMahasiswa::class, 'getRiwayat']);
        });

        Route::prefix('penilaian')->group(function () {
            Route::get('/{id_magang}', [PenilaianController::class, 'getPenilaian']);
            Route::post('/{id_magang}', [PenilaianController::class, 'postPenilaian']);
        });

        Route::prefix('periode')->group(function () {
            Route::get('/detail/{id_periode}', [PeriodeMagangController::class, 'getDetailPeriode']);
            Route::post('/daftar/{id_periode}', [MagangControllerMahasiswa::class, 'postMagang']);
        });
    });

    Route::middleware(['authorize:DSN'])->prefix('dosen')->group(function () {
        Route::get('/dashboard', [MagangControllerDosen::class, 'getDashboard']);
        Route::prefix('profil')->group(function () {
            Route::get('/', [AkunControllerDosen::class, 'getProfil']);

            Route::prefix('edit')->group(function () {
                Route::get('/', [AkunControllerDosen::class, 'getEditProfil']);

                Route::prefix('keahlian')->group( function () {
                    Route::get('/', [KeahlianDosenController::class, 'getAddKeahlian']);
                    Route::get('{id_keahlian}', [KeahlianDosenController::class, 'getKeahlian']);
                    Route::post('/', [KeahlianDosenController::class, 'postKeahlian']);
                    Route::put('{id_keahlian}', [KeahlianDosenController::class, 'putKeahlian']);
                    Route::delete('{id_keahlian}', [KeahlianDosenController::class, 'deleteKeahlian']);
                });
                
            });
        });

        Route::prefix('aktivitas')->group( function () {
            Route::get('/', [AktivitasControllerDosen::class, 'getMagangDiterima']);
            Route::get('/{id_magang}', [AktivitasControllerDosen::class, 'getAktivitas']);
            Route::get('/{id_magang}/profil', [AkunControllerDosen::class, 'getProfilMahasiswa']);
            Route::get('/{id_magang}/evaluasi', [EvaluasiController::class, 'getEvaluasi']);
            Route::post('/{id_magang}/evaluasi', [EvaluasiController::class, 'postEvaluasi']);
            Route::get('/{id_magang}/evaluasi/{id_evaluasi}', [EvaluasiController::class, 'getEditEvaluasi']);
            Route::put('/{id_magang}/evaluasi/{id_evaluasi}', [EvaluasiController::class, 'putEvaluasi']); 
            Route::delete('/{id_magang}/evaluasi/{id_evaluasi}', [EvaluasiController::class, 'deleteEvaluasi']); 
        });
        
        Route::prefix('riwayat')->group( function () {
            Route::get('/', [MagangControllerDosen::class, 'getRiwayat']);
        });
    });
});