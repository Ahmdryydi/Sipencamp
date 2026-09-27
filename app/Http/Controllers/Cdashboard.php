<?php

namespace App\Http\Controllers;

use App\Models\Mperalatan;
use App\Models\Mpenyewaan;
use App\Models\Mpembayaran;
use App\Models\Mulasan;
use App\Models\Mkategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cdashboard extends Controller
{
    public function index()
    {
        // 1. KARTU RINGKASAN STATISTIK
        $totalPeralatan = Mperalatan::count();
        
        // Penyewaan Aktif (Status selain 'Selesai' atau 'Batal')
        $penyewaanAktif = Mpenyewaan::whereIn('status', ['Menunggu Konfirmasi', 'Disetujui', 'Dipinjam'])->count();
        
        // Pendapatan Bulan Ini (Ubah 'status_pembayaran' menjadi 'status')
        $pendapatanBulanIni = Mpembayaran::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'Lunas') // <-- DIUBAH DI SINI
            ->sum('jumlah_bayar');

        // Rating Rata-rata dari tabel ulasan
        $ratingRataRata = round(Mulasan::avg('rating') ?? 0, 1);

        // 2. TABEL PENYEWAAN TERBARU (5 Transaksi Terakhir)
        $penyewaanTerbaru = Mpenyewaan::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 3. DATA GRAFIK PENDAPATAN PER BULAN (12 Bulan Tahun Ini)
        $grafikPendapatan = [];
        for ($m = 1; $m <= 12; $m++) {
            $total = Mpembayaran::whereMonth('created_at', $m)
                ->whereYear('created_at', now()->year)
                ->where('status', 'Lunas') // <-- DIUBAH DI SINI
                ->sum('jumlah_bayar');
            $grafikPendapatan[] = $total;
        }

        // 4. DATA GRAFIK DONUT (Kategori Peralatan Paling Sering Disewa)
        $kategoriDisewa = DB::table('penyewaan_detail')
            ->join('peralatan', 'penyewaan_detail.peralatan_id', '=', 'peralatan.id')
            ->join('kategori', 'peralatan.kategori_id', '=', 'kategori.id')
            ->select('kategori.nama_kategori', DB::raw('SUM(penyewaan_detail.jumlah) as total_disewa'))
            ->groupBy('kategori.id', 'kategori.nama_kategori')
            ->orderByDesc('total_disewa')
            ->take(5)
            ->get();

        $kategoriLabels = $kategoriDisewa->pluck('nama_kategori');
        $kategoriData = $kategoriDisewa->pluck('total_disewa');

        return view('admin.dashboard', compact(
            'totalPeralatan',
            'penyewaanAktif',
            'pendapatanBulanIni',
            'ratingRataRata',
            'penyewaanTerbaru',
            'grafikPendapatan',
            'kategoriLabels',
            'kategoriData'
        ));
    }
}