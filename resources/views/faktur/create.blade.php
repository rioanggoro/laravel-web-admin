<form action="{{ route('faktur.store') }}" method="POST" id="form_tambah_faktur">
    @csrf
    <div class="form-group">
        <label for="nomor_faktur">Nomor Faktur</label>
        <input type="text" name="nomor_faktur" id="nomor_faktur" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="kode_faktur">Kode Faktur</label>
        <input type="text" name="kode_faktur" id="kode_faktur" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="nama_barang">Nama Barang</label>
        <select name="nama_barang" id="nama_barang" class="form-control" required>
            <option value="" disabled selected>Pilih Barang</option>
            @foreach ($barangs as $barang)
                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="banyak">Jumlah</label>
        <input type="number" name="banyak" id="banyak" class="form-control" min="1" required>
    </div>

    <div class="form-group">
        <label for="harga_satuan">Harga Satuan</label>
        <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" min="0" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Faktur</button>
</form>
