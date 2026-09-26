<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpenyewaanDetail extends Model
{
    use HasFactory;

    protected $table = 'penyewaan_detail';

    protected $fillable = [
        'penyewaan_id',
        'peralatan_id',
        'paket_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function penyewaan()
    {
        return $this->belongsTo(Mpenyewaan::class, 'penyewaan_id');
    }

    public function peralatan()
    {
        return $this->belongsTo(Mperalatan::class, 'peralatan_id');
    }

    public function paket()
    {
        return $this->belongsTo(Mpaket::class, 'paket_id');
    }
}