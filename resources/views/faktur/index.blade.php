@extends('layouts.app')

@include('faktur.create') <!-- Modal untuk Tambah Faktur -->
@include('faktur.edit') <!-- Modal untuk Edit Faktur -->

@section('content')
    <div class="container">
        <h1>Data Faktur</h1>
        <button class="btn btn-primary mb-3" id="button_tambah_faktur">Tambah Faktur</button>

        <table id="table_faktur" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Faktur</th>
                    <th>Kode Faktur</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Jumlah</th>
                    <th>Nama Barang</th>
                    <th>Ukuran</th>
                    <th>Harga Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#table_faktur').DataTable({
                paging: true
            });

            function loadFaktur() {
                $.ajax({
                    url: '/faktur/get-data',
                    type: "GET",
                    dataType: 'JSON',
                    success: function(response) {
                        let table = $('#table_faktur').DataTable();
                        table.clear();
                        let data = response.data.map((value, index) => {
                            return [
                                index + 1,
                                value.nomor_faktur || "No Faktur",
                                value.kode_faktur || "No Kode",
                                value.nama || "Tidak Ada Nama",
                                value.alamat || "Tidak Ada Alamat",
                                value.banyak || 0,
                                value.nama_barang || "Tidak Ada Barang",
                                value.ukuran || "Tidak Ada Ukuran",
                                value.harga_satuan || 0,
                                `
                    <button class="btn btn-warning" onclick="editFaktur(${value.id})">Edit</button>
                    <button class="btn btn-danger" onclick="deleteFaktur(${value.id})">Hapus</button>
                    `
                            ];
                        });
                        table.rows.add(data).draw();
                    },
                    error: function(error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }


            // Load data saat halaman pertama kali dimuat
            loadFaktur();

            // Tambah Faktur
            $('#button_tambah_faktur').click(function() {
                $('#form_tambah_faktur')[0].reset(); // Reset form
                $('.alert').addClass('d-none'); // Sembunyikan alert
                $('#modal_tambah_faktur').modal('show');
            });


            $('#store').click(function(e) {
                e.preventDefault();
                let formData = new FormData($('#form_tambah_faktur')[0]);
                $('.alert').addClass('d-none'); // Sembunyikan semua alert error

                $.ajax({
                    url: '/faktur',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message || 'Data berhasil ditambahkan',
                            showConfirmButton: true,
                            timer: 3000
                        });
                        $('#modal_tambah_faktur').modal('hide');
                        loadFaktur();
                    },
                    error: function(error) {
                        if (error.responseJSON && error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(key, value) {
                                $(`#alert-${key}`).removeClass('d-none').html(value[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan server, silakan coba lagi.',
                            });
                        }
                    }
                });
            });


            // Fungsi Edit Faktur
            window.editFaktur = function(id) {
                $.ajax({
                    url: `/faktur/${id}/edit`,
                    type: "GET",
                    success: function(response) {
                        $('#edit_nomor_faktur').val(response.nomor_faktur);
                        $('#edit_kode_faktur').val(response.kode_faktur);
                        $('#edit_nama').val(response.nama);
                        $('#edit_alamat').val(response.alamat);
                        $('#edit_banyak').val(response.banyak);
                        $('#edit_nama_barang').val(response.nama_barang);
                        $('#edit_ukuran').val(response.ukuran);
                        $('#edit_harga_satuan').val(response.harga_satuan);
                        $('#form_edit_faktur').attr('action', `/faktur/${id}`);
                        $('#modal_edit_faktur').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil data faktur.'
                        });
                    }
                });
            };

            // Update Faktur
            $('#update').click(function(e) {
                e.preventDefault();
                let formData = new FormData($('#form_edit_faktur')[0]);

                $.ajax({
                    url: $('#form_edit_faktur').attr('action'),
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message || 'Data berhasil diupdate',
                            showConfirmButton: true,
                            timer: 3000
                        });
                        $('#modal_edit_faktur').modal('hide');
                        loadFaktur();
                    },
                    error: function(error) {
                        if (error.responseJSON && error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(key, value) {
                                $(`#alert-edit-${key}`).removeClass('d-none').html(
                                    value[0]);
                            });
                        }
                    }
                });
            });

            // Hapus Faktur
            window.deleteFaktur = function(id) {
                let token = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    title: 'Apakah Kamu Yakin?',
                    text: "Ingin menghapus data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YA, HAPUS!',
                    cancelButtonText: 'TIDAK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/faktur/${id}`,
                            type: "DELETE",
                            data: {
                                "_token": token
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message ||
                                        'Data berhasil dihapus',
                                    showConfirmButton: true,
                                    timer: 3000
                                });
                                loadFaktur();
                            },
                            error: function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Gagal menghapus data faktur.'
                                });
                            }
                        });
                    }
                });
            };
        });
    </script>
@endsection
