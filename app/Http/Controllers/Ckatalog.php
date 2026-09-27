<?php

namespace App\Http\Controllers;

use App\Models\Mperalatan;
use App\Models\Mpaket;
use App\Models\Mkategori;
use App\Models\MrekomendasiPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Ckatalog extends Controller
{
    /**
     * Menampilkan Halaman Utama Katalog Olshop untuk Pelanggan
     */
    public function index(Request $request)
    {
        // 1. Ambil daftar kategori untuk tombol filter
        $kategoriList = Mkategori::all();

        // 2. Query Peralatan Eceran / Satuan dengan Relasi Kategori
        $queryPeralatan = Mperalatan::with('kategori');

        // Filter berdasarkan kategori jika dipilih user
        if ($request->filled('kategori_id')) {
            $queryPeralatan->where('id_kategori', $request->kategori_id);
        }

        // Filter pencarian berdasarkan kata kunci
        if ($request->filled('search')) {
            $queryPeralatan->where('nama_peralatan', 'like', '%' . $request->search . '%');
        }

        $peralatan = $queryPeralatan->paginate(12);

        // 3. Ambil Paket Sewa Hemat yang statusnya Aktif
        $pakets = Mpaket::where('status', 'aktif')->get();

        // 4. Ambil Rekomendasi AI jika User sudah login
        $rekomendasiAI = null;
        if (Auth::check()) {
            $rekomendasiAI = MrekomendasiPaket::with('paket')
                ->where('user_id', Auth::id())
                ->orderByDesc('skor')
                ->take(3)
                ->get();
        }

        return view('user.katalog.index', compact('peralatan', 'pakets', 'kategoriList', 'rekomendasiAI'));
    }

    /**
     * Detail Peralatan Satuan
     */
    public function detailAlat($id)
    {
        $item = Mperalatan::with('kategori')->findOrFail($id);
        return view('user.katalog.detail_alat', compact('item'));
    }

    /**
     * Detail Paket Sewa Hemat
     */
    public function detailPaket($id)
    {
        $paket = Mpaket::findOrFail($id);
        return view('user.katalog.detail_paket', compact('paket'));
    }
}
