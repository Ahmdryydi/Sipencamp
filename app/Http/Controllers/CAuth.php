<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Cauth extends Controller
{
    /**
     * Menampilkan Form Login (Dapat mendeteksi parameter ?role=customer atau ?role=admin)
     */
    public function index(Request $request)
    {
        $role = $request->query('role', 'customer');
        return view('auth.login', compact('role'));
    }

    /**
     * Proses Verifikasi Login
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pengecekan Level/Role User setelah Login
            if ($user->level === 'admin' || $user->role === 'admin') {
                // Pengalihan langsung ke route/URL dashboard admin
                return redirect('/dashboard')->with('success', 'Selamat datang di Panel Admin SIPENCAMP.');
            }

            // Pengalihan langsung ke halaman utama katalog/pelanggan
            return redirect('/')->with('success', 'Berhasil login! Selamat berbelanja/menyewa.');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
