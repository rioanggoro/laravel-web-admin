@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Faktur</h1>
        <form action="{{ route('faktur.update', $faktur->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nomor Faktur</label>
                <input type="text" name="nomor_faktur" class="form-control" value="{{ $faktur->nomor_faktur }}" required>
            </div>
            <div class="form-group">
                <label>Kode Faktur</label>
                <input type="text" name="kode_faktur" class="form-control" value="{{ $faktur->kode_faktur }}" required>
            </div>
            <div class="form-group">
                <label>Nama Barang</label>
                <select name="nama_barang" class="form-control" required>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}" {{ $faktur->nama_barang == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="banyak" class="form-control" value="{{ $faktur->banyak }}" required>
            </div>
            <div class="form-group">
                <label>Harga Satuan</label>
                <input type="number" name="harga_satuan" class="form-control" value="{{ $faktur->harga_satuan }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Faktur</button>
        </form>
    </div>
@endsection
