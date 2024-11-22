<!-- Modal -->
<div class="modal fade" id="modal_tambah_barang" tabindex="-1" role="dialog" aria-labelledby="modal_tambah_barang_label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_tambah_barang_label">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Ubah type button di tombol submit menjadi button biasa -->
            <form enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" id="nama_barang" required>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_barang"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" class="form-control" name="stok" id="stok" required>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stok"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                        <!-- Ubah type ke button untuk AJAX -->
                        <button type="button" class="btn btn-primary" id="store">Tambah</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
