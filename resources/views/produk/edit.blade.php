@extends('layouts.app')

@section('page-title', 'Edit Produk')

@section('content')
<section class="content">
    <div class="content-box">
        <h2>Edit Produk</h2>
        <p>Ubah data produk tanaman hias.</p>

        <form action="{{ route('produk.update', $produk) }}" method="POST" enctype="multipart/form-data" class="form-produk">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', $produk->kode_barang) }}">
                @error('kode_barang')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}">
                @error('nama_produk')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="Tanaman Indoor" {{ old('kategori', $produk->kategori) == 'Tanaman Indoor' ? 'selected' : '' }}>
                        Tanaman Indoor
                    </option>
                    <option value="Tanaman Outdoor" {{ old('kategori', $produk->kategori) == 'Tanaman Outdoor' ? 'selected' : '' }}>
                        Tanaman Outdoor
                    </option>
                </select>
                @error('kategori')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}">
                @error('harga')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}">
                @error('stok')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $produk->tanggal_masuk->format('Y-m-d')) }}">
                @error('tanggal_masuk')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Gambar Produk</label>

                @if($produk->gambar)
                    <img src="{{ asset('img/' . $produk->gambar) }}" class="thumb" style="margin-bottom:10px;">
                @endif

                <input type="file" name="gambar" accept="image/*">

                @error('gambar')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-action">
                <button type="submit" class="btn-submit">Update Produk</button>
                <a href="{{ route('produk.index') }}" class="btn-reset" style="text-align:center;">Kembali</a>
            </div>
        </form>
    </div>
</section>
@endsection