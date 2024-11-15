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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" id="edit_nama_barang"
                                    required>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit_nama_barang">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" class="form-control" name="stok" id="edit_stok" required>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit_stok"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ukuran</label>
                                <input type="text" class="form-control" name="ukuran" id="edit_ukuran" required>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-edit_ukuran"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
