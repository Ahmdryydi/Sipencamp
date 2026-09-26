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
        detailHarga: '',
        detailStatus: '',
        detailDeskripsi: '',
        detailFoto: '',

        // State Edit
        editId: null, 
        editKode: '',
        editNama: '', 
        editHarga: '',
        editStatus: 'aktif',
        editDeskripsi: '',
        editFotoPreview: ''
    }">

        <!-- Page Header -->
        <div class="page-header flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Paket Sewa Camping</h1>
                <p class="text-gray-500">Kelola daftar paket sewa peralatan camping yang tersedia.</p>
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
                        placeholder="Cari Paket Sewa...">
                </div>
                <!-- Action buttons / Filter options -->
                <div class="table-filter-group flex items-center gap-2">
                    <!-- Tombol Tambah Paket -->
                    <button @click="openCreate = true" type="button" title="Tambah Paket Sewa"
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
                            <th class="px-4 py-3 w-10 text-center">No</th>
                            <th class="px-4 py-3 w-20 text-center">Foto</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Paket</th>
                            <th class="px-4 py-3">Harga / Hari</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pakets as $index => $item)
                            <tr>
                                <!-- Nomor Urut -->
                                <td class="text-center px-4 py-3">{{ $index + 1 }}</td>

                                <!-- Foto Paket -->
                                <td class="px-4 py-3 text-center">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto {{ $item->nama_paket }}" class="w-12 h-12 object-cover rounded-lg mx-auto border">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 border rounded-lg flex items-center justify-center text-xs text-gray-400 mx-auto">
                                            No Img
                                        </div>
                                    @endif
                                </td>

                                <!-- Kode Paket -->
                                <td class="px-4 py-3 font-mono text-sm text-gray-600">{{ $item->kode_paket }}</td>

                                <!-- Nama Paket -->
                                <td class="px-4 py-3 font-bold text-gray-800">{{ $item->nama_paket }}</td>

                                <!-- Harga Paket -->
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    Rp {{ number_format($item->harga_paket, 0, ',', '.') }}
                                </td>

                                <!-- Status Paket -->
                                <td class="px-4 py-3 text-center">
                                    @if($item->status == 'aktif')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi / Tombol -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        <!-- Tombol Detail -->
                                        <button type="button"
                                            class="p-1.5 text-gray-600 hover:text-emerald-600 hover:bg-gray-100 rounded-lg transition"
                                            title="Detail Paket" @click="
                                                openDetail = true;
                                                detailKode = '{{ addslashes($item->kode_paket) }}';
                                                detailNama = '{{ addslashes($item->nama_paket) }}';
                                                detailHarga = 'Rp {{ number_format($item->harga_paket, 0, ',', '.') }}';
                                                detailStatus = '{{ ucfirst($item->status) }}';
                                                detailDeskripsi = '{{ addslashes($item->deskripsi ?? '-') }}';
                                                detailFoto = '{{ $item->foto ? asset('storage/' . $item->foto) : '' }}';
                                            ">
                                            <i class="bi bi-eye text-lg"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button type="button"
                                            class="p-1.5 text-gray-600 hover:text-amber-600 hover:bg-gray-100 rounded-lg transition"
                                            title="Edit Paket" @click="
                                                openEdit = true; 
                                                editId = '{{ $item->id }}'; 
                                                editKode = '{{ addslashes($item->kode_paket) }}'; 
                                                editNama = '{{ addslashes($item->nama_paket) }}'; 
                                                editHarga = '{{ $item->harga_paket }}'; 
                                                editStatus = '{{ $item->status }}'; 
                                                editDeskripsi = '{{ addslashes($item->deskripsi ?? '') }}';
                                                editFotoPreview = '{{ $item->foto ? asset('storage/' . $item->foto) : '' }}';
                                            ">
                                            <i class="bi bi-pencil text-lg"></i>
                                        </button>

                                        <!-- Form & Tombol Hapus -->
                                        <form id="form-delete-{{ $item->id }}"
                                            action="{{ route('paket.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="konfirmasiHapus('{{ $item->id }}')"
                                                class="p-1.5 text-gray-600 hover:text-rose-600 hover:bg-gray-100 rounded-lg transition"
                                                title="Hapus Paket">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada data paket sewa.</td>
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
                        <li class="page-item disabled"><a class="page-link border-0 px-3 py-1 rounded" href="#"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link border-0 px-3 py-1 rounded bg-emerald-600 text-white" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 px-3 py-1 rounded" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 px-3 py-1 rounded" href="#">3</a></li>
                        <li class="page-item"><a class="page-link border-0 px-3 py-1 rounded" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- MODAL DETAIL PAKET -->
        <div x-show="openDetail" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openDetail = false" class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 transform transition-all">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Paket Sewa</h3>
                <div class="space-y-3">
                    <template x-if="detailFoto">
                        <div class="mb-3 text-center">
                            <img :src="detailFoto" class="h-40 w-full object-cover rounded-lg border">
                        </div>
                    </template>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-medium text-gray-500 text-sm">Kode Paket</label>
                            <p class="font-semibold text-gray-800 font-mono" x-text="detailKode"></p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-500 text-sm">Status</label>
                            <p class="font-semibold text-gray-800" x-text="detailStatus"></p>
                        </div>
                    </div>
                    <div>
                        <label class="font-medium text-gray-500 text-sm">Nama Paket</label>
                        <p class="font-semibold text-gray-800 text-base" x-text="detailNama"></p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-500 text-sm">Harga Paket</label>
                        <p class="font-bold text-emerald-600 text-lg" x-text="detailHarga"></p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-500 text-sm">Deskripsi</label>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200 mt-1 text-sm" x-text="detailDeskripsi"></p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="openDetail = false"
                        class="px-4 py-2 font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH PAKET -->
        <div x-show="openCreate" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openCreate = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Paket Sewa Baru</h3>
                <form action="{{ route('paket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Kode Paket</label>
                            <input type="text" name="kode_paket" placeholder="Misal: PKT-001" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Nama Paket</label>
                            <input type="text" name="nama_paket" required placeholder="Misal: Paket Hemat 2 Orang"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Harga Paket (Rp)</label>
                                <input type="number" name="harga_paket" step="0.01" required placeholder="150000"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Foto Paket</label>
                            <input type="file" name="foto" accept="image/*"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" placeholder="Rincian barang/isi paket..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"></textarea>
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

        <!-- MODAL EDIT PAKET -->
        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openEdit = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Paket Sewa</h3>
                <form :action="'/paket/' + editId" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Kode Paket</label>
                            <input type="text" name="kode_paket" x-model="editKode" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Nama Paket</label>
                            <input type="text" name="nama_paket" x-model="editNama" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Harga Paket (Rp)</label>
                                <input type="number" name="harga_paket" step="0.01" x-model="editHarga" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" x-model="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Ganti Foto Paket (Opsional)</label>
                            <template x-if="editFotoPreview">
                                <div class="mb-2">
                                    <img :src="editFotoPreview" class="w-16 h-16 object-cover rounded border">
                                </div>
                            </template>
                            <input type="file" name="foto" accept="image/*"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editDeskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"></textarea>
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

    <!-- SCRIPT SWEETALERT2 TOAST & KONFIRMASI -->
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
                text: "Yakin ingin menghapus paket sewa ini?",
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