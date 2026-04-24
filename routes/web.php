<?php

use App\Http\Controllers\AbsensiKegiatanTambahanController;
use App\Http\Controllers\AbsensiShalatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportSantriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KegiatanTambahanController;
use App\Http\Controllers\RekapSantriController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilPesantrenController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Hanya Admin ───────────────────────────────────────────────────────────
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);

        Route::resource('kelas', KelasController::class)->except(['show']);
        Route::delete('/santri/bulk-delete', [SantriController::class, 'bulkDelete'])->name('santri.bulk-delete');
        Route::resource('santri', SantriController::class)->except(['show']);
        Route::resource('kegiatan-tambahan', KegiatanTambahanController::class)->except(['show']);

        Route::get('/santri-import', [ImportSantriController::class, 'index'])->name('santri.import');
        Route::post('/santri-import', [ImportSantriController::class, 'store'])->name('santri.import.store');
        Route::get('/santri-import-template', [ImportSantriController::class, 'template'])->name('santri.import.template');

        Route::get('/profil-pesantren', [ProfilPesantrenController::class, 'show'])->name('profil-pesantren.show');
        Route::get('/profil-pesantren/edit', [ProfilPesantrenController::class, 'edit'])->name('profil-pesantren.edit');
        Route::put('/profil-pesantren', [ProfilPesantrenController::class, 'update'])->name('profil-pesantren.update');
    });

    // ─── Admin, Musyrif, dan Pengurus ──────────────────────────────────────────
    Route::middleware(['role:admin,musyrif,pengurus'])->group(function () {
        Route::get('/absensi-shalat', [AbsensiShalatController::class, 'index'])->name('absensi-shalat.index');
        Route::post('/absensi-shalat', [AbsensiShalatController::class, 'store'])->name('absensi-shalat.store');
        Route::get('/rekap-absensi-shalat', [AbsensiShalatController::class, 'rekap'])->name('absensi-shalat.rekap');
        Route::get('/export-absensi-shalat', [AbsensiShalatController::class, 'export'])->name('absensi-shalat.export');
        Route::get('/pdf-absensi-shalat', [AbsensiShalatController::class, 'pdf'])->name('absensi-shalat.pdf');
        Route::get('/export-ringkasan-absensi-shalat', [AbsensiShalatController::class, 'exportRingkasan'])
            ->name('absensi-shalat.ringkasan.export');
        Route::get('/pdf-ringkasan-absensi-shalat', [AbsensiShalatController::class, 'pdfRingkasan'])
            ->name('absensi-shalat.ringkasan.pdf');

        Route::get('/peringkat-shalat', [AbsensiShalatController::class, 'peringkat'])
            ->name('absensi-shalat.peringkat');

        Route::get('/absensi-kegiatan-tambahan', [AbsensiKegiatanTambahanController::class, 'index'])
            ->name('absensi-kegiatan-tambahan.index');
        Route::post('/absensi-kegiatan-tambahan', [AbsensiKegiatanTambahanController::class, 'store'])
            ->name('absensi-kegiatan-tambahan.store');
        Route::get('/rekap-absensi-kegiatan', [AbsensiKegiatanTambahanController::class, 'rekap'])
            ->name('absensi-kegiatan-tambahan.rekap');
        Route::get('/export-absensi-kegiatan', [AbsensiKegiatanTambahanController::class, 'export'])
            ->name('absensi-kegiatan-tambahan.export');
        Route::get('/pdf-absensi-kegiatan', [AbsensiKegiatanTambahanController::class, 'pdf'])
            ->name('absensi-kegiatan-tambahan.pdf');

        Route::get('/rekap-santri', [RekapSantriController::class, 'index'])->name('rekap-santri.index');
        Route::get('/rekap-santri/pdf', [RekapSantriController::class, 'pdf'])->name('rekap-santri.pdf');

        Route::delete('/rekap-santri/shalat/bulk-delete', [RekapSantriController::class, 'bulkDeleteShalat'])
            ->name('rekap-santri.bulk-delete-shalat');

        Route::delete('/rekap-santri/kegiatan/bulk-delete', [RekapSantriController::class, 'bulkDeleteKegiatan'])
            ->name('rekap-santri.bulk-delete-kegiatan');
    });
});

require __DIR__.'/auth.php';
