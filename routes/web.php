<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [Cdashboard::class, 'index'])->name('dashboard');

Route::resource('kategori', Ckategori::class);
Route::resource('peralatan', Cperalatan::class);
Route::resource('paket', Cpaket::class);

Route::get('/penyewaan', [Cpenyewaan::class, 'index'])->name('penyewaan.index');
Route::get('/penyewaan/{id}', [Cpenyewaan::class, 'show'])->name('penyewaan.show');
Route::patch('/penyewaan/{id}/status', [Cpenyewaan::class, 'updateStatus'])->name('penyewaan.status');

Route::get('/pembayaran', [Cpembayaran::class, 'index'])->name('pembayaran.index');
Route::patch('/pembayaran/{id}/verifikasi', [Cpembayaran::class, 'verifikasi'])->name('pembayaran.verifikasi');

Route::get('/ulasan', [Culasan::class, 'index'])->name('ulasan.index');
Route::delete('/ulasan/{id}', [Culasan::class, 'destroy'])->name('ulasan.destroy');

    
    // Modul Performa Rekomendasi AI
Route::get('/rekomendasi-ai', [CrekomendasiPaket::class, 'index'])->name('rekomendasi.index');
Route::post('/rekomendasi-ai/generate', [CrekomendasiPaket::class, 'regenerate'])->name('rekomendasi.regenerate');

Route::get('/laporan', [Claporan::class, 'index'])->name('laporan.index');
Route::get('/laporan/export-excel', [Claporan::class, 'exportExcel'])->name('laporan.excel');
Route::get('/laporan/export-pdf', [Claporan::class, 'exportPdf'])->name('laporan.pdf');

Route::resource('user', Cuser::class);