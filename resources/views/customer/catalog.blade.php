@extends('layouts.customer')

@section('content')
<div class="cf-card">
    <div class="cf-header">
        <h1 class="cf-title">
            Katalog Produk
        </h1>

        <p class="cf-subtitle">
            Pilih tanaman hias favorit Anda dari katalog Mitra Flora.
        </p>
    </div>

    {{-- LIVE SEARCH DAN FILTER --}}
    <div class="cf-filter-row">
        <input type="text"
               id="catalogSearchInput"
               class="cf-input"
               placeholder="Cari nama produk atau kode barang...">

        <select id="catalogCategoryFilter" class="cf-select">
            <option value="">Semua Kategori</option>
            <option value="indoor">Indoor</option>
            <option value="outdoor">Outdoor</option>
        </select>

        <button type="button"
                id="catalogResetButton"
                class="cf-btn cf-btn-secondary">
            Reset
        </button>
    </div>

    <div id="catalogLoading" class="cf-loading cf-hidden">
        Mencari produk...
    </div>

    {{-- GRID PRODUK --}}
    <div id="catalogProductGrid" class="cf-product-grid">
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

                    <div style="margin-top: 14px;">
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
                    </div>

                    <div class="cf-product-actions">
                        <a href="{{ route('customer.product.detail', $product) }}"
                           class="cf-btn cf-btn-secondary">
                            Detail
                        </a>

                        @if($product->stok > 0)
                            <a href="{{ route('customer.checkout', ['product_id' => $product->id]) }}"
                               class="cf-btn cf-btn-primary">
                                Beli
                            </a>
                        @else
                            <button type="button"
                                    class="cf-btn cf-btn-secondary"
                                    disabled>
                                Habis
                            </button>
                        @endif
                    </div>
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

    <div id="catalogPagination" class="cf-pagination">
        {{ $products->links() }}
    </div>
</div>

<script>
    const catalogSearchInput = document.getElementById('catalogSearchInput');
    const catalogCategoryFilter = document.getElementById('catalogCategoryFilter');
    const catalogResetButton = document.getElementById('catalogResetButton');
    const catalogProductGrid = document.getElementById('catalogProductGrid');
    const catalogLoading = document.getElementById('catalogLoading');
    const catalogPagination = document.getElementById('catalogPagination');

    const initialCatalogHTML = catalogProductGrid.innerHTML;
    const initialCatalogPaginationHTML = catalogPagination ? catalogPagination.innerHTML : '';

    let catalogSearchTimer = null;

    function getProductStatusBadge(status) {
        if (status === 'tersedia') {
            return `<span class="cf-badge cf-badge-green">Tersedia</span>`;
        }

        if (status === 'hampir_habis') {
            return `<span class="cf-badge cf-badge-warning">Hampir Habis</span>`;
        }

        return `<span class="cf-badge cf-badge-red">Habis</span>`;
    }

    function getProductImage(product) {
        if (product.gambar) {
            return `
                <img src="${product.gambar}"
                     class="cf-product-img"
                     alt="${product.nama_produk}">
            `;
        }

        return `
            <div class="cf-product-placeholder">
                🌿
            </div>
        `;
    }

    function getDetailUrl(productId) {
        let url = "{{ route('customer.product.detail', ':id') }}";
        return url.replace(':id', productId);
    }

    function getCheckoutUrl(productId) {
        return "{{ route('customer.checkout') }}" + "?product_id=" + productId;
    }

    function renderCatalogProducts(products) {
        if (products.length === 0) {
            catalogProductGrid.innerHTML = `
                <div class="cf-card" style="grid-column: 1 / -1; text-align:center;">
                    <p class="cf-subtitle">
                        Produk tidak ditemukan.
                    </p>
                </div>
            `;
            return;
        }

        catalogProductGrid.innerHTML = products.map(product => {
            const detailUrl = product.detail_url ?? getDetailUrl(product.id);
            const checkoutUrl = product.checkout_url ?? getCheckoutUrl(product.id);

            const buyButton = product.stok > 0
                ? `
                    <a href="${checkoutUrl}" class="cf-btn cf-btn-primary">
                        Beli
                    </a>
                `
                : `
                    <button type="button" class="cf-btn cf-btn-secondary" disabled>
                        Habis
                    </button>
                `;

            return `
                <div class="cf-product-card">
                    ${getProductImage(product)}

                    <div class="cf-product-body">
                        <p class="cf-product-code">
                            ${product.kode_barang}
                        </p>

                        <h3 class="cf-product-title">
                            ${product.nama_produk}
                        </h3>

                        <p class="cf-product-price">
                            ${product.harga}
                        </p>

                        <div style="margin-top: 14px;">
                            ${getProductStatusBadge(product.status)}
                        </div>

                        <div class="cf-product-actions">
                            <a href="${detailUrl}" class="cf-btn cf-btn-secondary">
                                Detail
                            </a>

                            ${buyButton}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    async function liveSearchCatalogProducts() {
        const keyword = catalogSearchInput.value.trim();
        const kategori = catalogCategoryFilter.value;

        if (keyword === '' && kategori === '') {
            catalogProductGrid.innerHTML = initialCatalogHTML;

            if (catalogPagination) {
                catalogPagination.innerHTML = initialCatalogPaginationHTML;
                catalogPagination.classList.remove('cf-hidden');
            }

            return;
        }

        catalogLoading.classList.remove('cf-hidden');

        if (catalogPagination) {
            catalogPagination.classList.add('cf-hidden');
        }

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
                    kategori: kategori,
                }),
            });

            const data = await response.json();

            catalogLoading.classList.add('cf-hidden');

            if (data.success) {
                renderCatalogProducts(data.products);
            } else {
                catalogProductGrid.innerHTML = `
                    <div class="cf-card" style="grid-column: 1 / -1; text-align:center;">
                        <p class="cf-subtitle">
                            Data produk gagal dimuat.
                        </p>
                    </div>
                `;
            }
        } catch (error) {
            catalogLoading.classList.add('cf-hidden');

            catalogProductGrid.innerHTML = `
                <div class="cf-card" style="grid-column: 1 / -1; text-align:center;">
                    <p class="cf-subtitle">
                        Terjadi kesalahan saat mencari produk.
                    </p>
                </div>
            `;
        }
    }

    catalogSearchInput.addEventListener('input', function () {
        clearTimeout(catalogSearchTimer);

        catalogSearchTimer = setTimeout(() => {
            liveSearchCatalogProducts();
        }, 400);
    });

    catalogCategoryFilter.addEventListener('change', function () {
        liveSearchCatalogProducts();
    });

    catalogResetButton.addEventListener('click', function () {
        catalogSearchInput.value = '';
        catalogCategoryFilter.value = '';
        liveSearchCatalogProducts();
    });
</script>
@endsection