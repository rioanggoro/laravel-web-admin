@extends('layouts.app')

@include('barang.create')
@include('barang.edit')
@include('barang.show')

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

    <!-- Datatables Jquery -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                paging: true
            });

            $.ajax({
                url: "/barang/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let stok = value.stok !== null ? value.stok : "Stok Kosong";
                        let ukuran = value.ukuran !== null ? value.ukuran :
                            "Tidak ada ukuran"; // Menggunakan ukuran sesuai dengan kolom tabel

                        let barang = `
                    <tr class="barang-row" id="index_${value.id}">
                        <td>${counter++}</td>
                        <td>${value.nama_barang}</td>
                        <td>${stok}</td>
                        <td>${ukuran}</td>
                        <td>
                            <a href="javascript:void(0)" id="button_detail_barang" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2"><i class="far fa-eye"></i> </a>
                            <a href="javascript:void(0)" id="button_edit_barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                            <a href="javascript:void(0)" id="button_hapus_barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                        </td>
                    </tr>
                `;
                        $('#table_id').DataTable().row.add($(barang)).draw(false);
                    });
                }
            });
        });
    </script>



    <!-- Show Modal Tambah barang -->
    <script>
        $('body').on('click', '#button_tambah_barang', function() {
            $('#modal_tambah_barang').modal('show');
        });

        $('#store').click(function(e) {
            e.preventDefault();

            // Ambil nilai dari input form
            let nama_barang = $('#nama_barang').val();
            let stok = $('#stok').val();
            let ukuran = $('#ukuran').val(); // Mengganti 'satuan' dengan 'ukuran'
            let token = $("meta[name='csrf-token']").attr("content");

            // Buat FormData untuk AJAX
            let formData = new FormData();
            formData.append('nama_barang', nama_barang);
            formData.append('stok', stok);
            formData.append('ukuran', ukuran); // Menambahkan ukuran ke FormData
            formData.append('_token', token);

            $.ajax({
                url: '/barang',
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: '/barang/get-data',
                        type: "GET",
                        cache: false,
                        success: function(response) {
                            $('#table-barangs').html(''); // kosongkan tabel terlebih dahulu

                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let stok = value.stok !== null ? value.stok :
                                    "Stok Kosong";
                                let ukuran = value.ukuran !== null ? value.ukuran :
                                    "Tidak ada ukuran"; // Mengganti 'satuan' dengan 'ukuran'

                                let barang = `
                            <tr class="barang-row" id="index_${value.id}">
                                <td>${counter++}</td>
                                <td>${value.nama_barang}</td>
                                <td>${stok}</td>
                                <td>${ukuran}</td>
                                <td>
                                    <a href="javascript:void(0)" id="button_detail_barang" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2"><i class="far fa-eye"></i> </a>
                                    <a href="javascript:void(0)" id="button_edit_barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                    <a href="javascript:void(0)" id="button_hapus_barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                </td>
                            </tr>
                        `;
                                $('#table_id').DataTable().row.add($(barang)).draw(
                                    false);
                            });

                            $('#nama_barang').val('');
                            $('#stok').val('');
                            $('#ukuran').val(''); // Mengganti 'satuan' dengan 'ukuran'
                            $('#modal_tambah_barang').modal('hide');

                            let table = $('#table_id').DataTable();
                            table.draw();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.nama_barang && error.responseJSON
                        .nama_barang[0]) {
                        $('#alert-nama_barang').removeClass('d-none');
                        $('#alert-nama_barang').addClass('d-block');
                        $('#alert-nama_barang').html(error.responseJSON.nama_barang[0]);
                    }

                    if (error.responseJSON && error.responseJSON.stok && error.responseJSON.stok[0]) {
                        $('#alert-stok').removeClass('d-none');
                        $('#alert-stok').addClass('d-block');
                        $('#alert-stok').html(error.responseJSON.stok[0]);
                    }

                    if (error.responseJSON && error.responseJSON.ukuran && error.responseJSON.ukuran[
                            0]) { // Mengganti 'satuan' dengan 'ukuran'
                        $('#alert-ukuran').removeClass('d-none');
                        $('#alert-ukuran').addClass('d-block');
                        $('#alert-ukuran').html(error.responseJSON.ukuran[0]);
                    }
                }
            });
        });
    </script>



    <!-- Show Detail Data Barang -->
    <script>
        $('body').on('click', '#button_detail_barang', function() {
            let barang_id = $(this).data('id');

            $.ajax({
                url: `/barang/${barang_id}/`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#barang_id').val(response.data.id);
                    $('#detail_nama_barang').val(response.data.nama_barang);
                    $('#detail_stok').val(response.data.stok !== null && response.data.stok !== '' ?
                        response.data.stok : 'Stok Kosong');

                    // Mengganti 'satuan' dengan 'ukuran'
                    $('#detail_ukuran').val(response.data.ukuran ? response.data.ukuran :
                        'Tidak ada ukuran');

                    // Menghapus bagian deskripsi dan gambar jika tidak relevan
                    $('#modal_detail_barang').modal('show');
                },
                error: function(error) {
                    console.error("Error fetching barang details:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data barang.'
                    });
                }
            });
        });
    </script>



    <!-- Edit Data Barang -->
    <script>
        // Menampilkan Form Modal Edit
        $('body').on('click', '#button_edit_barang', function() {
            let barang_id = $(this).data('id');

            $.ajax({
                url: `/barang/${barang_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#barang_id').val(response.data.id);
                    $('#edit_nama_barang').val(response.data.nama_barang);
                    $('#edit_stok').val(response.data.stok);
                    $('#edit_ukuran').val(response.data
                        .ukuran); // Menggunakan 'ukuran' sesuai dengan tabel SQL

                    $('#modal_edit_barang').modal('show');
                }
            });
        });

        // Proses Update Data
        $('#update').click(function(e) {
            e.preventDefault();

            let barang_id = $('#barang_id').val();
            let nama_barang = $('#edit_nama_barang').val();
            let stok = $('#edit_stok').val();
            let ukuran = $('#edit_ukuran').val(); // Menggunakan 'ukuran' sesuai dengan tabel SQL
            let token = $("meta[name='csrf-token']").attr("content");

            // Buat objek FormData
            let formData = new FormData();
            formData.append('nama_barang', nama_barang);
            formData.append('stok', stok);
            formData.append('ukuran', ukuran); // Menggunakan 'ukuran' sesuai dengan tabel SQL
            formData.append('_token', token);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/barang/${barang_id}`,
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    let row = $(`#index_${response.data.id}`);
                    let rowData = row.find('td');

                    // Update tampilan data pada tabel
                    rowData.eq(2).text(response.data.nama_barang);
                    rowData.eq(3).text(response.data.stok);
                    rowData.eq(4).text(response.data
                        .ukuran); // Menggunakan 'ukuran' untuk menampilkan ukuran barang

                    $('#modal_edit_barang').modal('hide');
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.nama_barang) {
                        $('#alert-nama_barang').removeClass('d-none').addClass('d-block').html(error
                            .responseJSON.nama_barang[0]);
                    }
                    if (error.responseJSON && error.responseJSON.stok) {
                        $('#alert-stok').removeClass('d-none').addClass('d-block').html(error
                            .responseJSON.stok[0]);
                    }
                    if (error.responseJSON && error.responseJSON
                        .ukuran) { // Menggunakan 'ukuran' sesuai dengan tabel SQL
                        $('#alert-ukuran').removeClass('d-none').addClass('d-block').html(error
                            .responseJSON.ukuran[0]);
                    }
                }
            });
        });
    </script>



    <!-- Hapus Data Barang -->
    <script>
        $('body').on('click', '#button_hapus_barang', function() {
            let barang_id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "ingin menghapus data ini!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang/${barang_id}`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: true,
                                timer: 3000
                            });

                            // Hapus data dari cache DataTables
                            $('#table_id').DataTable().clear().draw();

                            // Ambil ulang data dan gambar tabel
                            $.ajax({
                                url: "/barang/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $.each(response.data, function(key, value) {
                                        let stok = value.stok != null ?
                                            value.stok : "Stok Kosong";
                                        let barang = `
                                        <tr class="barang-row" id="index_${value.id}">
                                            <td>${counter++}</td>
                                            <td><img src="/storage/${value.gambar}" alt="gambar Barang" style="width: 150px"; height="150px"></td>
                                            <td>${value.kode_barang}</td>
                                            <td>${value.nama_barang}</td>
                                            <td>${stok}</td>
                                            <td>
                                                <a href="javascript:void(0)" id="button_detail_barang" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2"><i class="far fa-eye"></i> </a>
                                                <a href="javascript:void(0)" id="button_edit_barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                                <a href="javascript:void(0)" id="button_hapus_barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                    `;
                                        $('#table_id').DataTable().row.add(
                                            $(barang)).draw(false);
                                    });
                                }
                            });
                        }
                    })
                }
            })
        })
    </script>
@endsection
