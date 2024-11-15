<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('barang.index');
    }

    /**
     * Retrieve data of all Barang entries.
     */
    public function getDataBarang()
    {
        $barangs = Barang::select('id', 'nama_barang', 'stok', 'ukuran')->get();
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
        $ukuran = ['pcs', 'kg', 'liter', 'meter'];
        return view('barang.create', compact('ukuran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateBarang($request);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data!'
            ], 500);
        }
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
    public function edit($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Edit Data Barang',
            'data'    => $barang
        ]);
    }


    public function update(Request $request)
    {
        $barang_id = $request->input('id');
        $barang = Barang::find($barang_id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'ukuran'      => 'required|string|max:11',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data!'
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Barang Berhasil Dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data!'
            ], 500);
        }
    }

    /**
     * Validasi input untuk Barang.
     */
    private function validateBarang($request)
    {
        return Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'required|integer|min:0', // Cek stok tidak negatif
            'ukuran'      => 'required|string|max:11'
        ], [
            'nama_barang.required' => 'Form Nama Barang Wajib Di Isi!',
            'stok.required'        => 'Form Stok Wajib Di Isi!',
            'stok.integer'         => 'Gunakan Angka Untuk Mengisi Form Stok!',
            'stok.min'             => 'Stok tidak boleh negatif!',
            'ukuran.required'      => 'Form Ukuran Wajib Di Isi!'
        ]);
    }
}
