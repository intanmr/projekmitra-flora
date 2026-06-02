@extends('layouts.customer')

@section('content')
<div class="cf-card">
    <div class="cf-header">
        <a href="{{ route('customer.catalog') }}"
           class="cf-btn cf-btn-secondary">
            ← Kembali ke Katalog
        </a>
    </div>

    <div class="cf-detail-grid">
        <div>
            @if($product->gambar)
                <img src="{{ asset('storage/' . $product->gambar) }}"
                     alt="{{ $product->nama_produk }}"
                     class="cf-detail-image">
            @else
                <div class="cf-detail-placeholder">
                    🌿
                </div>
            @endif
        </div>

        <div>
            <p class="cf-detail-code">
                {{ $product->kode_barang }}
            </p>

            <h1 class="cf-detail-title">
                {{ $product->nama_produk }}
            </h1>

            <p class="cf-detail-price">
                Rp {{ number_format($product->harga, 0, ',', '.') }}
            </p>

            <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:16px;">
                @if($product->status === 'tersedia')
                    <span class="cf-badge cf-badge-green">
                        Tersedia
                    </span>
                @elseif($product->status === 'hampir_habis')
                    <span class="cf-badge cf-badge-warning">
                        Hampir Habis
                    </span>
                @else
                    <span class="cf-badge cf-badge-red">
                        Habis
                    </span>
                @endif

                <span class="cf-badge cf-badge-gray">
                    Stok: {{ $product->stok }} pcs
                </span>

                <span class="cf-badge cf-badge-green">
                    Tanaman {{ ucfirst($product->kategori) }}
                </span>
            </div>

            <div class="cf-grid-2" style="margin-top: 24px;">
                <div class="cf-info-box">
                    <p class="cf-info-label">
                        Kode Produk
                    </p>

                    <p class="cf-info-value">
                        {{ $product->kode_barang }}
                    </p>
                </div>

                <div class="cf-info-box">
                    <p class="cf-info-label">
                        Kategori
                    </p>

                    <p class="cf-info-value">
                        Tanaman {{ ucfirst($product->kategori) }}
                    </p>
                </div>

                <div class="cf-info-box">
                    <p class="cf-info-label">
                        Harga
                    </p>

                    <p class="cf-info-value">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                </div>

                <div class="cf-info-box">
                    <p class="cf-info-label">
                        Stok
                    </p>

                    <p class="cf-info-value">
                        {{ $product->stok }} pcs
                    </p>
                </div>

                <div class="cf-info-box">
                    <p class="cf-info-label">
                        Status
                    </p>

                    <p class="cf-info-value">
                        @if($product->status === 'tersedia')
                            Tersedia
                        @elseif($product->status === 'hampir_habis')
                            Hampir Habis
                        @else
                            Habis
                        @endif
                    </p>
                </div>

                <div class="cf-info-box">
                    <p class="cf-info-label">
                        Tanggal Masuk
                    </p>

                    <p class="cf-info-value">
                        {{ $product->tanggal_masuk ? \Carbon\Carbon::parse($product->tanggal_masuk)->format('Y-m-d') : '-' }}
                    </p>
                </div>
            </div>

            <div class="cf-actions" style="justify-content:flex-start;">
                @if($product->stok > 0)
                    <a href="{{ route('customer.checkout', ['product_id' => $product->id]) }}"
                       class="cf-btn cf-btn-primary">
                        Beli Sekarang
                    </a>
                @else
                    <button type="button"
                            class="cf-btn cf-btn-secondary"
                            disabled>
                        Stok Habis
                    </button>
                @endif

                <a href="{{ route('customer.catalog') }}"
                   class="cf-btn cf-btn-secondary">
                    Lihat Produk Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endsection