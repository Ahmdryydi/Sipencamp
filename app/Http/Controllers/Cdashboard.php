<?php

namespace App\Http\Controllers;

use App\Models\Mpenyewaan;
use App\Models\Mperalatan;
use Illuminate\Http\Request;

class Cdashboard extends Controller
{
    public function index()
    {
        $total = Mpenyewaan::where('status', 'dikembalikan')->sum('total_biaya');

        // Mengambil jumlah peralatan yang sedang disewa
        $alatTersewa = Mpenyewaan::where('status', 'disewa')->count();

        return view('admin.dashboard.index', compact('total', 'alatTersewa'));
    }
}