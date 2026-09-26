<?php

namespace App\Http\Controllers;

use App\Models\Mkategori;
use Illuminate\Http\Request;

class Ckategori extends Controller
{
    public function index()
    {
        $kategori = Mkategori::latest()->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
        ]);

        Mkategori::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
        ]);

        $kategori = Mkategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->back()->with('update', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Mkategori::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('delete', 'Kategori berhasil dihapus!');
    }
}