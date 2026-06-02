@extends('layouts.customer')

@section('content')
<section class="cf-hero">
    <div>
        <div class="cf-hero-badge">
            <span class="cf-hero-dot"></span>
            Koleksi Tanaman Hias Terbaik
        </div>

        <h1 class="cf-hero-title">
            Temukan Tanaman <br>
            <span>Indoor & Outdoor</span> <br>
            untuk Rumah Anda.
        </h1>

        <p class="cf-hero-text">
            Mitra Flora menyediakan berbagai tanaman hias berkualitas untuk mempercantik ruangan,
            taman, kantor, dan area rumah Anda.
        </p>

        <a href="{{ route('customer.catalog') }}" class="cf-btn cf-btn-primary">
            Lihat Katalog
        </a>
    </div>
</section>

<section class="cf-section">
    <h2 class="cf-section-title">
        Produk Tanaman Terbaru
    </h2>

    <p class="cf-section-subtitle">
        Pilih tanaman hias favorit Anda dan lakukan pemesanan dengan mudah.
    </p>

        <div class="cf-product-grid">
        @forelse($products as $product)
            <div class="cf-product-card">
                @if($product->gambar)
                    <img src="{{ asset('storage/' . $product->gambar) }}"
                         class="cf-product-img"
                         alt="{{ $product->nama_produk }}">
                @else
                    <div class="cf-product-placeholder">
                        🌿
                    </div>
                @endif

                <div class="cf-product-body">
                    <p class="cf-product-code">
                        {{ $product->kode_barang }}
                    </p>

                    <h3 class="cf-product-title">
                        {{ $product->nama_produk }}
                    </h3>

                    <p class="cf-product-price">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>

                    <p class="cf-product-meta">
                        Tanaman {{ ucfirst($product->kategori) }} • Stok {{ $product->stok }} pcs
                    </p>
                </div>
            </div>
        @empty
            <div class="cf-card" style="grid-column: 1 / -1; text-align:center;">
                <p class="cf-subtitle">
                    Belum ada produk yang tersedia.
                </p>
            </div>
        @endforelse
    </div>

    <div class="cf-card" style="margin-bottom: 28px;">
        <div class="cf-header">
            <h2 class="cf-title" style="font-size: 24px;">
                Cuaca Surabaya
            </h2>

            <p class="cf-subtitle">
                Data cuaca saat ini
            </p>
        </div>

        <div id="weatherLoading" class="cf-message cf-message-success">
            Mengambil data cuaca...
        </div>

        <div id="weatherResult" class="cf-grid-4 cf-hidden">
            <div class="cf-info-box">
                <p class="cf-info-label">Kota</p>
                <h3 id="weatherCity" class="cf-info-value"></h3>
            </div>

            <div class="cf-info-box">
                <p class="cf-info-label">Suhu Saat Ini</p>
                <h3 class="cf-info-value">
                    <span id="weatherTemp"></span>°C
                </h3>
            </div>

            <div class="cf-info-box">
                <p class="cf-info-label">Kondisi</p>
                <h3 id="weatherCondition" class="cf-info-value"></h3>
            </div>

            <div class="cf-info-box">
                <p class="cf-info-label">Deskripsi</p>
                <p id="weatherDescription" class="cf-info-value"></p>
            </div>
        </div>

        <p id="weatherTakenAt" class="cf-small-text"></p>
    </div>

    <div class="cf-card" style="margin-bottom: 28px;">
        <div class="cf-header">
            <h2 class="cf-title" style="font-size: 24px;">
                Pencarian Instan Produk
            </h2>

            <p class="cf-subtitle">
                Cari produk tanaman berdasarkan kode barang atau nama produk.
            </p>
        </div>

        <input type="text"
               id="liveSearchInput"
               class="cf-input"
               placeholder="Contoh: anggrek, MF-001...">

        <div id="liveSearchLoading" class="cf-loading cf-hidden">
            Mencari produk...
        </div>

        <div id="liveSearchResult" style="margin-top: 14px;">
            <p class="cf-small-text">
                Cari produk berdasarkan kata kunci...
            </p>
        </div>
    </div>

    <div class="cf-grid-3" style="margin-bottom: 28px;">
        <div class="cf-info-box">
            <p class="cf-info-label">
                Jumlah Kunjungan
            </p>

            <h3 class="cf-info-value" style="font-size: 28px;">
                {{ $visitStats['count'] ?? 0 }} kali
            </h3>
        </div>

        <div class="cf-info-box">
            <p class="cf-info-label">
                Kunjungan Pertama
            </p>

            <h3 class="cf-info-value">
                {{ $visitStats['first_visit'] ?? '-' }}
            </h3>
        </div>

        <div class="cf-info-box">
            <p class="cf-info-label">
                Kunjungan Terakhir
            </p>

            <h3 class="cf-info-value">
                {{ $visitStats['last_visit'] ?? '-' }}
            </h3>
        </div>
    </div>

    <div class="cf-card" style="margin-bottom: 34px;">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:18px; flex-wrap:wrap;">
            <div>
                <h2 class="cf-title" style="font-size: 24px;">
                    Statistik Kunjungan
                </h2>

                <p class="cf-subtitle">
                    Data ini dihitung menggunakan session Laravel.
                </p>
            </div>

            <form method="POST" action="{{ route('customer.visits.reset') }}">
                @csrf
                <button type="submit" class="cf-btn cf-btn-danger-soft">
                    Reset Hitungan
                </button>
            </form>
        </div>
    </div>


