<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    use HasFactory;
    protected $table = 'faktur';

    protected $fillable = [
        'nomor_faktur', // int
        'kode_faktur', // varchar(10)
        'created_at', // date
        'nama', // varchar(255)
        'alamat', // text
        'banyak', // int
        'nama_barang', // bigint
        'ukuran', // int
        'harga_satuan', // decimal(10,0)
        'jumlah', // decimal(10,0)
    ];

    // Jika ada relasi dengan model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'nama_barang', 'id');
    }
}
