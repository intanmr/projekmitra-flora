<button class="hamburger" id="hamburger" aria-label="Menu">
    <span></span>
    <span></span>
    <span></span>
</button>

<div class="sidebar-overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('img/logo mitra flora.webp') }}" alt="Logo Mitra Flora">
        <h2>Mitra<span>Flora</span></h2>
    </div>

    <hr>

    <p class="menu-title">Menu Utama</p>

    <nav class="menu">
        @auth
            <a href="#">
                👤 {{ auth()->user()->name }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    Logout
                </button>
            </form>
        @endauth

        @guest
            <a href="{{ route('login') }}">🔐 Login</a>
            <a href="{{ route('register') }}">📝 Register</a>
        @endguest
        
        <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">
            🏠 Beranda
        </a>
        <a href="{{ route('inputproduk') }}" class="{{ request()->routeIs('inputproduk') ? 'active' : '' }}">
            📥 Form Tambah Produk
        </a>

        <a href="{{ route('manajemenproduk') }}" class="{{ request()->routeIs('manajemenproduk') ? 'active' : '' }}">
            📦 Manajemen Produk
        </a>

        <a href="{{ route('datapesanan') }}" class="{{ request()->routeIs('datapesanan') ? 'active' : '' }}">
            🧾 Data Pesanan
        </a>

        <a href="#">
            👤 Data Customer
        </a>
    </nav>

    <hr>

    <nav class="menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            🏠 Beranda
        </a>

        <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">
            ℹ️ Tentang
        </a>

        <a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'active' : '' }}">
            📞 Kontak
        </a>
    </nav>
</aside>