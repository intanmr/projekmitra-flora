@extends('layouts.customer')

@section('content')
<div class="cf-card" style="margin-bottom: 24px;">
    <h1 class="cf-title">
        Selamat Datang di Mitra Flora
    </h1>

    <p class="cf-subtitle">
        Anda berhasil login sebagai customer. Silakan pilih menu beranda, katalog, atau riwayat pesanan.
    </p>
</div>

<div class="cf-grid-3">
    <div class="cf-card">
        <div style="font-size: 42px; margin-bottom: 12px;">🏠</div>
        <h3 class="cf-product-title">Beranda</h3>
        <p class="cf-subtitle">
            Halaman utama customer.
        </p>
    </div>

    <div class="cf-card">
        <div style="font-size: 42px; margin-bottom: 12px;">🌿</div>
        <h3 class="cf-product-title">Katalog</h3>
        <p class="cf-subtitle">
            Melihat daftar tanaman hias yang dijual.
        </p>
    </div>

    <div class="cf-card">
        <div style="font-size: 42px; margin-bottom: 12px;">📜</div>
        <h3 class="cf-product-title">Riwayat</h3>
        <p class="cf-subtitle">
            Melihat riwayat pesanan customer.
        </p>
    </div>
</div>
@endsection