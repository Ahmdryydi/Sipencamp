<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mperalatan extends Model
{
    use HasFactory;

    protected $table = 'peralatan';

    protected $guarded = ['id'];

    // Relasi ke tabel kategori
    public function kategori()
    {
        return $this->belongsTo(Mkategori::class, 'id_kategori');
    }
}
