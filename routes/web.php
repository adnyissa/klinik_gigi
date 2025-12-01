<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminDokterController;
use App\Http\Controllers\AdminPasienController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\AdminKasirController;
use App\Http\Controllers\AdminJadwalController;
use App\Http\Controllers\AdminRMController;
use App\Http\Controllers\AdminPembayaranController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasienKonsultasiController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\DokterDashboardController;
use App\Http\Controllers\DokterJadwalController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('index');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [RegistrasiController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrasiController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================================================
// ROLE: DOKTER
// ====================================================
Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function () {
        
        // Dashboard Dokter
        Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dashboard');

        // Jadwal Dokter
        Route::get('/jadwal', [DokterJadwalController::class, 'index'])->name('jadwal');
        Route::post('/jadwal', [DokterJadwalController::class, 'store'])->name('jadwal.store');
        Route::post('/jadwal/{id}/status', [DokterJadwalController::class, 'updateStatus'])->name('jadwal.status');
        Route::delete('/jadwal/{id}', [DokterJadwalController::class, 'destroy'])->name('jadwal.destroy');

        // Periksa Pasien
        Route::get('/periksa', [DokterDashboardController::class, 'periksaPasien'])->name('periksa');
        Route::post('/antrian', [DokterDashboardController::class, 'storeAntrian'])->name('antrian.store');

        // Riwayat Pasien
        Route::get('/riwayat', [DokterDashboardController::class, 'riwayatPasien'])->name('riwayat');
        Route::post('/rekam-medis', [DokterDashboardController::class, 'storeRekamMedis'])->name('rekam-medis.store');

        // Resep Obat
        Route::get('/resep', [DokterDashboardController::class, 'resepObat'])->name('resep');
    });

// ====================================================
// ROLE: PASIEN
// ====================================================
Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->name('pasien.')
    ->group(function () {
        Route::get('/dashboard', [PasienController::class, 'index'])->name('dashboard');
        Route::resource('konsultasi', PasienKonsultasiController::class)->only(['index', 'create', 'store']);
        Route::post('konsultasi/{konsultasi_id}/cancel', [PasienKonsultasiController::class, 'cancel'])->name('konsultasi.cancel');
    });

// ====================================================
// ROLE: KASIR
// ====================================================
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard');
});

// ====================================================
// ROLE: ADMIN
// ====================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::prefix('admin/dokter')->group(function () {
        Route::get('/', [AdminDokterController::class, 'index'])->name('admin.dokter.index');
        Route::post('/', [AdminDokterController::class, 'store'])->name('admin.dokter.store');
        Route::put('/{id}', [AdminDokterController::class, 'update'])->name('admin.dokter.update');
        Route::delete('/{id}', [AdminDokterController::class, 'destroy'])->name('admin.dokter.destroy');
    });

    Route::prefix('admin/pasien')->group(function () {
        Route::get('/', [AdminPasienController::class, 'index'])->name('admin.pasien.index');
        Route::post('/', [AdminPasienController::class, 'store'])->name('admin.pasien.store');
        Route::get('/edit/{pasien}', [AdminPasienController::class, 'edit'])->name('admin.pasien.edit');
        Route::patch('/{pasien}', [AdminPasienController::class, 'update'])->name('admin.pasien.update');
        Route::delete('/{pasien}', [AdminPasienController::class, 'destroy'])->name('admin.pasien.destroy');
    });

    Route::prefix('admin/kasir')->group(function () {
        Route::get('/', [AdminKasirController::class, 'index'])->name('admin.kasir.index');
        Route::post('/', [AdminKasirController::class, 'store'])->name('admin.kasir.store');
        Route::patch('/{id}', [AdminKasirController::class, 'update'])->name('admin.kasir.update');
        Route::delete('/{id}', [AdminKasirController::class, 'destroy'])->name('admin.kasir.destroy');
    });

    Route::prefix('admin/jadwal')->group(function () {
        Route::get('/', [AdminJadwalController::class, 'index'])->name('admin.jadwal.index');
        Route::post('/', [AdminJadwalController::class, 'store'])->name('admin.jadwal.store');
        Route::patch('/{id}', [AdminJadwalController::class, 'update'])->name('admin.jadwal.update');
        Route::delete('/{id}', [AdminJadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
    });

    Route::prefix('admin/rm')->group(function () {
        Route::get('/', [AdminRMController::class, 'index'])->name('admin.rm.index');
        Route::post('/', [AdminRMController::class, 'store'])->name('admin.rm.store');
        Route::patch('/{id}', [AdminRMController::class, 'update'])->name('admin.rm.update');
        Route::delete('/{id}', [AdminRMController::class, 'destroy'])->name('admin.rm.destroy');
    });

    Route::prefix('admin/pembayaran')->group(function () { 
        Route::get('/', [AdminPembayaranController::class, 'index'])->name('admin.pembayaran.index'); 
        Route::post('/fetch-detail', [AdminPembayaranController::class, 'fetchDetail'])->name('admin.pembayaran.fetchDetail');
        Route::post('/store', [AdminPembayaranController::class, 'store'])->name('admin.pembayaran.store');
    });

    Route::prefix('admin/users')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/store', [UserController::class, 'store'])->name('admin.users.store');
    });
});