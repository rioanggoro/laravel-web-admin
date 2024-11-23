<div id="modal_detail_barang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showBarangLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showBarangLabel">Show Detail Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit_barang_form">
                <div class="modal-body">
                    <input type="hidden" id="barang_id">
                    <div class="col-auto">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Keluar</button>
                    {{-- <button type="button" class="btn btn-primary" id="update">Update</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>
