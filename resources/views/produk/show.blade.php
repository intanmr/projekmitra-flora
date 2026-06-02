@extends('layouts.app')

@section('page-title', 'Detail Produk')

@section('content')
<section class="content">
    <div class="content-box">
        <h2>Detail Produk</h2>
        <p>Informasi lengkap produk tanaman hias.</p>

        @if($produk->gambar)
            <img src="{{ asset('img/' . $produk->gambar) }}"
                 style="width:120px; border-radius:14px; margin-bottom:20px;">
        @endif

        <p><strong>Kode Barang:</strong> {{ $produk->kode_barang }}</p>
        <p><strong>Nama Produk:</strong> {{ $produk->nama_produk }}</p>
        <p><strong>Kategori:</strong> {{ $produk->kategori }}</p>
        <p><strong>Harga:</strong> Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
        <p><strong>Stok:</strong> {{ $produk->stok }}</p>
        <p><strong>Tanggal Masuk:</strong> {{ $produk->tanggal_masuk->format('Y-m-d') }}</p>

        <p>
            <strong>Status:</strong>
            @if($produk->stok < 5)
                <span class="status hampir">Hampir Habis</span>
            @else
                <span class="status tersedia">Tersedia</span>
            @endif
        </p>

        <div class="form-action" style="margin-top:20px;">
            <a href="{{ route('produk.edit', $produk) }}" class="btn-submit" style="text-align:center;">
                Edit
            </a>

            <a href="{{ route('produk.index') }}" class="btn-reset" style="text-align:center;">
                Kembali
            </a>
        </div>
    </div>
</section>
@endsection