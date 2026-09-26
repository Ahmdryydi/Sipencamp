<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Mpenyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaan';

    protected $fillable = [
        'kode_penyewaan',
        'user_id',
        'tanggal_sewa',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'total_biaya',
        'denda',
        'catatan',
    ];

    // Relasi ke User / Penyewa
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Detail Penyewaan
    // public function details()
    // {
    //     return $this->hasMany(MpenyewaanDetail::class, 'penyewaan_id');
    // }
}