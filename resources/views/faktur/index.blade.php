@extends('layouts.app')

@section('content-header')
    <h1>Data Faktur</h1>
    <div class="ml-auto">
        <a href="{{ route('faktur.create') }}" class="btn btn-primary" id="button_tambah_barang"><i class="fa fa-plus"></i>
            Tambah
            Faktur</a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table id="table_faktur" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Faktur</th>
                            <th>Dibuat Pada</th>
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
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('modal-includes')
    @include('faktur.edit')
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            /**
             * Initialize DataTable instance
             * @type {DataTable}
             */
            const table = $('#table_faktur').DataTable({
                paging: true
            });

            /**
             * Formats a date string into a localized format based on local time
             *
             * @param {string} dateString - ISO date string to format
             * @returns {string} Formatted date string
             */
            const formatDate = (dateString) => {
                const days = [
                    "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
                ];
                const months = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];

                const date = new Date(dateString); // Date object based on the string
                const dayName = days[date.getDay()]; // getDay() for local day
                const day = date.getDate(); // getDate() for local day of the month
                const monthName = months[date.getMonth()]; // getMonth() for local month
                const year = date.getFullYear(); // getFullYear() for local year
                const hours = String(date.getHours()).padStart(2, "0"); // getHours() for local hour
                const minutes = String(date.getMinutes()).padStart(2, "0"); // getMinutes() for local minute
                const seconds = String(date.getSeconds()).padStart(2, "0"); // getSeconds() for local second

                return `${dayName}, ${day} ${monthName} ${year} ${hours}:${minutes}:${seconds}`;
            };

            /**
             * Loads invoice data from the server and populates the DataTable
             */
            function loadFaktur() {
                $.ajax({
                    url: '{{ route('faktur.index') }}',
                    type: "GET",
                    dataType: 'JSON',
                    success: function(response) {
                        table.clear();
                        const data = response.data.map((value, index) => [
                            index + 1,
                            value.nomor_faktur || "No Faktur",
                            formatDate(value.created_at) || "Tanggal dibuat",
                            value.kode_faktur || "No Kode",
                            value.nama || "Tidak Ada Nama",
                            value.alamat || "Tidak Ada Alamat",
                            value.banyak || 0,
                            value.barang.nama_barang || "Tidak Ada Barang",
                            value.ukuran || "Tidak Ada Ukuran",
                            value.harga_satuan || 0,
                            `
                    <div class="btn-group" role="group">
                        <button class="btn btn-warning btn-sm" onclick="editFaktur(${value.id})">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteFaktur(${value.id})">
                            Hapus
                        </button>
                    </div>
                    `
                        ]);
                        table.rows.add(data).draw();
                    },
                    error: function(error) {
                        console.error("Error fetching data:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil data faktur'
                        });
                    }
                });
            }

            /**
             * Calculates the total amount based on quantity and unit price
             */
            function calculateTotal() {
                const banyak = parseFloat($('#banyak').val()) || 0;
                const hargaSatuan = parseFloat($('#harga_satuan').val()) || 0;
                $('#jumlah').val(banyak * hargaSatuan);
            }

            /**
             * Initial data load
             */
            loadFaktur();

            /**
             * Event handler for add invoice button
             */
            $('#button_tambah_faktur').click(function() {
                $('#form_tambah_faktur')[0].reset();
                $('.alert').addClass('d-none');
                $('#modal_tambah_faktur').modal('show');
            });

            /**
             * Event handlers for quantity and unit price inputs
             */
            $('#banyak, #harga_satuan').on('input', calculateTotal);

            /**
             * Event handler for storing new invoice
             */
            $('#store').click(function(e) {
                e.preventDefault();
                const formData = new FormData($('#form_tambah_faktur')[0]);
                $('.alert').addClass('d-none');

                calculateTotal();

                $.ajax({
                    url: "{{ route('faktur.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            });
                            $('#modal_tambah_faktur').modal('hide');
                            $('#form_tambah_faktur')[0].reset();
                            loadFaktur();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            Object.entries(errors).forEach(([key, value]) => {
                                $(`#alert-${key}`).removeClass('d-none').html(value[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan server, silakan coba lagi.'
                            });
                        }
                    }
                });
            });

            /**
             * Event handler for updating invoice
             */
            $('#update').click(function(e) {
                e.preventDefault();

                const formData = {
                    nomor_faktur: $('#edit_nomor_faktur').val(),
                    kode_faktur: $('#edit_kode_faktur').val(),
                    nama_barang: $('#edit_nama_barang').val(),
                    banyak: $('#edit_banyak').val(),
                    harga_satuan: $('#edit_harga_satuan').val(),
                    nama: $('#edit_nama').val(),
                    alamat: $('#edit_alamat').val(),
                    ukuran: $('#edit_ukuran').val(),
                    id: $('#edit_faktur_id').val(),
                    barang_id: $('#edit_barang_id').val()
                }

                $.ajax({
                    url: "{{ route('faktur.store') }}",
                    type: "POST",
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content'), // Include CSRF token for Laravel
                    },
                    success: function(response) {
                        // Track the successful response data
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message[
                                0], // Optionally show the entire response data
                            showConfirmButton: true
                        });

                        // Close modal and reload data
                        $('#modal_edit_faktur').modal('hide');
                        loadFaktur();
                    },
                    error: function(error) {
                        // Track the error data
                        let errorMessage = "An unknown error occurred.";
                        if (error.responseJSON?.errors) {
                            // Show validation errors in Swal
                            Object.entries(error.responseJSON.errors).forEach(([key,
                                value
                            ]) => {
                                $(`#alert-edit-${key}`).removeClass('d-none').html(
                                    value[0]);

                                // Using Swal to display the specific error
                                Swal.fire({
                                    icon: 'error',
                                    title: `Error with ${key}`,
                                    text: value[0],
                                    showConfirmButton: true,
                                });
                            });
                        } else {
                            // Show the generic error message in Swal
                            if (error.responseJSON?.message) {
                                errorMessage = error.responseJSON.message;
                            } else {
                                errorMessage = error.responseText || errorMessage;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: errorMessage,
                                showConfirmButton: true,
                                timer: 5000
                            });
                        }
                    }
                });
            });


            /**
             * Global function to handle invoice editing
             * @param {number} id - Invoice ID to edit
             */
            window.editFaktur = function(id) {
                $.ajax({
                    url: `/faktur/${id}/edit`,
                    type: "GET",
                    success: function(response) {
                        $('#edit_faktur_id').val(response.id);
                        $('#edit_barang_id').val(response.barang_id);
                        $('#edit_nomor_faktur').val(response.nomor_faktur);
                        $('#edit_kode_faktur').val(response.kode_faktur);
                        $('#edit_nama').val(response.nama);
                        $('#edit_alamat').val(response.alamat);
                        $('#edit_banyak').val(response.jumlah_barang);
                        $('#edit_jumlah').val(response.jumlah_barang);
                        $('#edit_nama_barang').val(response.barang_id).trigger('change');
                        $('#edit_ukuran').val(response.ukuran);
                        $('#edit_harga_satuan').val(response.harga_satuan);
                        $('#form_edit_faktur').attr('action', `/faktur`);
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

            /**
             * Global function to handle invoice deletion
             * @param {number} id - Invoice ID to delete
             */
            window.deleteFaktur = function(id) {
                const token = $("meta[name='csrf-token']").attr("content");

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
                            method: "DELETE",
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
                            error: function() {
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
@endpush
