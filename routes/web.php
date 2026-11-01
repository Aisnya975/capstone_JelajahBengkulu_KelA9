<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengetahuanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\NarasiController;
use App\Http\Controllers\EkstraksiLlmController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminPengetahuanController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\Admin\TestimoniController as AdminTestimoniController;

// Route Kirim Testimoni Publik
Route::post('/testimoni/store', [TestimoniController::class, 'store'])->name('testimoni.store');

// Autentikasi Pendaftaran (Register)
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Halaman publik (Beranda bebas diakses)
Route::get('/', [PengetahuanController::class, 'index'])
    ->name('home');

// Halaman detail budaya (Wajib login terlebih dahulu)
Route::get('/budaya/{pengetahuan}', [PengetahuanController::class, 'show'])
    ->middleware('auth')
    ->name('pengetahuan.show');

// Login dan logout
Route::get('/login', [LoginController::class, 'create'])
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Semua halaman pengelolaan admin wajib melewati autentikasi dan pemeriksaan role admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        // Narasi & Ekstraksi AI
        Route::get('/narasi', [NarasiController::class, 'index'])
            ->name('narasi.index');

        Route::get('/narasi/tambah', [NarasiController::class, 'create'])
            ->name('narasi.create');

        Route::post('/narasi', [NarasiController::class, 'store'])
            ->name('narasi.store');

        // Narasi CRUD Tambahan (Edit, Update, Delete)
        Route::get('/narasi/{narasi}/edit', [NarasiController::class, 'edit'])
            ->name('narasi.edit');

        Route::put('/narasi/{narasi}', [NarasiController::class, 'update'])
            ->name('narasi.update');

        Route::delete('/narasi/{narasi}', [NarasiController::class, 'destroy'])
            ->name('narasi.destroy');

        // Ekstraksi & Review AI
        Route::post('/narasi/{narasi}/ekstraksi', [EkstraksiLlmController::class, 'extract'])
            ->name('narasi.extract');

        Route::get('/narasi/{narasi}/review', [EkstraksiLlmController::class, 'review'])
            ->name('narasi.review');

        Route::post('/narasi/{narasi}/approve', [EkstraksiLlmController::class, 'approve'])
            ->name('narasi.approve');

        Route::post('/narasi/{narasi}/reject', [EkstraksiLlmController::class, 'reject'])
            ->name('narasi.reject');

        // Manajemen Akun Admin
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::post('/users', [AdminUserController::class, 'usersStore'] ?? [AdminUserController::class, 'store'])
            ->name('users.store');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        // Manajemen Pengetahuan Publik (Kelola Publik)
        Route::get('/pengetahuan-manage', [AdminPengetahuanController::class, 'index'])
            ->name('pengetahuan.manage');

        Route::get('/pengetahuan-manage/{pengetahuan}/edit', [AdminPengetahuanController::class, 'edit'])
            ->name('pengetahuan.edit');

        Route::put('/pengetahuan-manage/{pengetahuan}', [AdminPengetahuanController::class, 'update'])
            ->name('pengetahuan.update');

        Route::delete('/pengetahuan-manage/{pengetahuan}', [AdminPengetahuanController::class, 'destroy'])
            ->name('pengetahuan.destroy');

        // Manajemen Testimoni Admin (Digabung rapi di dalam grup admin)
        Route::get('/testimonis', [AdminTestimoniController::class, 'index'])->name('testimonis.index');
        Route::post('/testimonis/{id}/toggle', [AdminTestimoniController::class, 'toggle'])->name('testimonis.toggle');
        Route::delete('/testimonis/{id}', [AdminTestimoniController::class, 'destroy'])->name('testimonis.destroy');
    });