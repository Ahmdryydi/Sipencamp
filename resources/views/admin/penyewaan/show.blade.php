@extends('layouts.app')

@section('content')

    <!-- CDN Tailwind, Alpine.js, & SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Khusus Cetak Invoice (Praktek 16) -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-invoice, #printable-invoice * {
                visibility: visible;
            }
            #printable-invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
                box-shadow: none !important;
                border: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="max-w-5xl mx-auto py-4">

        <!-- Top Navigation / Back Button -->
        <div class="no-print flex items-center justify-between mb-6">
            <a href="{{ route('penyewaan.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition font-medium">
                <i class="bi bi-arrow-left text-lg"></i> Kembali ke List Penyewaan
            </a>

            <!-- Tombol Cetak Invoice -->
            <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition inline-flex items-center gap-2">
                <i class="bi bi-printer text-base"></i>
                <span>Cetak Invoice</span>
            </button>
        </div>

        <!-- INVOICE & DETAIL CARD (Print Target) -->
        <div id="printable-invoice" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">

            <!-- Invoice Header -->
            <div class="flex justify-between items-start border-b pb-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-emerald-700 tracking-tight flex items-center gap-2">
                        <i class="bi bi-tent-fill"></i> SIPENCAMP
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">Sistem Informasi Penyewaan Alat Camping</p>
                    <p class="text-xs text-gray-500">Banjarbaru, Kalimantan Selatan</p>
                </div>
                <div class="text-right">
                    <span class="text-xs uppercase tracking-widest text-gray-400 font-semibold">INVOICE PENYEWAAN</span>
                    <h3 class="text-xl font-mono font-bold text-gray-800 mt-1">{{ $penyewaan->kode_penyewaan }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Dibuat: {{ \Carbon\Carbon::parse($penyewaan->created_at)->format('d M Y H:i') }}</p>
                </div>
            </div>

            <!-- Info Penyewa & Info Transaksi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-sm">
                <!-- Info Penyewa -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h4 class="font-bold text-gray-700 uppercase text-xs tracking-wider mb-2 border-b pb-1">Informasi Penyewa</h4>
                    <p class="font-semibold text-gray-800 text-base">{{ $penyewaan->user->nama ?? $penyewaan->user->name ?? 'Pengguna' }}</p>
                    <p class="text-gray-600 mt-1"><i class="bi bi-envelope mr-1 text-gray-400"></i> {{ $penyewaan->user->email ?? '-' }}</p>
                    <p class="text-gray-600"><i class="bi bi-telephone mr-1 text-gray-400"></i> {{ $penyewaan->user->no_hp ?? '-' }}</p>
                </div>

                <!-- Info Tanggal & Status -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h4 class="font-bold text-gray-700 uppercase text-xs tracking-wider mb-2 border-b pb-1">Detail Jadwal Sewa</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-500 block">Tanggal Sewa:</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($penyewaan->tanggal_sewa)->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Rencana Kembali:</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($penyewaan->tanggal_kembali_rencana)->format('d M Y') }}</span>
                        </div>
                        <div class="col-span-2 pt-2 border-t mt-1 flex justify-between items-center">
                            <span class="text-gray-500">Status Transaksi:</span>
                            @php
                                $badges = [
                                    'pending'      => 'bg-amber-100 text-amber-800',
                                    'disetujui'    => 'bg-blue-100 text-blue-800',
                                    'disewa'       => 'bg-emerald-100 text-emerald-800',
                                    'dikembalikan' => 'bg-gray-100 text-gray-800',
                                    'terlambat'    => 'bg-rose-100 text-rose-800',
                                    'dibatalkan'   => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-0.5 text-xs font-bold rounded-full uppercase {{ $badges[$penyewaan->status] ?? 'bg-gray-100' }}">
                                {{ $penyewaan->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Rincian Item yang Disewa -->
            <div class="mb-8 overflow-hidden rounded-lg border border-gray-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Nama Barang / Paket</th>
                            <th class="px-4 py-3 text-center">Tipe Item</th>
                            <th class="px-4 py-3 text-right">Harga Satuan</th>
                            <th class="px-4 py-3 text-center">Jumlah</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($penyewaan->details as $index => $detail)
                            <tr>
                                <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ $detail->peralatan->nama_peralatan ?? $detail->paket->nama_paket ?? 'Item Sewa' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 border">
                                        {{ $detail->peralatan_id ? 'Peralatan' : 'Paket' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600">
                                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center font-medium">{{ $detail->jumlah }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-400">Tidak ada detail item.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Kalkulasi Biaya & Denda -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-6 border-t pt-6">
                <!-- Catatan Transaksi -->
                <div class="w-full md:w-1/2 text-xs text-gray-500">
                    <p class="font-bold text-gray-700 mb-1">Catatan Transaksi:</p>
                    <p class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-gray-600 italic">
                        {{ $penyewaan->catatan ?? 'Tidak ada catatan tambahan.' }}
                    </p>
                </div>

                <!-- Total Summary Table -->
                <div class="w-full md:w-1/2 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Biaya Sewa:</span>
                        <span class="font-medium">Rp {{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-rose-600">
                        <span>Denda Terlambat:</span>
                        <span class="font-medium">+ Rp {{ number_format($penyewaan->denda ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-emerald-800 border-t pt-2 mt-2">
                        <span>Total Pembayaran:</span>
                        <span>Rp {{ number_format(($penyewaan->total_biaya + ($penyewaan->denda ?? 0)), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Signatures (Print Only) -->
            <div class="hidden print:flex justify-between items-center mt-12 pt-8 text-center text-xs text-gray-600">
                <div>
                    <p class="mb-12">Penyewa,</p>
                    <p class="font-bold underline">{{ $penyewaan->user->nama ?? $penyewaan->user->name ?? 'Pengguna' }}</p>
                </div>
                <div>
                    <p class="mb-12">Petugas Admin,</p>
                    <p class="font-bold underline">{{ auth()->user()->nama ?? auth()->user()->name ?? 'Admin' }}</p>
                </div>
            </div>

        </div>

        <!-- BOTTOM ACTION BUTTONS ALUR STATUS (No Print) -->
        <div class="no-print mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-wrap items-center justify-between gap-4">
            <span class="text-sm text-gray-500 font-medium">Alur Operasional Transaksi:</span>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Status: Pending -> Setujui / Tolak -->
                @if($penyewaan->status == 'pending')
                    <form action="{{ route('penyewaan.status', $penyewaan->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="disetujui">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            <i class="bi bi-check-circle mr-1"></i> Setujui Penyewaan
                        </button>
                    </form>

                    <form action="{{ route('penyewaan.status', $penyewaan->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="dibatalkan">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                            <i class="bi bi-x-circle mr-1"></i> Batalkan / Tolak
                        </button>
                    </form>
                @endif

                <!-- Status: Disetujui -> Serahkan Barang (jadi Disewa) -->
                @if($penyewaan->status == 'disetujui')
                    <form action="{{ route('penyewaan.status', $penyewaan->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="disewa">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                            <i class="bi bi-box-arrow-right mr-1"></i> Serahkan Barang (Disewa)
                        </button>
                    </form>
                @endif

                <!-- Status: Disewa / Terlambat -> Terima Kembali (jadi Dikembalikan) -->
                @if(in_array($penyewaan->status, ['disewa', 'terlambat']))
                    <form action="{{ route('penyewaan.status', $penyewaan->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="dikembalikan">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800 transition">
                            <i class="bi bi-box-arrow-in-down-left mr-1"></i> Terima Pengembalian Barang
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>

    <!-- SCRIPT SWEETALERT2 TOAST -->
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