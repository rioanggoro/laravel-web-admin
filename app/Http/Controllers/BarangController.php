<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('barang.index', [
            'barangs' => Barang::all()
        ]);
    }

    /**
     * Retrieve data of all Barang entries.
     */
    public function getDataBarang()
    {
        $barangs = Barang::all();

        return response()->json([
            'success' => true,
            'data'    => $barangs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Definisikan pilihan satuan secara manual
        $ukuran = ['pcs', 'kg', 'liter', 'meter']; // Tambahkan satuan lain sesuai kebutuhan

        // Kirimkan ke view
        return view('barang.create', compact('ukuran'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'required|integer',
            'ukuran'      => 'required|string|max:11' // Sesuaikan panjang max dengan struktur tabel
        ], [
            'nama_barang.required' => 'Form Nama Barang Wajib Di Isi!',
            'stok.required'        => 'Form Stok Wajib Di Isi!',
            'stok.integer'         => 'Gunakan Angka Untuk Mengisi Form Stok!',
            'ukuran.required'      => 'Form Ukuran Wajib Di Isi!'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok,
            'ukuran'      => $request->ukuran,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $barang
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Barang',
            'data'    => $barang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        return response()->json([
            'success' => true,
            'message' => 'Edit Data Barang',
            'data'    => $barang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'required|integer',
            'ukuran'      => 'required|string|max:11' // Sesuaikan panjang max dengan struktur tabel
        ], [
            'nama_barang.required' => 'Form Nama Barang Wajib Di Isi!',
            'stok.required'        => 'Form Stok Wajib Di Isi!',
            'stok.integer'         => 'Gunakan Angka Untuk Mengisi Form Stok!',
            'ukuran.required'      => 'Form Ukuran Wajib Di Isi!'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok,
            'ukuran'      => $request->ukuran,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Terupdate',
            'data'    => $barang
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Barang Berhasil Dihapus!'
        ]);
    }
}
