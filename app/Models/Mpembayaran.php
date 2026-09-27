<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'penyewaan_id',
        'kode_pembayaran',
        'metode_pembayaran',
        'jumlah_bayar',
        'bukti_bayar',
        'status',
        'tanggal_bayar',
    ];

    // Relasi balik ke Penyewaan
    public function penyewaan()
    {
        return $this->belongsTo(Mpenyewaan::class, 'penyewaan_id');
    }
}
