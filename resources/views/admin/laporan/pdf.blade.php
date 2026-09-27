<!DOCTYPE html>
<html>
<head>
    <title>Laporan Sipencamp</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>SIPENCAMP - LAPORAN {{ str_replace('_', ' ', strtoupper($jenis_laporan)) }}</h2>
        <p>Periode: {{ date('d/m/Y', strtotime($tgl_mulai)) }} s/d {{ date('d/m/Y', strtotime($tgl_selesai)) }}</p>
    </div>

    <table>
        <thead>
            @if ($jenis_laporan === 'pendapatan' || $jenis_laporan === 'penyewaan')
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="20%">Kode TRX</th>
                    <th width="30%">Pelanggan</th>
                    <th class="text-center" width="20%">Tanggal</th>
                    <th class="text-right" width="25%">Total Biaya</th>
                </tr>
            @else
                <tr>
                    <th class="text-center" width="10%">Rank</th>
                    <th width="50%">Nama Peralatan</th>
                    <th class="text-center" width="20%">Total Disewa</th>
                    <th class="text-center" width="20%">Frekuensi</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @forelse ($laporans as $index => $row)
                @if ($jenis_laporan === 'pendapatan' || $jenis_laporan === 'penyewaan')
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>TRX-{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $row->user->nama ?? $row->user->name ?? 'Customer' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                        <td class="text-right">Rp {{ number_format($row->total_harga ?? $row->total_biaya ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="text-center">#{{ $index + 1 }}</td>
                        <td>{{ $row->peralatan->nama_peralatan ?? 'Peralatan #' . $row->peralatan_id }}</td>
                        <td class="text-center">{{ $row->total_disewa }} unit</td>
                        <td class="text-center">{{ $row->total_transaksi }} kali</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>