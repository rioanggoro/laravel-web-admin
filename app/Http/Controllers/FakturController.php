<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Faktur;
use App\Services\FakturService;
use Illuminate\Http\Request;

class FakturController extends Controller
{
    protected $fakturService;

    /*************  ✨ Codeium Command ⭐  *************/
    /******  36db921c-3b4f-4855-8d9b-dd78bb7ff0a2  *******/
    public function __construct(FakturService $fakturService)
    {
        $this->fakturService = $fakturService;
    }

    // Menampilkan daftar faktur
    public function index()
    {
        $faktur = Faktur::all();
        $barangs = Barang::all(); // Tambahkan ini untuk mengirim data barang
        return view('faktur.index', compact('faktur', 'barangs'));
    }


    // Menampilkan form pembuatan faktur
    public function create()
    {
        $barangs = Barang::all();
        return view('faktur.create', compact('barangs'));
    }

    // Menyimpan faktur baru
    public function store(Request $request)
    {
        $request->validate([
            'nomor_faktur' => 'required|integer',
            'kode_faktur' => 'required|string|max:10',
            'nama_barang' => 'required|exists:barangs,id',
            'banyak' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric',
        ]);

        try {
            // Kurangi stok barang dan buat faktur baru
            $barang = Barang::findOrFail($request->nama_barang);

            if ($barang->stok < $request->banyak) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi.');
            }

            $barang->stok -= $request->banyak;
            $barang->save();

            Faktur::create([
                'nomor_faktur' => $request->nomor_faktur,
                'kode_faktur' => $request->kode_faktur,
                'nama_barang' => $request->nama_barang,
                'banyak' => $request->banyak,
                'harga_satuan' => $request->harga_satuan,
                'jumlah' => $request->banyak * $request->harga_satuan,
            ]);

            return redirect()->route('faktur.index')->with('success', 'Faktur berhasil dibuat dan stok berkurang.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat faktur: ' . $e->getMessage());
        }
    }




    // Menampilkan form edit faktur
    public function edit($id)
    {
        $faktur = Faktur::with('barang')->findOrFail($id); // Pastikan barang di-load bersama faktur

        return response()->json([
            'nomor_faktur' => $faktur->nomor_faktur,
            'kode_faktur' => $faktur->kode_faktur,
            'nama_barang' => $faktur->barang->id, // atau $faktur->nama_barang jika kolom id barang ada di tabel faktur
            'banyak' => $faktur->banyak,
            'harga_satuan' => $faktur->harga_satuan,
        ]);
    }


    // Mengupdate data faktur
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_faktur' => 'required|string|max:10',
            'kode_faktur' => 'required|string|max:10',
            'nama_barang' => 'required|exists:barangs,id',
            'banyak' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric',
        ]);

        try {
            $faktur = Faktur::findOrFail($id);
            $barang = Barang::findOrFail($request->nama_barang);

            // Periksa apakah jumlah faktur berubah dan sesuaikan stok
            $stokLama = $faktur->banyak;
            $stokBaru = $request->banyak;

            if ($stokBaru > $stokLama && $barang->stok < ($stokBaru - $stokLama)) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk perubahan.');
            }

            // Update stok barang sesuai perubahan pada faktur
            $barang->stok = $barang->stok - ($stokBaru - $stokLama);
            $barang->save();

            // Panggil updateFaktur
            $this->fakturService->updateFaktur($id, $request->all());

            return redirect()->route('faktur.index')->with('success', 'Faktur berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate faktur: ' . $e->getMessage());
        }
    }

    // Menghapus faktur
    public function destroy($id)
    {
        try {
            $this->fakturService->deleteFaktur($id);
            return redirect()->route('faktur.index')->with('success', 'Faktur berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus faktur: ' . $e->getMessage());
        }
    }
}
