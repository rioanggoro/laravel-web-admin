<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuks';
    protected $fillable = [
        'kode_transaksi',
        'tanggal_masuk',
        'nama_barang',
        'jumlah_masuk',
        'supplier_id',
        'user_id'
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'nama_barang');
    }

    // Relasi ke Supplier (pastikan ada model Supplier)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
