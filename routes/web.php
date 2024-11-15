<?php

use App\Http\Controllers\ActivityLogController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\LaporanBarangKeluarController;
use App\Http\Controllers\LaporanBarangMasukController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\UbahPasswordController;
use App\Http\Controllers\FakturController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/data-pengguna/get-data', [ManajemenUserController::class, 'getDataPengguna']);
    Route::get('/api/role/', [ManajemenUserController::class, 'getRole']);
    Route::resource('/data-pengguna', ManajemenUserController::class);

    Route::get('/hak-akses/get-data', [HakAksesController::class, 'getDataRole']);
    Route::resource('/hak-akses', HakAksesController::class);

    Route::resource('/aktivitas-user', ActivityLogController::class);

    Route::resource('/dashboard', DashboardController::class);
    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/laporan-stok/get-data', [LaporanStokController::class, 'getData']);
    Route::get('/laporan-stok/print-stok', [LaporanStokController::class, 'printStok']);
    Route::get('/api/satuan-laporan-stok', [LaporanStokController::class, 'getSatuan']);
    Route::resource('/laporan-stok', LaporanStokController::class);

    Route::get('/laporan-barang-masuk/get-data', [LaporanBarangMasukController::class, 'getData']);
    Route::get('/laporan-barang-masuk/print-barang-masuk', [LaporanBarangMasukController::class, 'printBarangMasuk']);
    Route::get('/api/supplier/', [LaporanBarangMasukController::class, 'getSupplier']);
    Route::resource('/laporan-barang-masuk', LaporanBarangMasukController::class);

    Route::get('/laporan-barang-keluar/get-data', [LaporanBarangKeluarController::class, 'getData']);
    Route::get('/laporan-barang-keluar/print-barang-keluar', [LaporanBarangKeluarController::class, 'printBarangKeluar']);
    Route::get('/api/customer/', [LaporanBarangKeluarController::class, 'getCustomer']);
    Route::resource('/laporan-barang-keluar', LaporanBarangKeluarController::class);

    Route::get('/ubah-password', [UbahPasswordController::class, 'index']);
    Route::POST('/ubah-password', [UbahPasswordController::class, 'changePassword']);

    Route::get('/barang/get-data', [BarangController::class, 'getDataBarang']);
    Route::resource('/barang', BarangController::class);
    // Route::get('/barang/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::post('/barang/update', [BarangController::class, 'update'])->name('barang.update');
    Route::get('/barang/edit/{id}', [BarangController::class, 'edit'])->name('barang.edit');



    Route::get('/customer/get-data', [CustomerController::class, 'getDataCustomer']);
    Route::resource('/customer', CustomerController::class);

    Route::get('/api/barang-masuk/', [BarangMasukController::class, 'getAutoCompleteData']);
    Route::get('/barang-masuk/get-data', [BarangMasukController::class, 'getDataBarangMasuk']);
    Route::resource('/barang-masuk', BarangMasukController::class);

    Route::get('/api/barang-keluar/', [BarangKeluarController::class, 'getAutoCompleteData']);
    Route::get('/barang-keluar/get-data', [BarangKeluarController::class, 'getDataBarangKeluar']);
    Route::resource('/barang-keluar', BarangKeluarController::class);


    Route::resource('faktur', FakturController::class);

    Route::get('/faktur/{id}/edit', [FakturController::class, 'edit'])->name('faktur.edit');

    Route::put('/faktur/{id}', [FakturController::class, 'update'])->name('faktur.update');
});

require __DIR__ . '/auth.php';
