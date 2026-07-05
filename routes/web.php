<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\KontraktorController;
use App\Http\Controllers\PersonelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgressHarianController;
use App\Http\Controllers\ProgressMingguanController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingController;


// Dashboard Route (Auth and verified)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. Master Data CRUD (Hanya Admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('proyek', ProyekController::class);
        Route::resource('lokasi', LokasiController::class)->except(['create', 'show', 'edit']);
        Route::resource('kontraktor', KontraktorController::class)->except(['create', 'show', 'edit']);
        Route::resource('personel', PersonelController::class)->except(['create', 'show', 'edit']);
        Route::resource('user', UserController::class);

        // Settings & Backups
        Route::get('setting/pengaturan', [SettingController::class, 'pengaturan'])->name('setting.pengaturan');
        Route::put('setting/pengaturan', [SettingController::class, 'updatePengaturan'])->name('setting.pengaturan.update');
        Route::get('setting/backup', [SettingController::class, 'backup'])->name('setting.backup');
        Route::post('setting/backup', [SettingController::class, 'runBackup'])->name('setting.backup.run');
        Route::get('setting/backup/{id}/download', [SettingController::class, 'downloadBackup'])->name('setting.backup.download');
        Route::delete('setting/backup/{id}', [SettingController::class, 'deleteBackup'])->name('setting.backup.delete');
    });

    // 2. Monitoring Write Actions (Admin & Operator)
    Route::middleware('role:admin,operator')->prefix('monitoring')->group(function () {
        Route::post('progress-harian', [ProgressHarianController::class, 'store'])->name('progress-harian.store');
        Route::post('progress-mingguan', [ProgressMingguanController::class, 'store'])->name('progress-mingguan.store');
        Route::post('dokumentasi', [DokumentasiController::class, 'store'])->name('dokumentasi.store');
        Route::delete('dokumentasi/{id}', [DokumentasiController::class, 'destroy'])->name('dokumentasi.destroy');
    });

    // 3. Monitoring Read Actions (Semua Role)
    Route::prefix('monitoring')->group(function () {
        Route::get('progress-harian', [ProgressHarianController::class, 'index'])->name('progress-harian.index');
        Route::get('progress-mingguan', [ProgressMingguanController::class, 'index'])->name('progress-mingguan.index');
        Route::get('dokumentasi', [DokumentasiController::class, 'index'])->name('dokumentasi.index');
        Route::get('timeline', [MonitoringController::class, 'timeline'])->name('monitoring.timeline');
        Route::get('persentase-progress', [MonitoringController::class, 'persentaseProgress'])->name('monitoring.persentase-progress');
    });

    // 4. Laporan Module (Semua Role)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('harian', [LaporanController::class, 'harian'])->name('harian');
        Route::get('export-harian-pdf', [LaporanController::class, 'exportHarianPdf'])->name('export-harian-pdf');
        Route::get('mingguan', [LaporanController::class, 'mingguan'])->name('mingguan');
        Route::get('export-mingguan-pdf', [LaporanController::class, 'exportMingguanPdf'])->name('export-mingguan-pdf');
        Route::get('bulanan', [LaporanController::class, 'bulanan'])->name('bulanan');
        Route::get('export-bulanan-pdf', [LaporanController::class, 'exportBulananPdf'])->name('export-bulanan-pdf');
        Route::get('rekap', [LaporanController::class, 'rekap'])->name('rekap');
        Route::get('export-rekap-pdf', [LaporanController::class, 'exportRekapPdf'])->name('export-rekap-pdf');
        
        // Hanya Admin yang bisa mengekspor laporan
        Route::middleware('role:admin')->group(function () {
            Route::get('export-pdf/{id}', [LaporanController::class, 'exportPdf'])->name('export-pdf');
            Route::get('export-excel', [LaporanController::class, 'exportExcel'])->name('export-excel');
        });
    });
});

require __DIR__.'/auth.php';
