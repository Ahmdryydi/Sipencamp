@extends('layouts.app')

@section('content')

    <!-- CDN Tailwind, Alpine.js, & SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div x-data="{ activeTab: 'ranking' }">

        <!-- Page Header & Trigger Button -->
        <div class="page-header flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-magic text-emerald-600"></i> Performa Rekomendasi AI
                </h1>
                <p class="text-gray-500">Pantau efektivitas algoritma rekomendasi paket sewa dan aktivitas sistem.</p>
            </div>

            <form action="{{ route('rekomendasi.regenerate') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2 shadow-sm">
                    <i class="bi bi-arrow-repeat"></i> Generate Ulang Rekomendasi
                </button>
            </form>
        </div>

        <!-- STATS METRIC CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <!-- Total Rekomendasi Diberikan -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="bi bi-cpu"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Rekomendasi</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalRekomendasi }}</h3>
                </div>
            </div>

            <!-- Rekomendasi Dikonversi -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Berhasil Dikonversi</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $rekomendasiSewa }} <span
                            class="text-xs font-normal text-gray-500">transaksi</span></h3>
                </div>
            </div>

            <!-- Tingkat Konversi Rate -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tingkat Konversi</p>
                    <h3 class="text-2xl font-bold text-purple-600">{{ $tingkatKonversi }}%</h3>
                </div>
            </div>
        </div>

        <!-- TAB NAVIGATION -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b bg-gray-50/50 px-4 flex gap-6 text-sm font-medium">
                <button @click="activeTab = 'ranking'"
                    :class="activeTab === 'ranking' ? 'border-emerald-600 text-emerald-600 border-b-2 py-3' : 'text-gray-500 hover:text-gray-700 py-3'">
                    <i class="bi bi-trophy"></i> Paket Paling Sering Direkomendasikan
                </button>
                <button @click="activeTab = 'konversi'"
                    :class="activeTab === 'konversi' ? 'border-emerald-600 text-emerald-600 border-b-2 py-3' : 'text-gray-500 hover:text-gray-700 py-3'">
                    <i class="bi bi-pie-chart"></i> Detail Konversi
                </button>
                <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'border-emerald-600 text-emerald-600 border-b-2 py-3' : 'text-gray-500 hover:text-gray-700 py-3'">
                    <i class="bi bi-journal-text"></i> Log Aktivitas Pengguna
                </button>
            </div>

            <!-- TAB 1: RANKING PAKET -->
            <div x-show="activeTab === 'ranking'" class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">Rank</th>
                                <th class="px-4 py-3">Nama Paket</th>
                                <th class="px-4 py-3 text-center">Frekuensi Rekomendasi</th>
                                <th class="px-4 py-3 text-center">Rata-rata Skor Kecocokan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($rankingPaket as $index => $row)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-center font-bold text-gray-600">
                                        #{{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">
                                        {{ $row->paket->nama_paket ?? 'Paket #' . $row->paket_id }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-emerald-600">
                                        {{ $row->total_rekomendasi }} kali
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md font-mono font-semibold text-xs border border-emerald-200">
                                            {{ number_format($row->avg_skor, 2) }} / 1.00
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400">Belum ada data rekomendasi yang
                                        dihasilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: TINGKAT KONVERSI ANALYTICS -->
            <div x-show="activeTab === 'konversi'" class="p-6" x-cloak>
                <div class="max-w-xl mx-auto bg-gray-50 p-6 rounded-xl border text-center">
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Rasio Efektivitas Rekomendasi AI</h4>
                    <p class="text-xs text-gray-500 mb-6">Persentase pengguna yang langsung menyewa paket sewa berdasarkan
                        saran dari algoritma AI.</p>

                    <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden mb-4">
                        <div class="bg-emerald-600 h-6 rounded-full text-xs text-white font-bold flex items-center justify-center transition-all duration-500"
                            style="width: {{ $tingkatKonversi }}%">
                            {{ $tingkatKonversi }}%
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-left mt-6 pt-4 border-t border-gray-200 text-sm">
                        <div>
                            <span class="text-xs text-gray-400 block">Total Diberikan</span>
                            <strong class="text-gray-800 text-base">{{ $totalRekomendasi }} Rekomendasi</strong>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Dikonversi Menjadi Transaksi</span>
                            <strong class="text-emerald-600 text-base">{{ $rekomendasiSewa }} Transaksi</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: LOG AKTIVITAS PENGGUNA -->
            <div x-show="activeTab === 'logs'" class="p-4" x-cloak>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b">
                            <tr>
                                <th class="px-4 py-3 text-center w-10">No</th>
                                <th class="px-4 py-3">Pengguna</th>
                                <th class="px-4 py-3 text-center">Jenis Aktivitas</th>
                                <th class="px-4 py-3">Target Item</th>
                                <th class="px-4 py-3 text-center">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($logs as $index => $log)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>

                                    <!-- Nama Pengguna -->
                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        {{ $log->user->nama ?? $log->user->name ?? 'User #' . $log->user_id }}
                                    </td>

                                    <!-- Jenis Aktivitas Badges -->
                                    <td class="px-4 py-3 text-center">
                                        @if($log->jenis_aktivitas === 'lihat')
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                                <i class="bi bi-eye"></i> Lihat
                                            </span>
                                        @elseif($log->jenis_aktivitas === 'cari')
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="bi bi-search"></i> Cari
                                            </span>
                                        @elseif($log->jenis_aktivitas === 'sewa')
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="bi bi-cart-check"></i> Sewa
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-md bg-gray-100 text-gray-600 border">
                                                {{ $log->jenis_aktivitas }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Target Item (Peralatan / Paket) -->
                                    <td class="px-4 py-3 text-xs">
                                        @if ($log->peralatan)
                                            <span class="text-blue-600 font-medium"><i class="bi bi-box-seam"></i>
                                                {{ $log->peralatan->nama_peralatan }}</span>
                                        @elseif ($log->paket)
                                            <span class="text-purple-600 font-medium"><i class="bi bi-collection"></i>
                                                {{ $log->paket->nama_paket }}</span>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>

                                    <!-- Waktu -->
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">
                                        {{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i:s') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-400">Belum ada catatan log aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- SWEETALERT2 TOAST NOTIFICATION -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif
    </script>

@endsection