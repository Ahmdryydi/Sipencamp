<?php

use App\Http\Controllers\Cdashboard;
use App\Http\Controllers\Ckategori;
use App\Http\Controllers\Cperalatan;
use App\Http\Controllers\Cpaket;
use App\Http\Controllers\Cpenyewaan;
use App\Http\Controllers\Cpembayaran;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [Cdashboard::class, 'index'])->name('dashboard');
Route::resource('kategori', Ckategori::class);
Route::resource('peralatan', Cperalatan::class);
Route::resource('paket', Cpaket::class);

Route::get('/penyewaan', [Cpenyewaan::class, 'index'])->name('penyewaan.index');
Route::get('/penyewaan/{id}', [Cpenyewaan::class, 'show'])->name('penyewaan.show');
Route::patch('/penyewaan/{id}/status', [Cpenyewaan::class, 'updateStatus'])->name('penyewaan.status');

Route::get('/pembayaran', [Cpembayaran::class, 'index'])->name('pembayaran.index');
    Route::patch('/pembayaran/{id}/verifikasi', [Cpembayaran::class, 'verifikasi'])->name('pembayaran.verifikasi');