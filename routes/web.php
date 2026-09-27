<?php

use App\Http\Controllers\Ckatalog;
use App\Http\Controllers\Cauth;
use App\Http\Controllers\Cdashboard;
use App\Http\Controllers\Ckategori;
use App\Http\Controllers\Cperalatan;
use App\Http\Controllers\Cpaket;
use App\Http\Controllers\Cpenyewaan;
use App\Http\Controllers\Cpembayaran;
use App\Http\Controllers\CrekomendasiPaket;
use App\Http\Controllers\Culasan;
use App\Http\Controllers\Claporan;
use App\Http\Controllers\Cuser;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. MODUL AUTENTIKASI (LOGIN / LOGOUT)
// ==========================================
Route::get('/login', [Cauth::class, 'index'])->name('login');
Route::post('/login', [Cauth::class, 'authenticate'])->name('login.proses');
Route::post('/logout', [Cauth::class, 'logout'])->name('logout');

// ==========================================
// 2. MODUL PELANGGAN / USER (KATALOG UTAMA)
// ==========================================
Route::get('/', [Ckatalog::class, 'index'])->name('katalog.index');
Route::get('/katalog/alat/{id}', [Ckatalog::class, 'detailAlat'])->name('katalog.detail_alat');
Route::get('/katalog/paket/{id}', [Ckatalog::class, 'detailPaket'])->name('katalog.detail_paket');

// ==========================================
// 3. MODUL ADMINISTRATOR (ADMIN DASHBOARD)
// ==========================================
Route::get('/dashboard', [Cdashboard::class, 'index'])->name('dashboard');

Route::resource('kategori', Ckategori::class);
Route::resource('peralatan', Cperalatan::class);
Route::resource('paket', Cpaket::class);
Route::resource('user', Cuser::class);

Route::get('/penyewaan', [Cpenyewaan::class, 'index'])->name('penyewaan.index');
Route::get('/penyewaan/{id}', [Cpenyewaan::class, 'show'])->name('penyewaan.show');
Route::patch('/penyewaan/{id}/status', [Cpenyewaan::class, 'updateStatus'])->name('penyewaan.status');

Route::get('/pembayaran', [Cpembayaran::class, 'index'])->name('pembayaran.index');
Route::patch('/pembayaran/{id}/verifikasi', [Cpembayaran::class, 'verifikasi'])->name('pembayaran.verifikasi');

Route::get('/ulasan', [Culasan::class, 'index'])->name('ulasan.index');
Route::delete('/ulasan/{id}', [Culasan::class, 'destroy'])->name('ulasan.destroy');

Route::get('/rekomendasi-ai', [CrekomendasiPaket::class, 'index'])->name('rekomendasi.index');
Route::post('/rekomendasi-ai/generate', [CrekomendasiPaket::class, 'regenerate'])->name('rekomendasi.regenerate');

Route::get('/laporan', [Claporan::class, 'index'])->name('laporan.index');
Route::get('/laporan/export-excel', [Claporan::class, 'exportExcel'])->name('laporan.excel');
Route::get('/laporan/export-pdf', [Claporan::class, 'exportPdf'])->name('laporan.pdf');
