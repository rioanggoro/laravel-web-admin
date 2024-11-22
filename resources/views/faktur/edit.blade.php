<!-- Modal Edit Faktur -->
<div class="modal fade" id="modal_edit_faktur" tabindex="-1" role="dialog" aria-labelledby="modalEditFakturLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditFakturLabel">Edit Faktur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit_faktur">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_faktur_id">
                    <input type="hidden" name="nama" id="edit_nama">
                    <input type="hidden" name="alamat" id="edit_alamat">
                    <input type="hidden" name="ukuran" id="edit_ukuran">
                    <input type="hidden" name="barang_id" id="edit_barang_id">
                    <div class="form-group">
                        <label>Nomor Faktur</label>
                        <input type="text" name="nomor_faktur" id="edit_nomor_faktur" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit-nomor_faktur"></div>
                    </div>
                    <div class="form-group">
                        <label>Kode Faktur</label>
                        <input type="text" name="kode_faktur" id="edit_kode_faktur" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit-kode_faktur"></div>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="nama_barang" id="edit_nama_barang" class="form-control" required>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit-nama_barang"></div>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="hidden" name="jumlah" id="edit_jumlah">
                        <input type="number" name="banyak" id="edit_banyak" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit-banyak"></div>
                    </div>
                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="number" name="harga_satuan" id="edit_harga_satuan" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit-harga_satuan"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="update">Update Faktur</button>
                </div>
            </form>
        </div>
    </div>
</div>
