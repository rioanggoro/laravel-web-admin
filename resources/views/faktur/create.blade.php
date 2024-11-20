@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Tambah Faktur</h1>
        <!-- Form Tambah Faktur -->
        <form id="form_tambah_faktur" method="POST" action="{{ route('faktur.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor Faktur -->
                <div class="form-group">
                    <label for="nomor_faktur">Nomor Faktur</label>
                    <input type="number" name="nomor_faktur" id="nomor_faktur" class="form-control" required>
                </div>
                <!-- Kode Faktur -->
                <div class="form-group">
                    <label for="kode_faktur">Kode Faktur</label>
                    <input type="text" name="kode_faktur" id="kode_faktur" class="form-control" required>
                </div>
                <!-- Nama -->
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <!-- Alamat -->
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                </div>
                <!-- Nama Barang -->
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <select name="nama_barang" id="nama_barang" class="form-control" required>
                        <option value="" disabled selected>Pilih Barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}">
                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Banyak -->
                <div class="form-group">
                    <label for="banyak">Jumlah</label>
                    <input type="number" name="banyak" id="banyak" class="form-control" required>
                </div>
                <!-- Ukuran -->
                <div class="form-group">
                    <label for="ukuran">Ukuran</label>
                    <select class="form-control" name="ukuran" id="ukuran" required>
                        <option value="" disabled selected>Pilih Ukuran</option>
                        <option value="1lt">1lt</option>
                        <option value="5lt">5lt</option>
                        <option value="15lt">15lt</option>
                        <option value="20lt">20lt</option>
                    </select>
                </div>
                <!-- Harga Satuan -->
                <div class="form-group">
                    <label for="harga_satuan">Harga Satuan</label>
                    <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" required>
                </div>
                <!-- Jumlah Total -->
                <div class="form-group">
                    <label for="jumlah">Jumlah Total</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" readonly>
                </div>
            </div>
            <!-- Tombol Simpan -->
            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Simpan Faktur</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const banyakInput = document.getElementById('banyak');
            const hargaInput = document.getElementById('harga_satuan');
            const jumlahInput = document.getElementById('jumlah');

            // Update jumlah total secara otomatis
            function updateJumlah() {
                const banyak = parseInt(banyakInput.value) || 0;
                const harga = parseInt(hargaInput.value) || 0;
                jumlahInput.value = banyak * harga;
            }

            banyakInput.addEventListener('input', updateJumlah);
            hargaInput.addEventListener('input', updateJumlah);
        });
    </script>
@endsection
