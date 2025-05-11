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
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'postLogin']);
Route::get('/logout', [LogoutController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['authorize:ADM'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [MagangControllerAdmin::class, 'dashboard']);
        // Route::prefix('dashboard')->group(function (){

        // });
    });

    Route::middleware(['authorize:MHS'])->prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [MagangControllerMahasiswa::class, 'dashboard']);
    });

    Route::middleware(['authorize:DSN'])->prefix('dosen')->group(function () {
        Route::get('/dashboard', [MagangControllerDosen::class, 'dashboard']);
    });
});