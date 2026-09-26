<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpaket extends Model
{
    use HasFactory;

    protected $table = 'paket';

    protected $guarded = ['id'];

    protected $fillable = [
        'kode_paket',
        'nama_paket',
        'deskripsi',
        'harga_paket',
        'foto',
        'status',
    ];
}