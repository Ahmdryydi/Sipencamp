<?php

namespace App\Http\Controllers;

use App\Models\Mperalatan;
use App\Models\Mkategori;
use Illuminate\Http\Request;

class Cperalatan extends Controller
{
    public function index()
    {
        $peralatan = Mperalatan::with('kategori')->get();
        $kategori = Mkategori::all();
        return view('admin.peralatan.index', compact('peralatan', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peralatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        Mperalatan::create([
            'nama_peralatan' => $request->nama_peralatan,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Peralatan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_peralatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $peralatan = Mperalatan::findOrFail($id);
        $peralatan->update([
            'nama_peralatan' => $request->nama_peralatan,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('update', 'Peralatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peralatan = Mperalatan::findOrFail($id);
        $peralatan->delete();

        return redirect()->back()->with('delete', 'Peralatan berhasil dihapus!');
    }
}