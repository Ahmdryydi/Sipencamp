<?php

namespace App\Http\Controllers;

use App\Models\Mulasan;
use Illuminate\Http\Request;

class Culasan extends Controller
{
    /**
     * Menampilkan daftar ulasan dari customer (Read-Only).
     */
    public function index()
    {
        $ulasans = Mulasan::with(['user', 'peralatan', 'paket'])
            ->latest()
            ->get();

        return view('admin.ulasan.index', compact('ulasans'));
    }

    /**
     * Hapus ulasan (Moderasi jika terindikasi spam/tidak layak).
     */
    public function destroy($id)
    {
        $ulasan = Mulasan::findOrFail($id);
        $ulasan->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus dari sistem.');
    }
}