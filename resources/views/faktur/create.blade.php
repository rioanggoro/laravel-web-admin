<!-- Modal Tambah Faktur -->
<div class="modal fade" id="modal_tambah_faktur" tabindex="-1" role="dialog" aria-labelledby="modalTambahFakturLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahFakturLabel">Tambah Faktur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_tambah_faktur">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nomor Faktur</label>
                        <input type="number" name="nomor_faktur" id="nomor_faktur" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nomor_faktur"></div>
                    </div>
                    <div class="form-group">
                        <label>Kode Faktur</label>
                        <input type="text" name="kode_faktur" id="kode_faktur" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-kode_faktur"></div>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama"></div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-alamat"></div>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="nama_barang" id="nama_barang" class="form-control" required>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_barang"></div>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="banyak" id="banyak" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-banyak"></div>
                    </div>
                    <div class="form-group">
                        <label>Ukuran</label>
                        <!-- Mengubah input text menjadi dropdown select -->
                        <select class="form-control" name="ukuran" id="ukuran" required>
                            <option value="" disabled selected>Pilih Ukuran</option>
                            <option value="1lt">1lt</option>
                            <option value="5lt">5lt</option>
                            <option value="15lt">15lt</option>
                            <option value="20lt">20lt</option>
                        </select>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-ukuran"></div>
                    </div>
                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-harga_satuan"></div>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Total</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control">
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-jumlah"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="store">Simpan Faktur</button>
                </div>
            </form>
        </div>
    </div>
</div>
