<div class="modal fade" tabindex="-1" role="dialog" id="modal_detail_barang">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Show Detail Data Barang</h5>
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
                                <label>Gambar</label>
                                <img src="" class="img-preview img-fluid my-1" id="detail_gambar_preview"
                                    style="max-height: 275px; overflow:hidden; border: 1px solid black;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" id="detail_nama_barang"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>Stok Saat Ini</label>
                                <input type="text" class="form-control" name="stok" id="detail_stok" disabled>
                            </div>

                            <div class="form-group">
                                <label>Ukuran</label>
                                <input type="text" class="form-control" name="ukuran" id="detail_ukuran" disabled>
                            </div>

                            <div class="form-group">
                                <label>Stok Minimum</label>
                                <input type="number" class="form-control" name="stok_minimum" id="detail_stok_minimum"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" id="detail_deskripsi" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Keluar</button>
                </div>
            </form>
        </div>
    </div>
</div>