</section>

<script>
    async function loadWeather() {
        const loading = document.getElementById('weatherLoading');
        const result = document.getElementById('weatherResult');

        try {
            const response = await fetch("{{ route('customer.weather.surabaya') }}", {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            loading.classList.add('cf-hidden');

            if (!data.success) {
                loading.textContent = data.message || 'Data cuaca gagal dimuat.';
                loading.className = 'cf-message cf-message-error';
                return;
            }

            document.getElementById('weatherCity').textContent = data.city;
            document.getElementById('weatherTemp').textContent = data.temperature;
            document.getElementById('weatherCondition').textContent = data.condition;
            document.getElementById('weatherDescription').textContent = data.description;
            document.getElementById('weatherTakenAt').textContent = 'Data diambil pada: ' + data.taken_at;

            result.classList.remove('cf-hidden');
        } catch (error) {
            loading.textContent = 'Gagal mengambil data cuaca.';
            loading.className = 'cf-message cf-message-error';
        }
    }

    loadWeather();
</script>

<script>
    let searchTimer = null;

    const liveSearchInput = document.getElementById('liveSearchInput');

    if (liveSearchInput) {
        liveSearchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {
                liveSearchProduct(this.value);
            }, 400);
        });
    }

    async function liveSearchProduct(keyword) {
        const loading = document.getElementById('liveSearchLoading');
        const result = document.getElementById('liveSearchResult');

        if (keyword.trim() === '') {
            result.innerHTML = `
                <p class="cf-small-text">
                    Ketik kata kunci untuk mencari produk.
                </p>
            `;
            return;
        }

        loading.classList.remove('cf-hidden');

        try {
            const response = await fetch("{{ route('customer.live-search.products') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    keyword: keyword,
                }),
            });

            const data = await response.json();

            loading.classList.add('cf-hidden');

            if (!data.success || data.products.length === 0) {
                result.innerHTML = `
                    <div class="cf-message cf-message-warning">
                        Produk tidak ditemukan.
                    </div>
                `;
                return;
            }

            result.innerHTML = data.products.map(product => {
                return `
                    <div class="cf-info-box" style="margin-bottom:10px;">
                        <div style="display:flex; justify-content:space-between; gap:14px; flex-wrap:wrap;">
                            <div>
                                <p class="cf-info-label">${product.kode_barang}</p>
                                <h3 class="cf-info-value">${product.nama_produk}</h3>
                                <p class="cf-small-text">Tanaman ${product.kategori}</p>
                            </div>

                            <div style="text-align:right;">
                                <p class="cf-product-price" style="margin:0;">${product.harga}</p>
                                <p class="cf-small-text">Stok ${product.stok} pcs</p>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        } catch (error) {
            loading.classList.add('cf-hidden');

            result.innerHTML = `
                <div class="cf-message cf-message-error">
                    Terjadi kesalahan saat mencari produk.
                </div>
            `;
        }
    }
</script>
@endsection