<?php

namespace App\Services;

use App\Models\Faktur;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class FakturService
{
    public function createFaktur($data)
    {
        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($data['nama_barang']);

            if ($barang->stok < $data['banyak']) {
                throw new \Exception("Stok barang tidak mencukupi.");
            }

            // Kurangi stok
            $barang->stok -= $data['banyak'];
            $barang->save();

            // Buat faktur
            $faktur = Faktur::create([
                'nomor_faktur' => $data['nomor_faktur'],
                'kode_faktur' => $data['kode_faktur'],
                'nama_barang' => $barang->id,
                'banyak' => $data['banyak'],
                'harga_satuan' => $data['harga_satuan'],
                'jumlah' => $data['banyak'] * $data['harga_satuan'],
            ]);

            DB::commit();
            return $faktur;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateFaktur($id, $data)
    {
        DB::beginTransaction();
        try {
            $faktur = Faktur::findOrFail($id);
            $barang = Barang::findOrFail($data['nama_barang']);

            // Mengembalikan stok barang yang lama
            $oldBarang = Barang::findOrFail($faktur->nama_barang);

            $stokLama = (int) $faktur->banyak;
            $stokBaru = (int) $data['banyak'];

            if ($stokLama !== $stokBaru) {
                $oldBarang->stok += $faktur->banyak;
                $oldBarang->save();

                // Cek stok barang yang baru
                if ($barang->stok < $data['banyak']) {
                    throw new \Exception("Stok barang tidak mencukupi.");
                }

                // Kurangi stok barang baru
                $barang->stok -= $data['banyak'];
                $barang->save();
            }


            // Update faktur
            $faktur->update([
                'nomor_faktur' => $data['nomor_faktur'],
                'kode_faktur' => $data['kode_faktur'],
                'nama_barang' => $barang->id,
                'banyak' => $data['banyak'],
                'harga_satuan' => $data['harga_satuan'],
                'jumlah' => $data['banyak'] * $data['harga_satuan'],
            ]);

            DB::commit();
            return ['success' => true, 'Berhasil Mengupdate Faktur'];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteFaktur($id)
    {
        DB::beginTransaction();
        try {
            $faktur = Faktur::findOrFail($id);
            $barang = Barang::findOrFail($faktur->nama_barang);

            // Kembalikan stok barang
            $barang->stok += $faktur->banyak;
            $barang->save();

            $faktur->delete();
            DB::commit();

            return ['success' => true, 'message' => 'Berhasil Menghapus Faktur'];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
