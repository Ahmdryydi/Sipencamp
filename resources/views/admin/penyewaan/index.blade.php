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

    <!-- Main Wrapper Alpine.js -->
    <div x-data="{ 
        openUpdateStatus: false,
        statusId: null,
        statusKode: '',
        statusSelected: ''
    }">

        <!-- Page Header -->
        <div class="page-header flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Transaksi Penyewaan</h1>
                <p class="text-gray-500">Kelola dan pantau seluruh alur pemesanan dan pengembalian alat camping.</p>
            </div>
        </div>

        <!-- Table Container Card -->
        <div class="table-card-custom">
            <!-- Header Controls -->
            <div class="table-header-control flex justify-between items-center p-4 border-b">
                <!-- Search bar -->
                <div class="table-search-box">
                    <i class="bi bi-search table-search-icon"></i>
                    <input type="text" class="table-search-input pl-9 pr-4 py-2 border rounded-lg text-sm"
                        placeholder="Cari Kode atau Nama Penyewa...">
                </div>
                <!-- Action / Filter options -->
                <div class="table-filter-group flex items-center gap-2">
                    <button class="btn-table-action px-3 py-2 border rounded-lg flex items-center gap-1 text-sm text-gray-600 hover:bg-gray-50"
                        type="button">
                        <i class="bi bi-funnel"></i> Filter Status
                    </button>
                    <button class="btn-table-action px-3 py-2 border rounded-lg flex items-center gap-1 text-sm text-gray-600 hover:bg-gray-50"
                        type="button">
                        <i class="bi bi-file-earmark-arrow-down"></i> Export
                    </button>
                </div>
            </div>

            <!-- Responsive Table Wrapper -->
            <div class="table-responsive">
                <table class="table-custom w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3 w-10 text-center">No</th>
                            <th class="px-4 py-3">Kode Penyewaan</th>
                            <th class="px-4 py-3">Nama Penyewa</th>
                            <th class="px-4 py-3">Tgl Sewa</th>
                            <th class="px-4 py-3">Tgl Kembali Rencana</th>
                            <th class="px-4 py-3">Total Biaya</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($penyewaans as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <!-- Nomor Urut -->
                                <td class="text-center px-4 py-3 text-gray-500">{{ $index + 1 }}</td>

                                <!-- Kode Penyewaan -->
                                <td class="px-4 py-3 font-mono font-semibold text-emerald-700">
                                    <a href="{{ route('penyewaan.show', $item->id) }}" class="hover:underline">
                                        {{ $item->kode_penyewaan }}
                                    </a>
                                </td>

                                <!-- Nama Penyewa -->
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item->user->name ?? $item->user->nama ?? 'Pengguna General' }}
                                </td>

                                <!-- Tanggal Sewa -->
                                <td class="px-4 py-3 text-gray-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_sewa)->format('d M Y') }}
                                </td>

                                <!-- Tanggal Kembali Rencana -->
                                <td class="px-4 py-3 text-gray-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali_rencana)->format('d M Y') }}
                                </td>

                                <!-- Total Biaya -->
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                                </td>

                                <!-- Status Badge -->
                                <td class="px-4 py-3 text-center">
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
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full capitalize {{ $badges[$item->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        <!-- Detail Link -->
                                        <a href="{{ route('penyewaan.show', $item->id) }}"
                                            class="p-1.5 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye text-lg"></i>
                                        </a>

                                        <!-- Ubah Status Quick Action Modal -->
                                        <button type="button"
                                            class="p-1.5 text-gray-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                            title="Ubah Status" @click="
                                                openUpdateStatus = true;
                                                statusId = '{{ $item->id }}';
                                                statusKode = '{{ $item->kode_penyewaan }}';
                                                statusSelected = '{{ $item->status }}';
                                            ">
                                            <i class="bi bi-gear text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi penyewaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Controls / Pagination -->
            <div class="table-footer-control flex justify-between items-center p-4 border-t">
                <span class="table-pagination-info text-gray-500 text-sm">Showing 1 to 10 entries</span>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0 flex gap-1">
                        <li class="page-item disabled"><a class="page-link border-0 px-3 py-1 rounded text-sm" href="#"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link border-0 px-3 py-1 rounded bg-emerald-600 text-white text-sm" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 px-3 py-1 rounded text-sm" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- MODAL QUICK UBAH STATUS -->
        <div x-show="openUpdateStatus" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openUpdateStatus = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all">
                <h3 class="text-lg font-bold text-gray-800 mb-1">Ubah Status Transaksi</h3>
                <p class="text-xs text-gray-500 mb-4">Kode Transaksi: <span class="font-mono font-semibold text-emerald-600" x-text="statusKode"></span></p>

                <form :action="'/admin/penyewaan/' + statusId + '/status'" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700 text-sm mb-2">Pilih Status Baru</label>
                        <select name="status" x-model="statusSelected" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="disewa">Disewa (Barang Diserahkan)</option>
                            <option value="dikembalikan">Dikembalikan</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="openUpdateStatus = false"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">Simpan Perubahan</button>
                    </div>
                </form>
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