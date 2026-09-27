<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaktivitasPengguna extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_pengguna';
    
    // Matikan updated_at karena tabel hanya memiliki kolom created_at
    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'peralatan_id',
        'paket_id',
        'jenis_aktivitas',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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