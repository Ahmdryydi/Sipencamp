<?php

namespace App\Http\Controllers;

use App\Models\Mpenyewaan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Cpenyewaan extends Controller
{
    /**
     * Menampilkan daftar transaksi penyewaan.
     */
    public function index()
    {
        $penyewaans = Mpenyewaan::with('user')
            ->latest()
            ->get();

        return view('admin.penyewaan.index', compact('penyewaans'));
    }

    /**
     * Menampilkan detail transaksi penyewaan (Invoice).
     */
    public function show($id)
    {
        $penyewaan = Mpenyewaan::with(['user', 'details.peralatan', 'details.paket'])
            ->findOrFail($id);

        return view('admin.penyewaan.show', compact('penyewaan'));
    }

    /**
     * Update status operasional transaksi (Setujui, Serahkan Barang, Terima Pengembalian, Batal).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,disewa,dikembalikan,terlambat,dibatalkan',
        ]);

        $penyewaan = Mpenyewaan::findOrFail($id);
        $statusBaru = $request->status;

        // Jika status diubah menjadi 'dikembalikan', catat tanggal pengembalian aktual
        if ($statusBaru === 'dikembalikan') {
            $penyewaan->tanggal_kembali_aktual = Carbon::now()->toDateString();
        }

        $penyewaan->status = $statusBaru;
        $penyewaan->save();

        return redirect()->back()->with('success', 'Status penyewaan berhasil diperbarui menjadi ' . ucfirst($statusBaru) . '.');
    }
}