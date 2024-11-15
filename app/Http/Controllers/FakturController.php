<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Faktur;
use App\Services\FakturService;
use Illuminate\Http\Request;

class FakturController extends Controller
{
    protected $fakturService;

    public function __construct(FakturService $fakturService)
    {
        $this->fakturService = $fakturService;
    }

    // Menampilkan daftar faktur
    public function index()
    {
        $faktur = Faktur::all();
        return view('faktur.index', compact('faktur'));
    }

    // Menampilkan form pembuatan faktur
    public function create()
    {
        $barangs = Barang::all();
        dd($barangs); // Debugging
        return view('faktur.create', compact('barangs'));
    }

    // Menyimpan faktur baru
    public function store(Request $request)
    {
        $request->validate([
            'nomor_faktur' => 'required|string|max:10',
            'kode_faktur' => 'required|string|max:10',
            'nama_barang' => 'required|exists:barangs,id',
            'banyak' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric',
        ]);

        try {
            // Temukan barang yang dipilih
            $barang = Barang::findOrFail($request->nama_barang);

            // Cek ketersediaan stok
            if ($barang->stok < $request->banyak) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi.');
            }

            // Kurangi stok barang
            $barang->stok -= $request->banyak;
            $barang->save();

            // Panggil `createFaktur` dari service
            $this->fakturService->createFaktur($request->all());

            return redirect()->route('faktur.index')->with('success', 'Faktur berhasil dibuat dan stok berkurang.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat faktur: ' . $e->getMessage());
        }
    }


    // Menampilkan form edit faktur
    public function edit($id)
    {
        $faktur = Faktur::findOrFail($id);
        $barangs = Barang::all();
        return view('faktur.edit', compact('faktur', 'barangs'));
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
