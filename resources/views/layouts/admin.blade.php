<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Mitra Flora' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="mf-body">

    <nav class="mf-navbar">
        <div class="mf-navbar-inner">
            <div class="mf-navbar-row">
                <a href="{{ route('admin.dashboard') }}" class="mf-brand">
                    <div class="mf-brand-icon">
                        🌷
                    </div>

                    <div class="mf-brand-title">
                        Mitra <span>Flora</span>
                    </div>
                </a>

                <button type="button" id="adminMenuButton" class="mf-hamburger">
                    ☰
                </button>

                <div class="mf-desktop-menu">
                    <a href="{{ route('admin.dashboard') }}"
                       class="mf-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        🏠 Dashboard
                    </a>

                    <a href="{{ route('admin.products.create') }}"
                       class="mf-nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        📥 Input Produk
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="mf-nav-link {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit') || request()->routeIs('admin.products.show') ? 'active' : '' }}">
                        📦 Manajemen Produk
                    </a>

                    @if(Route::has('admin.orders.index'))
                        <a href="{{ route('admin.orders.index') }}"
                           class="mf-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            🧾 Data Pesanan
                        </a>
                    @endif

                    @if(Route::has('admin.profile'))
                        <a href="{{ route('admin.profile') }}"
                           class="mf-nav-link {{ request()->routeIs('admin.profile') || request()->routeIs('profile.edit') ? 'active' : '' }}">
                            👤 Profil Admin
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mf-logout-btn">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <div id="adminMobileMenu" class="mf-mobile-menu">
                <div class="mf-mobile-list">
                    <a href="{{ route('admin.dashboard') }}"
                       class="mf-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        🏠 Dashboard
                    </a>

                    <a href="{{ route('admin.products.create') }}"
                       class="mf-nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        📥 Input Produk
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="mf-nav-link {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit') || request()->routeIs('admin.products.show') ? 'active' : '' }}">
                        📦 Manajemen Produk
                    </a>

                    @if(Route::has('admin.orders.index'))
                        <a href="{{ route('admin.orders.index') }}"
                           class="mf-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            🧾 Data Pesanan
                        </a>
                    @endif

                    @if(Route::has('admin.profile'))
                        <a href="{{ route('admin.profile') }}"
                           class="mf-nav-link {{ request()->routeIs('admin.profile') || request()->routeIs('profile.edit') ? 'active' : '' }}">
                            👤 Profil Admin
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mf-logout-btn">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="mf-main">
        <div class="mf-container">
            @if(session('success'))
                <div class="mf-success-alert">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="mf-footer">
        <div class="mf-footer-inner">
            <div class="mf-footer-grid">
                <div class="mf-footer-brand">
                    <div class="mf-footer-icon">
                        🌷
                    </div>

                    <div>
                        <h3>Mitra Flora</h3>
                        <p>
                            Menyediakan berbagai kebutuhan tanaman hias berkualitas untuk rumah, kantor, dan taman.
                        </p>
                    </div>
                </div>

                <div>
                    <h3>Lokasi</h3>
                    <p>Jl. Kalimantan 10, Jember, Indonesia</p>
                </div>

                <div>
                    <h3>Kontak</h3>
                    <p>
                        Email: mitraflora@gmail.com <br>
                        WA: 08123456789
                    </p>
                </div>
            </div>

            <div class="mf-footer-bottom">
                © 2026 Mitra Flora. Hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const adminMenuButton = document.getElementById('adminMenuButton');
            const adminMobileMenu = document.getElementById('adminMobileMenu');

            if (adminMenuButton && adminMobileMenu) {
                adminMenuButton.addEventListener('click', function () {
                    adminMobileMenu.classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html>