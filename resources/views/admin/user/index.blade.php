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
        openSettings: false, // State Modal Pengaturan

        // State Detail
        detailNama: '',
        detailDeskripsi: '',

        // State Edit
        editId: null, 
        editNama: '', 
        editDeskripsi: '' 
    }">

        <!-- Page Header -->
        <div class="page-header flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kategori Alat Camping</h1>
                <p class="text-gray-500">Kelola kategori peralatan camping yang tersedia.</p>
            </div>
            <!-- Tombol Pengaturan Utama -->
            <div>
                <button @click="openSettings = true" type="button"
                    class="px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 active:scale-95 transition-all shadow-sm cursor-pointer inline-flex items-center gap-2">
                    <i class="bi bi-gear text-lg"></i>
                    <span>Pengaturan</span>
                </button>
            </div>
        </div>

        <!-- Table Container Card -->
        <div class="table-card-custom">
            <!-- Header Controls -->
            <div class="table-header-control flex justify-between items-center p-4">
                <!-- Search bar -->
                <div class="table-search-box">
                    <i class="bi bi-search table-search-icon"></i>
                    <input type="text" class="table-search-input pl-9 pr-4 py-2 border rounded-lg "
                        placeholder="Cari Kategori...">
                </div>
                <!-- Action buttons / Filter options -->
                <div class="table-filter-group flex items-center gap-2">
                    <!-- Tombol Tambah Kategori -->
                    <button @click="openCreate = true" type="button" title="Tambah Kategori"
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
                            <th class="px-4 py-3">Nama Kategori</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($kategori as $index => $item)
                            <tr>
                                <!-- Nomor Urut -->
                                <td class="text-center">{{ $index + 1 }}</td>

                                <!-- Nama Kategori -->
                                <td class="font-bold">{{ $item->nama_kategori }}</td>

                                <!-- Deskripsi -->
                                <td>{{ $item->deskripsi ?? '-' }}</td>

                                <!-- Aksi / Tombol -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        <!-- Tombol Detail -->
                                        <button type="button"
                                            class="p-1.5 text-gray-600 hover:text-emerald-600 hover:bg-gray-100 rounded-lg transition"
                                            title="Detail Kategori" @click="
                                                openDetail = true;
                                                detailNama = '{{ addslashes($item->nama_kategori) }}';
                                                detailDeskripsi = '{{ addslashes($item->deskripsi ?? '-') }}';
                                            ">
                                            <i class="bi bi-eye text-lg"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button type="button"
                                            class="p-1.5 text-gray-600 hover:text-amber-600 hover:bg-gray-100 rounded-lg transition"
                                            title="Edit Kategori" @click="
                                                openEdit = true; 
                                                editId = '{{ $item->id }}'; 
                                                editNama = '{{ addslashes($item->nama_kategori) }}'; 
                                                editDeskripsi = '{{ addslashes($item->deskripsi ?? '') }}';
                                            ">
                                            <i class="bi bi-pencil text-lg"></i>
                                        </button>

                                        <!-- Form & Tombol Hapus -->
                                        <form id="form-delete-{{ $item->id }}"
                                            action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="konfirmasiHapus('{{ $item->id }}')"
                                                class="p-1.5 text-gray-600 hover:text-rose-600 hover:bg-gray-100 rounded-lg transition"
                                                title="Hapus Kategori">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data kategori.</td>
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

        <!-- MODAL PENGATURAN (POP-UP PILIHAN) -->
        <div x-show="openSettings" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openSettings = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-800">Menu Pengaturan</h3>
                    <button @click="openSettings = false" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>
                
                <div class="space-y-3">
                    <!-- Opsi Pengaturan Akun -->
                    <a href="{{ route('profile.edit') }}" 
                        class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition group">
                        <div class="p-2.5 bg-emerald-100 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition">
                            <i class="bi bi-person-gear text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 text-sm group-hover:text-emerald-600 transition">Pengaturan Akun</h4>
                            <p class="text-xs text-gray-500">Kelola profil, email, dan kata sandi Anda</p>
                        </div>
                    </a>

                    <!-- Opsi Data User / Hak Akses -->
                    <a href="{{ route('users.index') }}" 
                        class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition group">
                        <div class="p-2.5 bg-blue-100 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition">
                            <i class="bi bi-people text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 text-sm group-hover:text-emerald-600 transition">Manajemen Data User</h4>
                            <p class="text-xs text-gray-500">Kelola pengguna sistem dan hak akses</p>
                        </div>
                    </a>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" @click="openSettings = false"
                        class="px-4 py-2 font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL DETAIL KATEGORI -->
        <div x-show="openDetail" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openDetail = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Kategori</h3>
                <div class="space-y-3 ">
                    <div>
                        <label class="font-medium text-gray-500">Nama Kategori</label>
                        <p class="font-semibold text-gray-800 text-base" x-text="detailNama"></p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200 mt-1" x-text="detailDeskripsi"></p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="openDetail = false"
                        class="px-4 py-2 font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH KATEGORI -->
        <div x-show="openCreate" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openCreate = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Kategori Baru</h3>
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition ">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition "></textarea>
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

        <!-- MODAL EDIT KATEGORI -->
        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="openEdit = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Kategori</h3>
                <form :action="'/kategori/' + editId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" name="nama_kategori" x-model="editNama" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition ">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editDeskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition "></textarea>
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
                text: "Yakin ingin menghapus kategori ini?",
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