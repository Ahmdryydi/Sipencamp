<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPENCAMP - Masuk / Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-100 p-8">

        <!-- Header Branding & Tombol Kembali -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('katalog.index') }}" class="text-xs text-emerald-800 font-semibold hover:underline flex items-center gap-1">
                ← Kembali ke Katalog
            </a>
            <span class="text-emerald-800 font-bold text-lg flex items-center gap-1">
                <span class="text-emerald-500">✳</span> SIPENCAMP
            </span>
        </div>

        <!-- Banner Indikator Role Login -->
        <div class="mb-6 p-3 rounded-2xl flex items-center gap-3 {{ $role === 'admin' ? 'bg-amber-50 border border-amber-200' : 'bg-emerald-50 border border-emerald-200' }}">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold {{ $role === 'admin' ? 'bg-amber-200 text-amber-900' : 'bg-emerald-200 text-emerald-900' }}">
                {{ $role === 'admin' ? '🛠️' : '⛺' }}
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Mode Akses Login:</p>
                <p class="text-sm font-bold {{ $role === 'admin' ? 'text-amber-900' : 'text-emerald-900' }}">
                    {{ $role === 'admin' ? 'Administrator / Pengelola' : 'Penyewa / Pelanggan' }}
                </p>
            </div>
        </div>

        <h2 class="text-2xl font-extrabold text-gray-800 mb-1">Selamat Datang Kembali</h2>
        <p class="text-xs text-gray-500 mb-6">Masukkan username dan kata sandi Anda untuk melanjutkan.</p>

        <!-- Pesan Error Validation -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.proses') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                       placeholder="Masukkan username Anda"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Password</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 transition">
            </div>

            <button type="submit"
                    class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-bold py-3.5 rounded-xl text-sm shadow-md transition">
                Masuk Sekarang
            </button>
        </form>

        <!-- Switcher Mode Akses -->
        <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs text-gray-500">
            @if($role === 'admin')
                Bukan Administrator? <a href="{{ route('login') }}?role=customer" class="text-emerald-800 font-bold hover:underline">Login sebagai Penyewa</a>
            @else
                Pengelola Sistem? <a href="{{ route('login') }}?role=admin" class="text-emerald-800 font-bold hover:underline">Login Administrator</a>
            @endif
        </div>

    </div>

</body>
</html>
