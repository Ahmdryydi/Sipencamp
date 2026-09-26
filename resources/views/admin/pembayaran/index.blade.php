@extends('layouts.app')

@section('content')

    <!-- CDN Tailwind, Alpine.js, & SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ 
        openModalBukti: false,
        imgBukti: '',
        kodeBayar: '',
        openVerifikasi: false,
        bayarId: null,
        statusVerifikasi: ''
    }">

        <!-- Page Header -->
        <div class="page-header flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Verifikasi Pembayaran</h1>
                <p class="text-gray-500">Kelola dan verifikasi bukti transfer/pembayaran dari pelanggan.</p>
            </div>
        </div>

        <!-- Table Card Wrapper -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header Controls -->
            <div class="p-4 border-b flex justify-between items-center gap-4">
                <div class="relative w-72">
                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Cari Kode Bayar atau Penyewa...">
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 flex items-center gap-1">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </div>

            <!-- Table Responsive -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b">
                        <tr>
                            <th class="px-4 py-3 text-center w-10">No</th>
                            <th class="px-4 py-3">Kode Pembayaran</th>
                            <th class="px-4 py-3">Kode Penyewaan</th>
                            <th class="px-4 py-3">Nama Penyewa</th>
                            <th class="px-4 py-3 text-center">Metode</th>
                            <th class="px-4 py-3 text-right">Jumlah Bayar</th>
                            <th class="px-4 py-3 text-center">Tgl Bayar</th>
                            <th class="px-4 py-3 text-center">Bukti</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pembayarans as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-mono font-semibold text-emerald-700">
                                    {{ $item->kode_pembayaran }}
                                </td>
                                <td class="px-4 py-3 font-mono text-gray-600">
                                    {{ $item->penyewaan->kode_penyewaan ?? '-' }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item->penyewaan->user->nama ?? $item->penyewaan->user->name ?? 'Pengguna' }}
                                </td>
                                <td class="px-4 py-3 text-center uppercase text-xs font-bold text-gray-600">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 border">
                                        {{ $item->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                    Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">
                                    {{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') : '-' }}
                                </td>
                                
                                <!-- Bukti Bayar Preview Button -->
                                <td class="px-4 py-3 text-center">
                                    @if($item->bukti_bayar)
                                        <button type="button" @click="
                                            openModalBukti = true;
                                            imgBukti = '{{ asset('storage/' . $item->bukti_bayar) }}';
                                            kodeBayar = '{{ $item->kode_pembayaran }}';
                                        " class="px-2.5 py-1 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition inline-flex items-center gap-1">
                                            <i class="bi bi-image"></i> Lihat
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusClass = [
                                            'pending'       => 'bg-amber-100 text-amber-800',
                                            'terverifikasi' => 'bg-emerald-100 text-emerald-800',
                                            'ditolak'       => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full capitalize {{ $statusClass[$item->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <!-- Aksi Verifikasi / Tolak -->
                                <td class="px-4 py-3 text-center">
                                    @if($item->status == 'pending')
                                        <div class="flex justify-center items-center gap-1">
                                            <button type="button" @click="
                                                openVerifikasi = true;
                                                bayarId = '{{ $item->id }}';
                                                statusVerifikasi = 'terverifikasi';
                                            " class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg" title="Verifikasi">
                                                <i class="bi bi-check-circle-fill text-lg"></i>
                                            </button>
                                            <button type="button" @click="
                                                openVerifikasi = true;
                                                bayarId = '{{ $item->id }}';
                                                statusVerifikasi = 'ditolak';
                                            " class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="Tolak">
                                                <i class="bi bi-x-circle-fill text-lg"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-8 text-gray-400">Belum ada transaksi pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL PREVIEW BUKTI BAYAR -->
        <div x-show="openModalBukti" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-cloak>
            <div @click.away="openModalBukti = false" class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
                <button @click="openModalBukti = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Bukti Pembayaran</h3>
                <p class="text-xs text-gray-500 mb-4">Kode Transaksi: <span class="font-mono font-semibold text-emerald-600" x-text="kodeBayar"></span></p>
                <div class="border rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center min-h-[250px]">
                    <img :src="imgBukti" alt="Bukti Transfer" class="max-h-[400px] object-contain w-full">
                </div>
            </div>
        </div>

        <!-- MODAL KONFIRMASI VERIFIKASI -->
        <div x-show="openVerifikasi" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openVerifikasi = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Status Pembayaran</h3>
                <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin mengubah status pembayaran ini menjadi <strong class="uppercase text-emerald-600" x-text="statusVerifikasi"></strong>?</p>

                <form :action="'/admin/pembayaran/' + bayarId + '/verifikasi'" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" :value="statusVerifikasi">

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openVerifikasi = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Proses</button>
                    </div>
                </form>
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