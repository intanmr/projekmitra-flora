@extends('layouts.app')

@section('page-title', 'Tambah Produk')

@section('content')
<section class="content">
    <div class="content-box">
        <h2>Tambah Produk</h2>
        <p>Silakan isi data produk tanaman hias baru.</p>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="form-produk">
            @csrf

            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: MF-001">
                @error('kode_barang')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" placeholder="Contoh: Anggrek Bulan">
                @error('nama_produk')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Tanaman Indoor" {{ old('kategori') == 'Tanaman Indoor' ? 'selected' : '' }}>
                        Tanaman Indoor
                    </option>
                    <option value="Tanaman Outdoor" {{ old('kategori') == 'Tanaman Outdoor' ? 'selected' : '' }}>
                        Tanaman Outdoor
                    </option>
                </select>
                @error('kategori')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" value="{{ old('harga') }}" placeholder="Contoh: 150000">
                @error('harga')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok') }}" placeholder="Contoh: 20">
                @error('stok')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}">
                @error('tanggal_masuk')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" accept="image/*">
                @error('gambar')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-action">
                <button type="submit" class="btn-submit">Simpan Produk</button>
                <a href="{{ route('produk.index') }}" class="btn-reset" style="text-align:center;">Kembali</a>
            </div>
        </form>
    </div>
</section>
@endsection