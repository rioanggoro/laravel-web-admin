<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    use HasFactory;
    protected $table = 'faktur';
    protected $guarded = [];

    // Jika ada relasi dengan model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'nama_barang', 'id');
    }
}
