<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $fillable = ['nama_barang', 'stok', 'ukuran'];

    // Relasi ke Faktur
    public function fakturs()
    {
        return $this->hasMany(Faktur::class, 'nama_barang');
    }

    // Relasi ke BarangMasuk
    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'nama_barang');
    }

    // Relasi ke BarangKeluar
    public function barangKeluars()
    {
        return $this->hasMany(BarangKeluar::class, 'nama_barang');
    }
}
