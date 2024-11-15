<!-- Modal untuk Tambah Faktur -->
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
            <form id="form_tambah_faktur" action="{{ route('faktur.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nomor Faktur</label>
                        <input type="text" name="nomor_faktur" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Faktur</label>
                        <input type="text" name="kode_faktur" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="nama_barang" class="form-control" required>
                            @foreach ($faktur as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="banyak" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="number" name="harga_satuan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Faktur</button>
                </div>
            </form>
        </div>
    </div>
</div>
