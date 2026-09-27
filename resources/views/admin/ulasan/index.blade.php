@extends('layouts.app')

@section('content')

    <!-- CDN Tailwind, Alpine.js, & SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ openDelete: false, ulasanId: null }">

        <!-- Page Header -->
        <div class="page-header flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Ulasan & Ratings</h1>
                <p class="text-gray-500">Kelola dan pantau ulasan dari pelanggan terkait peralatan dan paket sewa.</p>
            </div>
        </div>

        <!-- Table Card Wrapper -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header Controls -->
            <div class="p-4 border-b flex justify-between items-center gap-4">
                <div class="relative w-72">
                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Cari Pelanggan atau Komentar...">
                </div>
            </div>

            <!-- Table Responsive -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b">
                        <tr>
                            <th class="px-4 py-3 text-center w-10">No</th>
                            <th class="px-4 py-3">Nama Customer</th>
                            <th class="px-4 py-3">Untuk Item</th>
                            <th class="px-4 py-3 text-center">Rating</th>
                            <th class="px-4 py-3">Komentar</th>
                            <th class="px-4 py-3 text-center">Tanggal</th>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($ulasans as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                
                                <!-- Nama Customer -->
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item->user->nama ?? $item->user->name ?? 'Anonim' }}
                                </td>

                                <!-- Untuk Item (Peralatan / Paket) -->
                                <td class="px-4 py-3">
                                    @if ($item->peralatan)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="bi bi-box-seam"></i> {{ $item->peralatan->nama_peralatan }}
                                        </span>
                                    @elseif ($item->paket)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="bi bi-collection"></i> {{ $item->paket->nama_paket }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>

                                <!-- Rating Bintang -->
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="flex justify-center items-center text-amber-400 text-sm gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $item->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[11px] text-gray-500 font-medium">({{ $item->rating }}/5)</span>
                                </td>

                                <!-- Komentar -->
                                <td class="px-4 py-3 text-gray-600 max-w-xs">
                                    <p class="line-clamp-2">{{ $item->komentar ?? '-' }}</p>
                                </td>

                                <!-- Tanggal -->
                                <td class="px-4 py-3 text-center text-gray-500 text-xs whitespace-nowrap">
                                    {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                                </td>

                                <!-- Aksi (Hapus Spam/Moderasi) -->
                                <td class="px-4 py-3 text-center">
                                    <button type="button" @click="openDelete = true; ulasanId = '{{ $item->id }}'" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Ulasan (Spam/Pelanggaran)">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">Belum ada ulasan dari customer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL KONFIRMASI HAPUS MODERASI -->
        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openDelete = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <div class="text-center mb-4">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Hapus Ulasan Ini?</h3>
                    <p class="text-xs text-gray-500 mt-1">Gunakan fungsi ini hanya untuk menghapus ulasan yang mengandung unsur spam, ujaran kebencian, atau pelanggaran aturan.</p>
                </div>

                <form :action="'/admin/ulasan/' + ulasanId" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="openDelete = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">Ya, Hapus Ulasan</button>
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