@extends('layouts.admin')

@section('content')
<div class="mf-card">
    <div class="mf-header">
        <a href="{{ route('admin.products.index') }}"
           class="mf-btn mf-btn-secondary">
            ← Kembali ke Manajemen Produk
        </a>
    </div>

    <div class="mf-detail-grid">
        <div>
            @if($product->gambar)
                <img src="{{ asset('storage/' . $product->gambar) }}"
                     alt="{{ $product->nama_produk }}"
                     class="mf-detail-image">
            @else
                <div class="mf-detail-placeholder">
                    🌿
                </div>
            @endif
        </div>

        <div>
            <p class="mf-detail-code">
                {{ $product->kode_barang }}
            </p>

            <h1 class="mf-detail-title">
                {{ $product->nama_produk }}
            </h1>

            <p class="mf-detail-price">
                Rp {{ number_format($product->harga, 0, ',', '.') }}
            </p>

            <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:16px;">
                @if($product->status === 'tersedia')
                    <span class="mf-badge mf-badge-green">Tersedia</span>
                @elseif($product->status === 'hampir_habis')
                    <span class="mf-badge mf-badge-warning">Hampir Habis</span>
                @else
                    <span class="mf-badge mf-badge-red">Habis</span>
                @endif

                <span class="mf-badge mf-badge-gray">
                    Stok: {{ $product->stok }} pcs
                </span>

                <span class="mf-badge mf-badge-green">
                    Tanaman {{ ucfirst($product->kategori) }}
                </span>
            </div>

            <div class="mf-info-grid">
                <div class="mf-info-box">
                    <p class="mf-info-label">Kode Barang</p>
                    <p class="mf-info-value">{{ $product->kode_barang }}</p>
                </div>

                <div class="mf-info-box">
                    <p class="mf-info-label">Kategori</p>
                    <p class="mf-info-value">Tanaman {{ ucfirst($product->kategori) }}</p>
                </div>

                <div class="mf-info-box">
                    <p class="mf-info-label">Harga</p>
                    <p class="mf-info-value">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                </div>

                <div class="mf-info-box">
                    <p class="mf-info-label">Tanggal Masuk</p>
                    <p class="mf-info-value">
                        {{ $product->tanggal_masuk ? \Carbon\Carbon::parse($product->tanggal_masuk)->format('Y-m-d') : '-' }}
                    </p>
                </div>
            </div>

            <div class="mf-actions" style="justify-content:flex-start;">
                <a href="{{ route('admin.products.edit', $product) }}" class="mf-btn mf-btn-primary">
                    Edit Produk
                </a>

                <a href="{{ route('admin.products.index') }}" class="mf-btn mf-btn-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection