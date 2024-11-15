<div class="modal fade" tabindex="-1" role="dialog" id="modal_edit_barang">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="barang_id">
                    <!-- Form input fields -->
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" id="edit_nama_barang" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" class="form-control" id="edit_stok" required>
                    </div>
                    <div class="form-group">
                        <label>Ukuran</label>
                        <input type="text" class="form-control" id="edit_ukuran" required>
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
