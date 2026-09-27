<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPENCAMP - Katalog Penyewaan Alat Camping</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk Dropdown Pilihan Login -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Header / Navbar Topbar -->
    <nav class="bg-white border-b border-gray-100 px-6 py-4 sticky top-0 z-50 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-8">
            <!-- Logo Brand -->
            <a href="{{ route('katalog.index') }}" class="flex items-center gap-2 font-bold text-xl text-emerald-950">
                <span class="text-emerald-500 text-2xl">✳</span> SIPENCAMP
            </a>

            <!-- Kolom Pencarian -->
            <form action="{{ route('katalog.index') }}" method="GET" class="relative w-80 hidden md:block">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tenda, carrier, kompor..."
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm pl-10 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>

        <!-- Tombol Aksi Kanan / Pilihan Login -->
        <div class="flex items-center gap-4">

            @auth
                <!-- Jika User Sudah Login -->
                <div class="flex items-center gap-3 border-l pl-4 border-gray-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-gray-400">Selamat datang,</p>
                        <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                    </div>

                    @if(Auth::user()->level === 'admin')
                        <a href="{{ route('dashboard') }}" class="bg-emerald-800 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-emerald-900 transition flex items-center gap-1">
                            Panel Admin
                        </a>
                    @else
                        <a href="#" class="bg-emerald-800 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-emerald-900 transition">
                            Sewa Saya
                        </a>
                    @endif
                </div>
            @else
                <!-- Dropdown Pilihan Login jika Belum Login -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="bg-emerald-800 hover:bg-emerald-900 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition flex items-center gap-2 shadow-sm">
                        <span>Masuk / Login</span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Menu Dropdown -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 z-50">

                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Masuk Sebagai</p>
                        </div>

                        <!-- Opsi 1: Penyewa / User -->
                        <a href="{{ route('login') }}?role=customer"
                           class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-900 transition">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                                ⛺
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Penyewa / User</p>
                                <p class="text-[11px] text-gray-400">Untuk sewa alat camping</p>
                            </div>
                        </a>

                        <!-- Opsi 2: Administrator -->
                        <a href="{{ route('login') }}?role=admin"
                           class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-900 transition">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-800 flex items-center justify-center font-bold text-xs">
                                🛠️
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Administrator</p>
                                <p class="text-[11px] text-gray-400">Kelola toko & transaksi</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endauth

        </div>
    </nav>

    <!-- Content Area (Katalog Olshop) -->
    <main class="max-w-7xl mx-auto px-6 py-8">

        <!-- Banner Promosi -->
        <div class="mb-8 bg-gradient-to-r from-emerald-900 to-emerald-700 rounded-3xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
            <div class="relative z-10 max-w-xl">
                <span class="bg-emerald-600/60 text-emerald-100 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-400/30">Penyewaan Alat Outdoor Terbaik</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold mt-3 mb-2">Sewa Peralatan Camping Mudah & Lengkap</h1>
                <p class="text-emerald-100 text-xs sm:text-sm">Pilih peralatan atau paket sewa hemat untuk petualangan camping Anda berikutnya.</p>
            </div>
        </div>

        <!-- Filter Cepat Kategori -->
        <div class="flex items-center justify-between mb-8 overflow-x-auto pb-2">
            <div class="flex gap-2">
                <a href="{{ route('katalog.index') }}"
                   class="px-5 py-2.5 rounded-xl font-medium text-sm whitespace-nowrap {{ !request('kategori_id') ? 'bg-emerald-900 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                   Semua Peralatan
                </a>
                @foreach($kategoriList as $kat)
                    <a href="{{ route('katalog.index', ['kategori_id' => $kat->id]) }}"
                       class="px-5 py-2.5 rounded-xl font-medium text-sm whitespace-nowrap {{ request('kategori_id') == $kat->id ? 'bg-emerald-900 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                       {{ $kat->nama_kategori }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION REKOMENDASI AI (DARI MODEL/DATABASE) -->
        <!-- ========================================== -->
        @if(!request('kategori_id') && !request('search'))
            <div class="mb-10 bg-emerald-50/70 border border-emerald-200/80 rounded-3xl p-6 sm:p-7 relative overflow-hidden shadow-sm">

                <!-- Badge Header AI -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-800 text-white flex items-center justify-center font-bold text-base shadow-sm">
                            ✨
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-emerald-950">Rekomendasi AI SIPENCAMP</h2>
                            <p class="text-xs text-emerald-700">Pilihan paket camping populer dan terbaik berdasarkan preferensi pengguna.</p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-block bg-emerald-200/60 text-emerald-900 text-xs font-bold px-3 py-1 rounded-full border border-emerald-300">
                        AI Powered
                    </span>
                </div>

                <!-- Cards Rekomendasi AI -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if($rekomendasiAI && $rekomendasiAI->count() > 0)
                        @foreach($rekomendasiAI as $rek)
                            @if($rek->paket)
                                <div class="bg-white border border-emerald-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="relative w-full h-40 bg-gray-100 rounded-xl mb-3 overflow-hidden">
                                            <img src="{{ $rek->paket->foto ? asset('storage/'.$rek->paket->foto) : 'https://via.placeholder.com/300?text=Rekomendasi+AI' }}" class="w-full h-full object-cover">
                                            <span class="absolute top-2 left-2 bg-emerald-800 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 shadow">
                                                ✨ Rekomendasi AI
                                            </span>
                                        </div>
                                        <h3 class="font-bold text-gray-800 text-base mb-1">{{ $rek->paket->nama_paket }}</h3>
                                        <p class="text-gray-500 text-xs mb-3 line-clamp-2">{{ $rek->paket->deskripsi ?? 'Paket rekomendasi AI sesuai kebutuhan camping Anda.' }}</p>
                                    </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-2">
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Harga Sewa</span>
                                            <span class="text-emerald-700 font-bold text-base">Rp {{ number_format($rek->paket->harga_paket, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">/hari</span></span>
                                        </div>
                                        <a href="{{ route('katalog.detail_paket', $rek->paket->id) }}" class="bg-emerald-800 hover:bg-emerald-900 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                                            Sewa Paket
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <!-- Tampilan Default Jika Belum Login / Belum Ada Data Rekomendasi Khusus -->
                        @foreach($pakets->take(3) as $pkt)
                            <div class="bg-white border border-emerald-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                <div>
                                    <div class="relative w-full h-40 bg-gray-100 rounded-xl mb-3 overflow-hidden">
                                        <img src="{{ $pkt->foto ? asset('storage/'.$pkt->foto) : 'https://via.placeholder.com/300?text=Rekomendasi+AI' }}" class="w-full h-full object-cover">
                                        <span class="absolute top-2 left-2 bg-emerald-800 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 shadow">
                                            ✨ Rekomendasi AI
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-base mb-1">{{ $pkt->nama_paket }}</h3>
                                    <p class="text-gray-500 text-xs mb-3 line-clamp-2">{{ $pkt->deskripsi ?? 'Paket sewa rekomendasi terfavorit minggu ini.' }}</p>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-2">
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Harga Sewa</span>
                                        <span class="text-emerald-700 font-bold text-base">Rp {{ number_format($pkt->harga_paket, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">/hari</span></span>
                                    </div>
                                    <a href="{{ route('katalog.detail_paket', $pkt->id) }}" class="bg-emerald-800 hover:bg-emerald-900 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                                        Sewa Paket
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        @endif

        <!-- Section 2: Paket Sewa Camping -->
        @if($pakets->count() > 0 && !request('kategori_id'))
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    📦 Paket Hemat Camping
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($pakets as $paket)
                        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="relative w-full h-44 bg-gray-100 rounded-xl mb-3 overflow-hidden">
                                    <img src="{{ $paket->foto ? asset('storage/'.$paket->foto) : 'https://via.placeholder.com/300?text=Paket+Sewa' }}" class="w-full h-full object-cover">
                                    <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">Paket Hemat</span>
                                </div>
                                <h3 class="font-bold text-gray-800 text-base mb-1">{{ $paket->nama_paket }}</h3>
                                <p class="text-gray-500 text-xs mb-4 line-clamp-2">{{ $paket->deskripsi ?? 'Paket perlengkapan camping siap pakai.' }}</p>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-2">
                                <div>
                                    <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Harga Sewa</span>
                                    <span class="text-emerald-700 font-bold text-base">Rp {{ number_format($paket->harga_paket, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">/hari</span></span>
                                </div>
                                <a href="{{ route('katalog.detail_paket', $paket->id) }}" class="bg-emerald-800 hover:bg-emerald-900 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
                                    Detail Paket
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Section 3: Katalog Peralatan Satuan / Eceran -->
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            ⛺ Peralatan Satuan / Eceran
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($peralatan as $item)
                <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="relative w-full h-44 bg-gray-100 rounded-xl mb-3 overflow-hidden">
                            <img src="{{ $item->foto ? asset('storage/'.$item->foto) : 'https://via.placeholder.com/300?text=Peralatan' }}" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-md text-gray-700 text-xs font-medium px-2 py-0.5 rounded-md">
                                {{ $item->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </div>

                        <h3 class="font-bold text-gray-800 text-base mb-1">{{ $item->nama_peralatan }}</h3>
                        <p class="text-gray-500 text-xs mb-4 line-clamp-2">{{ $item->deskripsi ?? 'Peralatan terawat dan siap pakai.' }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-2">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Harga Sewa</span>
                            <span class="text-emerald-700 font-bold text-base">Rp {{ number_format($item->harga_per_hari ?? 0, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">/hari</span></span>
                        </div>
                        <a href="{{ route('katalog.detail_alat', $item->id) }}" class="bg-emerald-800 hover:bg-emerald-900 text-white px-3 py-2 rounded-xl text-sm transition">
                            + Sewa
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-400 text-sm">Tidak ada peralatan yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $peralatan->links() }}
        </div>

    </main>

</body>
</html>
