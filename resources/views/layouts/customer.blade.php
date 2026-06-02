<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Mitra Flora' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function getCookieEarly(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');

            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }

            return null;
        }

        (function () {
            const theme = getCookieEarly('theme_preference') || 'light';

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="cf-body">

    <nav class="cf-navbar">
        <div class="cf-navbar-inner">
            <div class="cf-navbar-row">
                <a href="{{ route('customer.dashboard') }}" class="cf-brand">
                    <div class="cf-brand-icon">🌷</div>

                    <div class="cf-brand-title">
                        Mitra <span>Flora</span>
                    </div>
                </a>

                <button type="button" id="customerMenuButton" class="cf-hamburger">
                    ☰
                </button>

                <div class="cf-desktop-menu">
                    <a href="{{ route('customer.dashboard') }}"
                       class="cf-nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        🏠 Beranda
                    </a>

                    <a href="{{ route('customer.catalog') }}"
                       class="cf-nav-link {{ request()->routeIs('customer.catalog') || request()->routeIs('customer.checkout') || request()->routeIs('customer.product.detail') ? 'active' : '' }}">
                        🌿 Katalog
                    </a>

                    <a href="{{ route('customer.history') }}"
                       class="cf-nav-link {{ request()->routeIs('customer.history') ? 'active' : '' }}">
                        📜 Riwayat
                    </a>

                    <a href="{{ route('customer.profile') }}"
                        class="cf-nav-link {{ request()->routeIs('customer.profile') || request()->routeIs('customer.profile.edit') ? 'active' : '' }}">
                        👤 Profil
                    </a>

                    <button type="button" id="themeToggle" class="cf-nav-link">
                        🌙 Mode
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="cf-logout-btn">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <div id="customerMobileMenu" class="cf-mobile-menu">
                <div class="cf-mobile-list">
                    <a href="{{ route('customer.dashboard') }}"
                       class="cf-nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        🏠 Beranda
                    </a>

                    <a href="{{ route('customer.catalog') }}"
                       class="cf-nav-link {{ request()->routeIs('customer.catalog') || request()->routeIs('customer.checkout') || request()->routeIs('customer.product.detail') ? 'active' : '' }}">
                        🌿 Katalog
                    </a>

                    <a href="{{ route('customer.history') }}"
                       class="cf-nav-link {{ request()->routeIs('customer.history') ? 'active' : '' }}">
                        📜 Riwayat
                    </a>

                    <a href="{{ route('customer.profile') }}"
                        class="cf-nav-link {{ request()->routeIs('customer.profile') || request()->routeIs('customer.profile.edit') ? 'active' : '' }}">
                        👤 Profil
                    </a>

                    <button type="button" id="themeToggleMobile" class="cf-nav-link">
                        🌙 Mode
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="cf-logout-btn">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="cf-main">
        <div class="cf-container">
            @if(session('success'))
                <div class="cf-success-alert">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="cf-footer">
        <div class="cf-footer-inner">
            <div class="cf-footer-grid">
                <div class="cf-footer-brand">
                    <div class="cf-footer-icon">🌷</div>

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

            <div class="cf-footer-bottom">
                © 2026 Mitra Flora. Hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <script>
        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + '=' + value + '; expires=' + date.toUTCString() + '; path=/';
        }

        function getCookie(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');

            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }

            return null;
        }

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            updateThemeButtonText(theme);
        }

        function updateThemeButtonText(theme) {
            const desktopButton = document.getElementById('themeToggle');
            const mobileButton = document.getElementById('themeToggleMobile');

            const text = theme === 'dark' ? '☀️ Mode Terang' : '🌙 Mode Gelap';

            if (desktopButton) {
                desktopButton.textContent = text;
            }

            if (mobileButton) {
                mobileButton.textContent = text;
            }
        }

        function toggleTheme() {
            const currentTheme = getCookie('theme_preference') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            setCookie('theme_preference', newTheme, 30);
            applyTheme(newTheme);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const savedTheme = getCookie('theme_preference') || 'light';
            applyTheme(savedTheme);
           
            const customerMenuButton = document.getElementById('customerMenuButton');
            const customerMobileMenu = document.getElementById('customerMobileMenu');

            if (customerMenuButton && customerMobileMenu) {
                customerMenuButton.addEventListener('click', function () {
                    customerMobileMenu.classList.toggle('show');
                });
            }
            const themeToggle = document.getElementById('themeToggle');
            const themeToggleMobile = document.getElementById('themeToggleMobile');

            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }

            if (themeToggleMobile) {
                themeToggleMobile.addEventListener('click', toggleTheme);
            }
        });
    </script>

</body>
</html>