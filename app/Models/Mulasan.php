<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'user_id',
        'peralatan_id',
        'paket_id',
        'rating',
        'komentar',
    ];

    // Relasi ke User / Customer
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Peralatan (jika ulasan untuk produk peralatan)
    public function peralatan()
    {
        return $this->belongsTo(Mperalatan::class, 'peralatan_id');
    }

    // Relasi ke Paket (jika ulasan untuk paket sewa)
    public function paket()
    {
        return $this->belongsTo(Mpaket::class, 'paket_id');
    }
}