<div id="modal_edit_barang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editBarangLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBarangLabel">Edit Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit_barang_form">
                <div class="modal-body">
                    <input type="hidden" id="barang_id">
                    <div class="form-group">
                        <label for="edit_nama_barang">Nama Barang</label>
                        <input type="text" class="form-control" id="edit_nama_barang" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_stok">Stok</label>
                        <input type="number" class="form-control" id="edit_stok" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_ukuran">Ukuran</label>
                        <select class="form-control" name="edit_ukuran" id="edit_ukuran" required>
                            <option value="" disabled selected>Pilih Ukuran</option>
                            <option value="1lt">1lt</option>
                            <option value="5lt">5lt</option>
                            <option value="15lt">15lt</option>
                            <option value="20lt">20lt</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
