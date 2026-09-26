<?php

namespace App\Http\Controllers;

use App\Models\Mpembayaran;
use Illuminate\Http\Request;

class Cpembayaran extends Controller
{
    /**
     * Menampilkan daftar riwayat dan status pembayaran.
     */
    public function index()
    {
        $pembayarans = Mpembayaran::with(['penyewaan.user'])
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    /**
     * Verifikasi atau tolak pembayaran pelanggan.
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
        ]);

        $pembayaran = Mpembayaran::findOrFail($id);
        $pembayaran->status = $request->status;
        $pembayaran->save();

        // Jika verifikasi berhasil, otomatis set status penyewaan jadi 'disetujui' jika sebelumnya pending
        if ($request->status === 'terverifikasi' && $pembayaran->penyewaan) {
            if ($pembayaran->penyewaan->status === 'pending') {
                $pembayaran->penyewaan->update(['status' => 'disetujui']);
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui menjadi ' . ucfirst($request->status) . '.');
    }
}