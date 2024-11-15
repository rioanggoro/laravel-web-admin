<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    protected $table = 'faktur';
    protected $fillable = ['nomor_faktur', 'kode_faktur', 'nama', 'alamat', 'banyak', 'nama_barang', 'ukuran', 'harga_satuan', 'jumlah'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'nama_barang');
    }
}
