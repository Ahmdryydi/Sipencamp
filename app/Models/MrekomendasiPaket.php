<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MrekomendasiPaket extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi_paket';

    protected $fillable = [
        'user_id',
        'paket_id',
        'skor',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paket()
    {
        return $this->belongsTo(Mpaket::class, 'paket_id');
    }
}