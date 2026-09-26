<?php

namespace App\Http\Controllers;

use App\Models\Mpaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Cpaket extends Controller
{
    public function index()
    {
        $pakets = Mpaket::latest()->get();
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        return view('admin.paket.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_paket'  => 'required|unique:paket,kode_paket',
            'nama_paket'  => 'required|string|max:150',
            'deskripsi'   => 'nullable|string',
            'harga_paket' => 'required|numeric',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('paket', 'public');
        }

        Mpaket::create($data);

        return redirect()->route('paket.index')->with('success', 'Paket sewa berhasil ditambahkan.');
    }

    public function edit(Mpaket $paket)
    {
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, Mpaket $paket)
    {
        $request->validate([
            'kode_paket'  => 'required|unique:paket,kode_paket,' . $paket->id,
            'nama_paket'  => 'required|string|max:150',
            'deskripsi'   => 'nullable|string',
            'harga_paket' => 'required|numeric',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($paket->foto) {
                Storage::disk('public')->delete($paket->foto);
            }
            $data['foto'] = $request->file('foto')->store('paket', 'public');
        }

        $paket->update($data);

        return redirect()->route('paket.index')->with('success', 'Paket sewa berhasil diperbarui.');
    }

    public function destroy(Mpaket $paket)
    {
        if ($paket->foto) {
            Storage::disk('public')->delete($paket->foto);
        }
        $paket->delete();

        return redirect()->route('paket.index')->with('success', 'Paket sewa berhasil dihapus.');
    }
}