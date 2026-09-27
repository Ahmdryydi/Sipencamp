@extends('layouts.app')

@section('content')

    <!-- CDN Tailwind & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="p-6">
        <!-- Page Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Transaksi & Operasional</h1>
                <p class="text-gray-500 text-sm">Filter data laporan berdasarkan periode dan jenis aktivitas sistem.</p>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('laporan.excel', request()->all()) }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2 shadow-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
                <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition flex items-center gap-2 shadow-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF / Cetak
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm mb-6">
            <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Laporan</label>
                    <select name="jenis_laporan" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="pendapatan" {{ $jenis_laporan === 'pendapatan' ? 'selected' : '' }}>Laporan Pendapatan</option>
                        <option value="penyewaan" {{ $jenis_laporan === 'penyewaan' ? 'selected' : '' }}>Laporan Penyewaan</option>
                        <option value="peralatan_terlaris" {{ $jenis_laporan === 'peralatan_terlaris' ? 'selected' : '' }}>Laporan Peralatan Terlaris</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-900 transition flex items-center justify-center gap-2">
                        <i class="bi bi-filter"></i> Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table Output -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                    Hasil Filter: {{ str_replace('_', ' ', strtoupper($jenis_laporan)) }} ({{ date('d/m/Y', strtotime($tgl_mulai)) }} - {{ date('d/m/Y', strtotime($tgl_selesai)) }})
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-xs text-gray-600 uppercase tracking-wider border-b">
                        @if ($jenis_laporan === 'pendapatan' || $jenis_laporan === 'penyewaan')
                            <tr>
                                <th class="px-4 py-3 text-center w-12">No</th>
                                <th class="px-4 py-3">Kode TRX</th>
                                <th class="px-4 py-3">Pelanggan</th>
                                <th class="px-4 py-3 text-center">Tanggal Sewa</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Total Biaya</th>
                            </tr>
                        @else
                            <tr>
                                <th class="px-4 py-3 text-center w-12">Rank</th>
                                <th class="px-4 py-3">Nama Peralatan</th>
                                <th class="px-4 py-3 text-center">Total Unit Disewa</th>
                                <th class="px-4 py-3 text-center">Frekuensi Transaksi</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($laporans as $index => $row)
                            @if ($jenis_laporan === 'pendapatan' || $jenis_laporan === 'penyewaan')
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-mono font-semibold text-emerald-700">TRX-{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row->user->nama ?? $row->user->name ?? 'Customer' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">LUNAS</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($row->total_harga ?? $row->total_biaya ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @else
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-center font-bold text-gray-600">#{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row->peralatan->nama_peralatan ?? 'Peralatan #' . $row->peralatan_id }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-emerald-600">{{ $row->total_disewa }} unit</td>
                                    <td class="px-4 py-3 text-center text-gray-600">{{ $row->total_transaksi }} kali</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400">Tidak ada data laporan untuk periode dan kriteria ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection