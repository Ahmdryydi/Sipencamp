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
        openCreate: false, 
        openEdit: false, 
        openDetail: false,

        // State Detail
        detailKode: '',
        detailNama: '',
        detailKategori: '',
        detailHarga: '',
        detailStok: '',
        detailStokTersedia: '',
        detailKondisi: '',
        detailStatus: '',
        detailDeskripsi: '',
        detailFoto: null,

        // State Edit
        editId: null, 
        editKategoriId: '', 
        editKode: '',
        editNama: '', 
        editHarga: '', 
        editStok: '', 
        editStokTersedia: '', 
        editKondisi: 'baik', 
        editStatus: 'aktif', 
        editDeskripsi: '' 
    }">

        <!-- Page Header & Action Button -->
        <div class="page-header flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Peralatan Camping</h1>
                <p class="text-gray-500">Kelola inventaris dan daftar peralatan camping yang disewakan.</p>
            </div>
        </div>

        <!-- Table Container Card -->
        <div class="table-card-custom">
            <!-- Header Controls -->
            <div class="table-header-control flex justify-between items-center p-4">
                <!-- Search bar -->
                <div class="table-search-box">
                    <i class="bi bi-search table-search-icon"></i>
                    <input type="text" class="table-search-input pl-9 pr-4 py-2 border rounded-lg"
                        placeholder="Cari Peralatan...">
                </div>
                <!-- Action buttons / Filter options -->
                <div class="table-filter-group">
                    <!-- Tombol Tambah Peralatan -->
                    <button @click="openCreate = true" type="button" title="Tambah Peralatan"
                        class="px-4 py-2 font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 active:scale-95 transition-all shadow-sm cursor-pointer inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <button class="btn-table-action px-3 py-2 border rounded-lg flex items-center gap-1"
                        type="button">
                        <i class="bi bi-file-earmark-arrow-down"></i> Export
                    </button>
                </div>
            </div>

            <!-- Responsive Table Wrapper -->
            <div class="table-responsive">
                <table class="table-custom w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="w-10 text-center">No</th>
                            <th>Kode</th>
                            <th>Peralatan</th>
                            <th>Kategori</th>
                            <th>Harga / Hari</th>
                            <th class="text-center">Stok (Total/Tersedia)</th>
                            <th class="text-center">Kondisi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($peralatan as $index => $item)
                            <tr>
                                <!-- Nomor Urut -->
                                <td class="text-center">{{ $index + 1 }}</td>

                                <!-- Kode Peralatan -->
                                <td class="font-mono font-medium">{{ $item->kode_peralatan }}</td>

                                <!-- Informasi Peralatan (Foto, Nama, & Deskripsi) -->
                                <td>
                                    <div class="table-user-cell flex items-center gap-3">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_peralatan }}"
                                                class="w-10 h-10 object-cover rounded-lg border"
                                                onerror="this.src='assets/images/avatar.png'">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-lg bg-gray-100 border flex items-center justify-center text-gray-400 text-xs">
                                                No Pic
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $item->nama_peralatan }}</div>
                                            <div class="text-xs text-gray-500 truncate" style="max-width: 200px;">
                                                {{ $item->deskripsi ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="">
                                    <span
                                        class="px-2 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded border border-emerald-200">
                                        {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>

                                <!-- Harga Sewa Harian -->
                                <td class="font-bold">
                                    Rp {{ number_format($item->harga_sewa_harian, 0, ',', '.') }}
                                </td>

                                <!-- Stok & Stok Tersedia -->
                                <td class=" text-center">
                                    <strong>{{ $item->stok }}</strong>
                                    <small class="text-emerald-600 font-medium">({{ $item->stok_tersedia }} ada)</small>
                                </td>

                                <!-- Kondisi -->
                                <td class="text-center">
                                    @if($item->kondisi == 'baik')
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium text-blue-700 bg-blue-50 rounded border border-blue-200">Baik</span>
                                    @elseif($item->kondisi == 'rusak_ringan')
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium text-amber-700 bg-amber-50 rounded border border-amber-200">Rusak
                                            Ringan</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium text-rose-700 bg-rose-50 rounded border border-rose-200">Maintenance</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class=" text-center">
                                    @if($item->status == 'aktif')
                                        <span
                                            class="px-2 py-0.5 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Aktif</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Nonaktif</span>
                                    @endif
                                </td>

                                <!-- Aksi / Tombol -->
                                <td class=" text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        <!-- Tombol Detail -->
                                        <button type="button"
                                            class="p-1.5 text-gray-600 hover:text-emerald-600 hover:bg-gray-100 rounded-lg transition"
                                            title="Detail Peralatan" @click="
                                                        openDetail = true;
                                                        detailKode = '{{ addslashes($item->kode_peralatan) }}';
                                                        detailNama = '{{ addslashes($item->nama_peralatan) }}';
                                                        detailKategori = '{{ addslashes($item->kategori->nama_kategori ?? 'Tanpa Kategori') }}';
                                                        detailHarga = 'Rp {{ number_format($item->harga_sewa_harian, 0, ',', '.') }}';
                                                        detailStok = '{{ $item->stok }}';
                                                        detailStokTersedia = '{{ $item->stok_tersedia }}';
                                                        detailKondisi = '{{ $item->kondisi }}';
                                                        detailStatus = '{{ $item->status }}';
                                                        detailDeskripsi = '{{ addslashes($item->deskripsi ?? '-') }}';
                                                        detailFoto = '{{ $item->foto ? asset('storage/' . $item->foto) : null }}';
                                                    ">
                                            <i class="bi bi-eye text-lg"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button type="button"
                                            class="p-1.5 text-gray-600 hover:text-amber-600 hover:bg-gray-100 rounded-lg transition"
                                            title="Edit Peralatan" @click="
                                                        openEdit = true; 
                                                        editId = '{{ $item->id }}'; 
                                                        editKategoriId = '{{ $item->kategori_id }}';
                                                        editKode = '{{ addslashes($item->kode_peralatan) }}';
                                                        editNama = '{{ addslashes($item->nama_peralatan) }}'; 
                                                        editHarga = '{{ $item->harga_sewa_harian }}';
                                                        editStok = '{{ $item->stok }}';
                                                        editStokTersedia = '{{ $item->stok_tersedia }}';
                                                        editKondisi = '{{ $item->kondisi }}';
                                                        editStatus = '{{ $item->status }}';
                                                        editDeskripsi = '{{ addslashes($item->deskripsi ?? '') }}';
                                                    ">
                                            <i class="bi bi-pencil text-lg"></i>
                                        </button>

                                        <!-- Form & Tombol Hapus -->
                                        <form id="form-delete-{{ $item->id }}"
                                            action="{{ route('peralatan.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="konfirmasiHapus('{{ $item->id }}')"
                                                class="p-1.5 text-gray-600 hover:text-rose-600 hover:bg-gray-100 rounded-lg transition"
                                                title="Hapus Peralatan">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-400">Belum ada data peralatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Controls / Pagination -->
            <div class="table-footer-control flex justify-between items-center p-4 border-t">
                <span class="table-pagination-info text-gray-500">Showing 1 to 10 of 50 entries</span>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0 flex gap-1">
                        <li class="page-item disabled"><a class="page-link border-0 px-3 py-1 rounded" href="#"><i
                                    class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a
                                class="page-link border-0 px-3 py-1 rounded bg-emerald-600 text-white" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 px-3 py-1 rounded" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 px-3 py-1 rounded" href="#">3</a></li>
                        <li class="page-item"><a class="page-link border-0 px-3 py-1 rounded" href="#"><i
                                    class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- MODAL DETAIL PERALATAN -->
        <div x-show="openDetail" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-cloak>
            <div @click.away="openDetail = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center pb-3 mb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Detail Peralatan</h3>
                    <button @click="openDetail = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Foto Peralatan -->
                    <div class="flex justify-center">
                        <template x-if="detailFoto">
                            <img :src="detailFoto" :alt="detailNama"
                                class="w-32 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                        </template>
                        <template x-if="!detailFoto">
                            <div
                                class="w-32 h-32 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 text-xs font-medium">
                                Tidak ada foto
                            </div>
                        </template>
                    </div>

                    <!-- Informasi Rincian -->
                    <div class="space-y-2 text-gray-700">
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Kode Alat:</span>
                            <span class="font-mono font-bold text-emerald-700" x-text="detailKode"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Nama Peralatan:</span>
                            <span class="font-semibold text-gray-800" x-text="detailNama"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Kategori:</span>
                            <span class="font-medium text-emerald-700" x-text="detailKategori"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Harga Sewa / Hari:</span>
                            <span class="font-semibold text-gray-800" x-text="detailHarga"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Stok (Total / Tersedia):</span>
                            <span>
                                <strong x-text="detailStok"></strong>
                                <span class="text-emerald-600 font-medium"
                                    x-text="'(' + detailStokTersedia + ' ada)'"></span>
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 items-center">
                            <span class="text-gray-500">Kondisi:</span>
                            <div>
                                <template x-if="detailKondisi === 'baik'">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium text-blue-700 bg-blue-50 rounded border border-blue-200">Baik</span>
                                </template>
                                <template x-if="detailKondisi === 'rusak_ringan'">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium text-amber-700 bg-amber-50 rounded border border-amber-200">Rusak
                                        Ringan</span>
                                </template>
                                <template x-if="detailKondisi === 'maintenance'">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium text-rose-700 bg-rose-50 rounded border border-rose-200">Maintenance</span>
                                </template>
                            </div>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 items-center">
                            <span class="text-gray-500">Status:</span>
                            <div>
                                <template x-if="detailStatus === 'aktif'">
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Aktif</span>
                                </template>
                                <template x-if="detailStatus !== 'aktif'">
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Nonaktif</span>
                                </template>
                            </div>
                        </div>
                        <div class="pt-2">
                            <span class="text-gray-500 block mb-1">Deskripsi:</span>
                            <p class="p-3 bg-gray-50 rounded-lg border border-gray-100 text-xs text-gray-600 leading-relaxed"
                                x-text="detailDeskripsi"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="openDetail = false"
                        class="px-4 py-2 font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH PERALATAN -->
        <div x-show="openCreate" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-cloak>
            <div @click.away="openCreate = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 transform transition-all max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Peralatan Baru</h3>
                <form action="{{ route('peralatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Kode Alat</label>
                                <input type="text" name="kode_peralatan" placeholder="ALT-001" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="kategori_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition bg-white">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Nama Peralatan</label>
                            <input type="text" name="nama_peralatan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Harga / Hari</label>
                                <input type="number" name="harga_sewa_harian" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Total Stok</label>
                                <input type="number" name="stok" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Stok Tersedia</label>
                                <input type="number" name="stok_tersedia" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Kondisi</label>
                                <select name="kondisi"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition bg-white">
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Status</label>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition bg-white">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Foto Peralatan</label>
                            <input type="file" name="foto" accept="image/*"
                                class="w-full text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="openCreate = false"
                            class="px-4 py-2 font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition cursor-pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT PERALATAN -->
        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-cloak>
            <div @click.away="openEdit = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 transform transition-all max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Peralatan</h3>
                <form :action="'/peralatan/' + editId" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Kode Alat</label>
                                <input type="text" name="kode_peralatan" x-model="editKode" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="kategori_id" x-model="editKategoriId" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition bg-white">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Nama Peralatan</label>
                            <input type="text" name="nama_peralatan" x-model="editNama" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Harga / Hari</label>
                                <input type="number" name="harga_sewa_harian" x-model="editHarga" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Total Stok</label>
                                <input type="number" name="stok" x-model="editStok" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Stok Tersedia</label>
                                <input type="number" name="stok_tersedia" x-model="editStokTersedia" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Kondisi</label>
                                <select name="kondisi" x-model="editKondisi"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition bg-white">
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" x-model="editStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition bg-white">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Foto Peralatan (Opsional)</label>
                            <input type="file" name="foto" accept="image/*"
                                class="w-full text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editDeskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPT SWEETALERT2 -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('update'))
            Toast.fire({
                icon: 'warning',
                title: "{{ session('update') }}"
            });
        @endif

        @if(session('delete'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('delete') }}"
            });
        @endif

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Yakin ingin menghapus peralatan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }
    </script>

@endsection