<button class="hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
</button>

<div class="sidebar-overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo mitra flora.webp') }}" alt="Logo Mitra Flora">
        <h2>Mitra<span>Flora</span></h2>
    </div>

    <hr>

    <p class="menu-title">Menu Utama</p>

    <nav class="menu">
        <a href="{{ route('input.produk') }}">📥 Input Produk</a>
        <a href="{{ route('manajemen.produk') }}">📦 Manajemen Produk</a>
        <a href="{{ route('data.pesanan') }}">🧾 Data Pesanan</a>
        <a href="{{ route('data.customer') }}">👤 Data Customer</a>
    </nav>

    <hr>

    <nav class="menu">
        <a href="{{ route('beranda') }}">🏠 Beranda</a>
    </nav>
</aside>