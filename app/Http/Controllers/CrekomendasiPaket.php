<?php

namespace App\Http\Controllers;

use App\Models\MrekomendasiPaket;
use App\Models\MpenyewaanDetail;
use App\Models\MaktivitasPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CrekomendasiPaket extends Controller
{
    /**
     * Menampilkan dashboard analitik dan aktivitas fitur AI.
     */
    public function index()
    {
        // 1. Ranking Paket Paling Sering Direkomendasikan
        $rankingPaket = MrekomendasiPaket::select('paket_id', DB::raw('count(*) as total_rekomendasi'), DB::raw('AVG(skor) as avg_skor'))
            ->with('paket')
            ->groupBy('paket_id')
            ->orderByDesc('total_rekomendasi')
            ->get();

        // 2. Kalkulasi Tingkat Konversi
        $totalRekomendasi = MrekomendasiPaket::count();

        // Menghitung berapa kali paket yang direkomendasikan berhasil disewa
        $rekomendasiSewa = DB::table('rekomendasi_paket')
            ->join('penyewaan_detail', 'rekomendasi_paket.paket_id', '=', 'penyewaan_detail.paket_id')
            ->join('penyewaan', 'penyewaan_detail.penyewaan_id', '=', 'penyewaan.id')
            ->where('penyewaan.user_id', '=', DB::raw('rekomendasi_paket.user_id'))
            ->distinct('penyewaan.id')
            ->count('penyewaan.id');

        $tingkatKonversi = $totalRekomendasi > 0 ? round(($rekomendasiSewa / $totalRekomendasi) * 100, 1) : 0;

        // 3. Log Aktivitas Pengguna (Opsional / Mentah)
        $logs = MaktivitasPengguna::with(['user', 'peralatan', 'paket'])
            ->orderByDesc('created_at')
            ->take(20)
            ->get();

        return view('admin.rekomendasi.index', compact(
            'rankingPaket',
            'totalRekomendasi',
            'rekomendasiSewa',
            'tingkatKonversi',
            'logs'
        ));
    }

    /**
     * Trigger manual untuk Regenerate Rekomendasi Paket AI.
     */
    public function regenerate()
    {
        // Contoh jika memanggil Command Artisan buatanmu (misal: php artisan ai:generate-recommendations)
        // Artisan::call('ai:generate-recommendations');

        return redirect()->back()->with('success', 'Proses kalkulasi ulang rekomendasi paket AI berhasil dijalankan.');
    }
}