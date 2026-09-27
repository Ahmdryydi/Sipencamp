<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $jenis;

    public function __construct($data, $jenis)
    {
        $this->data = $data;
        $this->jenis = $jenis;
    }

    public function collection()
    {
        if ($this->jenis === 'pendapatan' || $this->jenis === 'penyewaan') {
            return $this->data->map(function ($item, $index) {
                return [
                    'No' => $index + 1,
                    'Kode Transaksi' => 'TRX-' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                    'Nama Customer' => $item->user->nama ?? $item->user->name ?? 'Customer',
                    'Tanggal Sewa' => $item->tanggal_sewa ?? $item->created_at,
                    'Status Pembayaran' => strtoupper($item->status_pembayaran ?? 'Lunas'),
                    'Total Biaya (Rp)' => $item->total_harga ?? $item->total_biaya ?? 0,
                ];
            });
        }

        // Laporan Peralatan Terlaris
        return $this->data->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama Peralatan' => $item->peralatan->nama_peralatan ?? 'Peralatan #' . $item->peralatan_id,
                'Jumlah Disewa' => $item->total_disewa,
                'Total Frekuensi' => $item->total_transaksi . ' kali transaksi',
            ];
        });
    }

    public function headings(): array
    {
        if ($this->jenis === 'pendapatan' || $this->jenis === 'penyewaan') {
            return ['No', 'Kode Transaksi', 'Nama Customer', 'Tanggal Sewa', 'Status Pembayaran', 'Total Biaya (Rp)'];
        }

        return ['No', 'Nama Peralatan', 'Jumlah Disewa', 'Total Frekuensi'];
    }
}