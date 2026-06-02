@extends('layouts.admin')

@section('content')

<section class="mf-hero">
    <div>
        <div class="mf-hero-badge">
            <span class="mf-hero-dot"></span>
            Solusi Tanaman Hias Terbaik
        </div>

        <h1 class="mf-hero-title">
            Hadirkan Nuansa <br>
            <span>Asri Alami</span> Dalam <br>
            Setiap Ruangan Indah.
        </h1>

        <p class="mf-hero-text">
            Sistem pengelolaan tanaman hias yang praktis, membantu Anda mengatur stok,
            penjualan, dan data produk dengan mudah.
        </p>
    </div>
    
</section>

<div class="mf-stats-grid">
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon green">💰</div>
        <p class="mf-stat-label">Total Pendapatan</p>
        <h2 class="mf-stat-number green">
            Rp {{ number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') }}
        </h2>
        <p class="mf-stat-sub">Dari pesanan yang selesai</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill green" style="width: 70%"></div>
        </div>
        <span class="mf-stat-badge green">Pendapatan</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon blue">🌿</div>
        <p class="mf-stat-label">Produk Tersedia</p>
        <h2 class="mf-stat-number blue">
            {{ $stats['jumlah_produk_tersedia'] ?? 0 }}
        </h2>
        <p class="mf-stat-sub">Produk dengan stok tersedia</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill blue" style="width: 80%"></div>
        </div>
        <span class="mf-stat-badge blue">Aktif</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon amber">📦</div>
        <p class="mf-stat-label">Total Pesanan</p>
        <h2 class="mf-stat-number yellow">
            {{ $stats['total_pesanan'] ?? 0 }}
        </h2>
        <p class="mf-stat-sub">Seluruh pesanan customer</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill amber" style="width: 60%"></div>
        </div>
        <span class="mf-stat-badge amber">Pesanan</span>
    </div>
 
</div>

<section class="mf-section">
    <h2 class="mf-section-title">
        Pilihan Tanaman Hias Terbaik untuk Anda
    </h2>

    <p class="mf-section-subtitle">
        Kami menghadirkan tanaman berkualitas dengan tampilan menarik dan perawatan yang mudah.
    </p>

    <div class="mf-feature-grid">
        <a href="{{ route('admin.products.index') }}" class="mf-feature-card">
            <div class="mf-feature-icon">🌱</div>

            <h3>Manajemen Produk</h3>

            <p>
                Kelola data tanaman hias dengan mudah, mulai dari tambah, edit,
                hingga pengaturan stok dan harga produk.
            </p>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="mf-feature-card">
            <div class="mf-feature-icon">📝</div>

            <h3>Data Pesanan</h3>

            <p>
                Pantau seluruh transaksi penjualan secara terstruktur dengan laporan
                yang membantu analisis bisnis Anda.
            </p>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="mf-feature-card">
            <div class="mf-feature-icon">💳</div>

            <h3>Verifikasi Status Pesanan</h3>

            <p>
                Proses dan validasi pesanan pelanggan dengan cepat untuk memastikan
                transaksi berjalan aman dan akurat.
            </p>
        </a>

        <a href="{{ route('admin.profile') }}" class="mf-feature-card">
            <div class="mf-feature-icon">👤</div>

            <h3>Profil Admin</h3>

            <p>
                Lihat informasi akun admin yang sedang login dan kelola data profil Anda.
            </p>
        </a>
    </div>
</section>
@endsection