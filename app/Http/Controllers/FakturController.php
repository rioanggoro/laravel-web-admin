<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Faktur;
use App\Services\FakturService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class FakturController
 *
 * Controller untuk mengelola operasi CRUD pada faktur penjualan.
 * Menangani pembuatan, pembacaan, pembaruan, dan penghapusan faktur
 * serta mengatur stok barang yang terkait dengan transaksi.
 *
 * @package App\Http\Controllers
 * @author Your Name <your.email@example.com>
 * @version 1.0.0
 */
class FakturController extends Controller
{
    /**
     * Konstruktor FakturController.
     *
     * Menginisialisasi controller dengan dependencies yang diperlukan
     * melalui Laravel's dependency injection.
     *
     * @param FakturService $fakturService Service layer untuk logika bisnis faktur
     */
    public function __construct(
        private readonly FakturService $fakturService
    ) {
    }

    /**
     * Menampilkan daftar semua faktur.
     *
     * Mengambil seluruh data faktur dan barang dari database
     * untuk ditampilkan dalam view index.
     *
     * @return View|JsonResponse Mengembalikan view dengan data faktur dan barang atau datatable yang akan diminta
     */
    public function index(Request $request): View|JsonResponse
    {
        $fakturs = Faktur::all();
        $barangs = Barang::all();

        if ($request->ajax()) {
            return DataTables::of(source: $fakturs)
                ->addColumn('barang', fn(Faktur $faktur) => $faktur->getAttribute('barang')->toArray())
                ->make(true);
        }

        return view('faktur.index', compact('fakturs', 'barangs'));
    }

    /**
     * Menampilkan form untuk membuat faktur baru.
     *
     * Menyiapkan data barang yang diperlukan untuk dropdown
     * pemilihan barang dalam form pembuatan faktur.
     *
     * @return View Mengembalikan view dengan data barang untuk form
     */
    public function create(): View
    {
        $barangs = Barang::all();
        return view('faktur.create', compact('barangs'));
    }

    /**
     * Menyimpan faktur baru ke database.
     *
     * Memvalidasi input, memeriksa stok barang, membuat faktur baru,
     * dan memperbarui stok barang terkait.
     *
     * @param Request $request Request dengan data faktur yang akan disimpan
     * @return JsonResponse Response JSON dengan status operasi dan data faktur
     *
     * @throws \Exception Jika terjadi kesalahan dalam pembuatan faktur
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nomor_faktur' => 'required|integer',
            'kode_faktur' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_barang' => 'required|exists:barangs,id',
            'banyak' => 'required|integer|min:1',
            'ukuran' => 'required|string|max:11',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        try {
            $barang = Barang::findOrFail($request->nama_barang);

            if ($barang->stok < $request->banyak) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok barang tidak mencukupi'
                ], 400);
            }

            $jumlah = $request->banyak * $request->harga_satuan;

            $faktur = Faktur::create([
                'nomor_faktur' => $request->nomor_faktur,
                'kode_faktur' => $request->kode_faktur,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'nama_barang' => $request->nama_barang,
                'banyak' => $request->banyak,
                'ukuran' => $request->ukuran,
                'harga_satuan' => $request->harga_satuan,
                'jumlah' => $jumlah
            ]);

            $barang->stok -= $request->banyak;
            $barang->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $faktur
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat faktur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail faktur beserta data barang terkait.
     *
     * @param int $id ID faktur yang akan ditampilkan
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            // Mengambil data faktur beserta relasi barang
            $faktur = Faktur::with('barang')->findOrFail($id);

            // Menghitung total faktur
            $total = $faktur->banyak * $faktur->harga_satuan;

            // Jika request adalah AJAX, kembalikan response JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'faktur' => $faktur,
                        'total' => $total,
                        'barang' => $faktur->barang
                    ]
                ]);
            }

            // Jika bukan AJAX, tampilkan view
            return view('faktur.show', compact('faktur', 'total'));

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Faktur tidak ditemukan atau terjadi kesalahan: ' . $e->getMessage()
                ], 404);
            }

            return redirect()
                ->route('faktur.index')
                ->with('error', 'Faktur tidak ditemukan atau terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan data faktur untuk keperluan edit.
     *
     * Mengambil data faktur beserta relasi barang terkait
     * dan mengembalikannya dalam format JSON.
     *
     * @param int $id ID faktur yang akan diedit
     * @return JsonResponse Response JSON dengan data faktur
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika faktur tidak ditemukan
     */
    public function edit(int $id): JsonResponse
    {
        $faktur = Faktur::with('barang')->findOrFail($id);

        return response()->json([
            'nomor_faktur' => $faktur->nomor_faktur,
            'kode_faktur' => $faktur->kode_faktur,
            'barang_id' => $faktur->barang->id,
            'jumlah_barang' => $faktur->jumlah_barang,
            'harga_satuan' => $faktur->harga_satuan,
        ]);
    }

    /**
     * Memperbarui data faktur yang ada di database.
     *
     * Memvalidasi input, memeriksa ketersediaan stok,
     * memperbarui faktur dan stok barang terkait.
     *
     * @param Request $request Request dengan data faktur yang diperbarui
     * @param int $id ID faktur yang akan diperbarui
     * @return RedirectResponse Redirect ke halaman yang sesuai dengan pesan status
     *
     * @throws \Exception Jika terjadi kesalahan dalam pembaruan faktur
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'nomor_faktur' => 'required|string|max:10',
            'kode_faktur' => 'required|string|max:10',
            'nama_barang' => 'required|exists:barangs,id',
            'banyak' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        try {
            $faktur = Faktur::findOrFail($id);
            $barang = Barang::findOrFail($request->barang_id);

            $stokLama = $faktur->jumlah_barang;
            $stokBaru = $request->jumlah_barang;

            if ($stokBaru > $stokLama && $barang->stok < ($stokBaru - $stokLama)) {
                return redirect()->back()->with('error', 'Stok barang tidak mencukupi.');
            }

            $barang->stok -= ($stokBaru - $stokLama);
            $barang->save();

            $this->fakturService->updateFaktur($id, $request->all());

            return redirect()->route('faktur.index')
                ->with('success', 'Faktur berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui faktur: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus faktur dari database.
     *
     * Menggunakan FakturService untuk menghapus faktur
     * dan mengembalikan stok barang jika diperlukan.
     *
     * @param int $id ID faktur yang akan dihapus
     * @return RedirectResponse Redirect ke halaman yang sesuai dengan pesan status
     *
     * @throws \Exception Jika terjadi kesalahan dalam penghapusan faktur
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->fakturService->deleteFaktur($id);
            return redirect()->route('faktur.index')
                ->with('success', 'Faktur berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus faktur: ' . $e->getMessage());
        }
    }
}
