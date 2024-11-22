@extends('layouts.app')



@section('content')
    <div class="section-header">
        <h1>Data Barang</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barang"><i class="fa fa-plus"></i> Tambah
                Barang</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Ukuran</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('barang.create')
    @include('barang.edit')
    @include('barang.show')
@endsection


@section('script-js')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#table_id').DataTable({
                paging: true
            });

            // Fetch and Render Data
            function fetchAndRenderData() {
                $.ajax({
                    url: "/barang/get-data",
                    type: "GET",
                    dataType: "JSON",
                    success: function(response) {
                        let counter = 1;
                        table.clear();
                        response.data.forEach(value => {
                            let stok = value.stok ? value.stok : "Stok Kosong";
                            let ukuran = value.ukuran ? value.ukuran : "Tidak ada ukuran";
                            let row = `
                                <tr id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td>${value.nama_barang}</td>
                                    <td>${stok}</td>
                                    <td>${ukuran}</td>
                                    <td>
                                        <button  class="btn btn-success btn-lg mb-2" data-id="${value.id}" id="detailButton"><i class="far fa-eye"></i></button>
                                        <button  class="btn btn-warning btn-lg mb-2" data-id="${value.id}" id="editButton"><i class="far fa-edit"></i></button>
                                        <button  class="btn btn-danger btn-lg mb-2" data-id="${value.id}" id="deleteButton"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>`;
                            table.row.add($(row)).draw(false);
                        });
                    },
                    error: function() {
                        Swal.fire("Error", "Failed to fetch data.", "error");
                    }
                });
            }

            fetchAndRenderData();

            // Show Add Modal
            $('#button_tambah_barang').click(function() {
                $('#modal_tambah_barang').modal('show');
            });

            // Store Data
            $('#store').click(function(e) {
                e.preventDefault();
                let formData = {
                    nama_barang: $('#nama_barang').val(),
                    stok: $('#stok').val(),
                    ukuran: $('#ukuran').val(),
                    _token: $("meta[name='csrf-token']").attr("content")
                };

                $.post("/barang", formData)
                    .done(response => {
                        Swal.fire("Success", response.message, "success");
                        $('#modal_tambah_barang').modal('hide');
                        fetchAndRenderData();
                    })
                    .fail(error => handleValidationErrors(error.responseJSON));
            });

            // Edit Data
            $(document).on('click', '#editButton', function() {
                let id = $(this).data('id');

                $.get(`/barang/edit/${id}`, function(response) {
                    // Populate the modal with data
                    $('#barang_id').val(response.data.id);
                    $('#edit_nama_barang').val(response.data.nama_barang);
                    $('#edit_stok').val(response.data.stok);
                    $('#edit_ukuran').val(response.data.ukuran);

                    // Show the modal
                    $('#modal_edit_barang').modal('show');
                }).fail(function() {
                    Swal.fire("Error", "Failed to fetch data.", "error");
                });
            });



            // Update Data
            $('#update').click(function(e) {
                e.preventDefault();
                let formData = {
                    id: $('#barang_id').val(),
                    nama_barang: $('#edit_nama_barang').val(),
                    stok: $('#edit_stok').val(),
                    ukuran: $('#edit_ukuran').val(),
                    _token: $("meta[name='csrf-token']").attr("content")
                };

                $.post("/barang/update", formData)
                    .done(response => {
                        Swal.fire("Success", response.message, "success");
                        $('#modal_edit_barang').modal('hide');
                        fetchAndRenderData();
                    })
                    .fail(error => handleValidationErrors(error.responseJSON));
            });

            // Delete Data
            $(document).on('click', '#deleteButton', function() {
                let id = $(this).data('id');
                let token = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                                url: `/barang/${id}`,
                                type: "DELETE",
                                data: {
                                    _token: token
                                }
                            })
                            .done(response => {
                                Swal.fire("Deleted!", response.message, "success");
                                fetchAndRenderData();
                            })
                            .fail(() => Swal.fire("Error", "Failed to delete data.", "error"));
                    }
                });
            });

            // Show Detail Modal
            $(document).on('click', '#detailButton', function() {
                let id = $(this).data('id');
                $.get(`/barang/${id}`)
                    .done(response => {
                        $('#detail_nama_barang').val(response.data.nama_barang);
                        $('#detail_stok').val(response.data.stok || "Stok Kosong");
                        $('#detail_ukuran').val(response.data.ukuran || "Tidak ada ukuran");
                        $('#modal_detail_barang').modal('show');
                    })
                    .fail(() => Swal.fire("Error", "Failed to fetch details.", "error"));
            });

            // Handle Validation Errors
            function handleValidationErrors(errors) {
                if (errors.nama_barang) {
                    $('#alert-nama_barang').removeClass('d-none').text(errors.nama_barang[0]);
                }
                if (errors.stok) {
                    $('#alert-stok').removeClass('d-none').text(errors.stok[0]);
                }
                if (errors.ukuran) {
                    $('#alert-ukuran').removeClass('d-none').text(errors.ukuran[0]);
                }
            }
        });
    </script>
@endsection
