@extends('layouts.app')

@section('content')

    <!-- CDN Tailwind CSS, Alpine.js, & Chart.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="p-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Utama</h1>
            <p class="text-gray-500 text-sm">Ringkasan performa penyewaan, pendapatan, dan aktivitas sistem Sipencamp.</p>
        </div>

        <!-- 1. KARTU RINGKASAN STATISTIK (4 CARDS) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            
            <!-- Card 1: Total Peralatan -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Peralatan</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalPeralatan }}</h3>
                </div>
            </div>

            <!-- Card 2: Penyewaan Aktif -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Penyewaan Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $penyewaanAktif }}</h3>
                </div>
            </div>

            <!-- Card 3: Pendapatan Bulan Ini -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                    <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Card 4: Rating Rata-rata -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rating Rata-rata</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $ratingRataRata }} <span class="text-xs font-normal text-gray-400">/ 5.0</span></h3>
                </div>
            </div>

        </div>

        <!-- 2. SECTION GRAFIK (LINE CHART & DONUT CHART) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Grafik Pendapatan Per Bulan (2 Kolom) -->
            <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-bold text-gray-800">Grafik Pendapatan Tahun {{ date('Y') }}</h3>
                    <span class="text-xs text-gray-400">Pembaruan Real-time</span>
                </div>
                <div class="h-64">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>

            <!-- Grafik Kategori Terlaris (1 Kolom) -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-gray-800">Kategori Paling Sering Disewa</h3>
                    <p class="text-xs text-gray-400">Proporsi persewaan berdasarkan kategori</p>
                </div>
                <div class="h-64 flex justify-center items-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

        </div>

        <!-- 3. TABEL PENYEWAAN TERBARU -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-800">Penyewaan Terbaru</h3>
                <a href="{{ route('laporan.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Lihat Semua Transaksi &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Kode TRX</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3 text-center">Tanggal Sewa</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($penyewaanTerbaru as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-mono font-semibold text-emerald-700">
                                    TRX-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item->user->nama ?? $item->user->name ?? 'Customer #' . $item->user_id }}
                                </td>
                                <td class="px-4 py-3 text-center text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($item->status === 'Dipinjam')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                            Dipinjam
                                        </span>
                                    @elseif ($item->status === 'Selesai')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Selesai
                                        </span>
                                    @elseif ($item->status === 'Disetujui')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-purple-50 text-purple-700 border border-purple-200">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $item->status ?? 'Menunggu' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                    Rp {{ number_format($item->total_harga ?? $item->total_biaya ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400">Belum ada transaksi penyewaan terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- INIALISASI CHART.JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Line Chart - Pendapatan
            const ctxIncome = document.getElementById('incomeChart').getContext('2d');
            new Chart(ctxIncome, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($grafikPendapatan),
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // 2. Donut Chart - Kategori Terlaris
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: @json($kategoriLabels),
                    datasets: [{
                        data: @json($kategoriData),
                        backgroundColor: [
                            '#059669',
                            '#2563eb',
                            '#9333ea',
                            '#f59e0b',
                            '#64748b'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });

        });
    </script>

@endsection