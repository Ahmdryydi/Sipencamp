<?php

namespace App\Http\Controllers;

use App\Models\Mpenyewaan;
use App\Models\MpenyewaanDetail;
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class Claporan extends Controller
{
    public function index(Request $request)
    {
        $tgl_mulai = $request->input('tgl_mulai', date('Y-m-01'));
        $tgl_selesai = $request->input('tgl_selesai', date('Y-m-t'));
        $jenis_laporan = $request->input('jenis_laporan', 'pendapatan');

        $laporans = $this->getLaporanData($tgl_mulai, $tgl_selesai, $jenis_laporan);

        return view('admin.laporan.index', compact('laporans', 'tgl_mulai', 'tgl_selesai', 'jenis_laporan'));
    }

    public function exportExcel(Request $request)
    {
        $tgl_mulai = $request->input('tgl_mulai', date('Y-m-01'));
        $tgl_selesai = $request->input('tgl_selesai', date('Y-m-t'));
        $jenis_laporan = $request->input('jenis_laporan', 'pendapatan');

        $laporans = $this->getLaporanData($tgl_mulai, $tgl_selesai, $jenis_laporan);

        return Excel::download(new LaporanExport($laporans, $jenis_laporan), 'Laporan_' . $jenis_laporan . '_' . date('Ymd') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $tgl_mulai = $request->input('tgl_mulai', date('Y-m-01'));
        $tgl_selesai = $request->input('tgl_selesai', date('Y-m-t'));
        $jenis_laporan = $request->input('jenis_laporan', 'pendapatan');

        $laporans = $this->getLaporanData($tgl_mulai, $tgl_selesai, $jenis_laporan);

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporans', 'tgl_mulai', 'tgl_selesai', 'jenis_laporan'));
        return $pdf->stream('Laporan_' . $jenis_laporan . '_' . date('Ymd') . '.pdf');
    }

    private function getLaporanData($tgl_mulai, $tgl_selesai, $jenis_laporan)
    {
        if ($jenis_laporan === 'pendapatan') {
            return Mpenyewaan::with('user')
                ->whereBetween(DB::raw('DATE(created_at)'), [$tgl_mulai, $tgl_selesai])
                ->latest()
                ->get();
        } elseif ($jenis_laporan === 'penyewaan') {
            return Mpenyewaan::with(['user', 'penyewaanDetail'])
                ->whereBetween(DB::raw('DATE(created_at)'), [$tgl_mulai, $tgl_selesai])
                ->latest()
                ->get();
        } else { // peralatan_terlaris
            return MpenyewaanDetail::select('peralatan_id', DB::raw('SUM(jumlah) as total_disewa'), DB::raw('COUNT(penyewaan_id) as total_transaksi'))
                ->whereHas('penyewaan', function ($query) use ($tgl_mulai, $tgl_selesai) {
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$tgl_mulai, $tgl_selesai]);
                })
                ->whereNotNull('peralatan_id')
                ->groupBy('peralatan_id')
                ->orderByDesc('total_disewa')
                ->with('peralatan')
                ->get();
        }
    }
}