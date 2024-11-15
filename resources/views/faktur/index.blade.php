@extends('layouts.app')

@include('faktur.create') <!-- Modal untuk Tambah Faktur -->
@include('faktur.edit') <!-- Modal untuk Edit Faktur -->

@section('content')
    <div class="container">
        <h1>Daftar Faktur</h1>
        <button class="btn btn-primary mb-3" id="button_tambah_faktur">Tambah Faktur</button>

        <table id="table_faktur" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Faktur</th>
                    <th>Kode Faktur</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faktur as $faktur)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $faktur->nomor_faktur }}</td>
                        <td>{{ $faktur->kode_faktur }}</td>
                        <td>{{ $faktur->barang->nama_barang }}</td>
                        <td>{{ $faktur->banyak }}</td>
                        <td>{{ $faktur->harga_satuan }}</td>
                        <td>
                            <button class="btn btn-warning" onclick="editFaktur({{ $faktur->id }})">Edit</button>
                            <form action="{{ route('faktur.destroy', $faktur->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#table_faktur').DataTable({
                paging: true
            });

            // Tampilkan modal tambah faktur
            $('#button_tambah_faktur').click(function() {
                $('#modal_tambah_faktur').modal('show');
            });
        });

        // Fungsi untuk menampilkan modal edit faktur dengan data
        function editFaktur(id) {
            $.ajax({
                url: `/faktur/${id}/edit`,
                type: "GET",
                success: function(data) {
                    $('#edit_nomor_faktur').val(data.nomor_faktur);
                    $('#edit_kode_faktur').val(data.kode_faktur);
                    $('#edit_nama_barang').val(data.nama_barang);
                    $('#edit_banyak').val(data.banyak);
                    $('#edit_harga_satuan').val(data.harga_satuan);
                    $('#form_edit_faktur').attr('action', `/faktur/${id}`);
                    $('#modal_edit_faktur').modal('show');
                },
                error: function() {
                    alert("Gagal mengambil data faktur.");
                }
            });
        }
    </script>
@endsection
