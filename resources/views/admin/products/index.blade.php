@extends('layouts.admin')

@section('content')
<div class="mf-stats-grid">
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon green">🏠</div>
        <p class="mf-stat-label">Indoor</p>
        <h2 class="mf-stat-number green">{{ $stats['indoor'] }}</h2>
        <p class="mf-stat-sub">produk tersedia</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill green" style="width: 75%"></div>
        </div>
        <span class="mf-stat-badge green">Aktif</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon blue">🌳</div>
        <p class="mf-stat-label">Outdoor</p>
        <h2 class="mf-stat-number blue">{{ $stats['outdoor'] }}</h2>
        <p class="mf-stat-sub">produk tersedia</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill blue" style="width: 55%"></div>
        </div>
        <span class="mf-stat-badge blue">Aktif</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon amber">📦</div>
        <p class="mf-stat-label">Total Stok</p>
        <h2 class="mf-stat-number yellow">{{ $stats['total_stok'] }}</h2>
        <p class="mf-stat-sub">pcs keseluruhan</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill amber" style="width: 80%"></div>
        </div>
        <span class="mf-stat-badge amber">Inventaris</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon red">⚠️</div>
        <p class="mf-stat-label">Hampir Habis</p>
        <h2 class="mf-stat-number red">{{ $stats['hampir_habis'] }}</h2>
        <p class="mf-stat-sub">produk perlu restock</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill red" style="width: 18%"></div>
        </div>
        <span class="mf-stat-badge red">Perhatian</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon green">💵</div>
        <p class="mf-stat-label">Nilai Inventaris</p>
        <h2 class="mf-stat-number green" style="font-size:22px; letter-spacing:-.5px">
            Rp {{ number_format($stats['total_inventaris'], 0, ',', '.') }}
        </h2>
        <p class="mf-stat-sub">Nilai seluruh stok</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill green" style="width: 65%"></div>
        </div>
        <span class="mf-stat-badge green">Aset</span>
    </div>
 
</div>

<div class="mf-card">
    <div class="mf-header">
        <h1 class="mf-title">
            Manajemen Produk
        </h1>

        <p class="mf-subtitle">
            Kelola data tanaman hias indoor dan outdoor.
        </p>
    </div>

    <div class="mf-filter-row">
        <input type="text"
               id="adminLiveSearchInput"
               class="mf-input"
               placeholder="Cari nama atau kode barang...">

        <select id="adminLiveCategory" class="mf-select">
            <option value="">Semua Kategori</option>
            <option value="indoor">Indoor</option>
            <option value="outdoor">Outdoor</option>
        </select>

        <button type="button" id="adminResetLiveSearch" class="mf-btn mf-btn-secondary">
            Reset
        </button>
    </div>

    <div id="adminLiveSearchLoading" class="mf-loading mf-hidden">
        Mencari produk...
    </div>

    <div class="mf-table-wrapper">
        <table class="mf-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="adminProductTableBody">
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}"
                                     class="mf-thumb"
                                     alt="{{ $product->nama_produk }}">
                            @else
                                <div class="mf-thumb-placeholder">🌿</div>
                            @endif
                        </td>

                        <td class="mf-total-text">
                            {{ $product->kode_barang }}
                        </td>

                        <td>
                            {{ $product->nama_produk }}
                        </td>

                        <td>
                            Tanaman {{ ucfirst($product->kategori) }}
                        </td>

                        <td>
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ $product->stok }} pcs
                        </td>

                        <td>
                            {{ $product->tanggal_masuk ? \Carbon\Carbon::parse($product->tanggal_masuk)->format('Y-m-d') : '-' }}
                        </td>

                        <td>
                            @if($product->status === 'tersedia')
                                <span class="mf-badge mf-badge-green">Tersedia</span>
                            @elseif($product->status === 'hampir_habis')
                                <span class="mf-badge mf-badge-warning">Hampir Habis</span>
                            @else
                                <span class="mf-badge mf-badge-red">Habis</span>
                            @endif
                        </td>

                        <td>
                            <div class="mf-action-group">
                                @if(Route::has('admin.products.show'))
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       title="Detail produk"
                                       class="mf-icon-btn mf-icon-detail">
                                        👁️
                                    </a>
                                @endif

                                <a href="{{ route('admin.products.edit', $product) }}"
                                   title="Edit produk"
                                   class="mf-icon-btn mf-icon-edit">
                                    ✏️
                                </a>

                                <button type="button"
                                        title="Hapus produk"
                                        onclick="openDeleteModal('{{ route('admin.products.destroy', $product) }}')"
                                        class="mf-icon-btn mf-icon-delete">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="mf-empty">
                            Belum ada produk. Silakan tambah produk terlebih dahulu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="adminProductPagination" class="mf-pagination">
        <div>
            {{ $products->links() }}
        </div>

        <a href="{{ route('admin.products.create') }}" class="mf-btn mf-btn-primary mf-pagination-right">
            + Tambah Produk
        </a>
    </div>
</div>

<div id="deleteModal" class="mf-modal">
    <div class="mf-modal-box">
        <div class="mf-modal-icon">🗑️</div>

        <h2 class="mf-modal-title">
            Konfirmasi Hapus
        </h2>

        <p class="mf-modal-text">
            Yakin ingin menghapus produk ini?
        </p>

        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="mf-modal-actions">
                <button type="button" onclick="closeDeleteModal()" class="mf-btn mf-btn-secondary">
                    Batal
                </button>

                <button type="submit" class="mf-btn mf-btn-danger">
                    Yakin
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');

        form.action = actionUrl;
        modal.classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });

    document.getElementById('deleteModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });

    const adminSearchInput = document.getElementById('adminLiveSearchInput');
    const adminCategoryInput = document.getElementById('adminLiveCategory');
    const adminResetButton = document.getElementById('adminResetLiveSearch');
    const adminTableBody = document.getElementById('adminProductTableBody');
    const adminLoading = document.getElementById('adminLiveSearchLoading');
    const adminPagination = document.getElementById('adminProductPagination');

    const initialTableBodyHTML = adminTableBody.innerHTML;
    const initialPaginationHTML = adminPagination ? adminPagination.innerHTML : '';

    let adminSearchTimer = null;

    function getProductStatusBadge(status) {
        if (status === 'tersedia') {
            return `<span class="mf-badge mf-badge-green">Tersedia</span>`;
        }

        if (status === 'hampir_habis') {
            return `<span class="mf-badge mf-badge-warning">Hampir Habis</span>`;
        }

        return `<span class="mf-badge mf-badge-red">Habis</span>`;
    }

    function getProductImage(product) {
        if (product.gambar) {
            return `
                <img src="${product.gambar}"
                     class="mf-thumb"
                     alt="${product.nama_produk}">
            `;
        }

        return `<div class="mf-thumb-placeholder">🌿</div>`;
    }

    function renderProductRows(products) {
        if (products.length === 0) {
            adminTableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="mf-empty">
                        Produk tidak ditemukan.
                    </td>
                </tr>
            `;
            return;
        }

        adminTableBody.innerHTML = products.map(product => {
            const detailButton = product.detail_url
                ? `
                    <a href="${product.detail_url}"
                       title="Detail produk"
                       class="mf-icon-btn mf-icon-detail">
                        👁️
                    </a>
                  `
                : '';

            return `
                <tr>
                    <td>${getProductImage(product)}</td>

                    <td class="mf-total-text">${product.kode_barang}</td>

                    <td>${product.nama_produk}</td>

                    <td>Tanaman ${product.kategori}</td>

                    <td>${product.harga}</td>

                    <td>${product.stok} pcs</td>

                    <td>${product.tanggal_masuk}</td>

                    <td>${getProductStatusBadge(product.status)}</td>

                    <td>
                        <div class="mf-action-group">
                            ${detailButton}

                            <a href="${product.edit_url}"
                               title="Edit produk"
                               class="mf-icon-btn mf-icon-edit">
                                ✏️
                            </a>

                            <button type="button"
                                    title="Hapus produk"
                                    onclick="openDeleteModal('${product.delete_url}')"
                                    class="mf-icon-btn mf-icon-delete">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async function adminLiveSearchProducts() {
        const keyword = adminSearchInput.value.trim();
        const kategori = adminCategoryInput.value;

        if (keyword === '' && kategori === '') {
            adminTableBody.innerHTML = initialTableBodyHTML;

            if (adminPagination) {
                adminPagination.innerHTML = initialPaginationHTML;
                adminPagination.classList.remove('mf-hidden');
            }

            return;
        }

        adminLoading.classList.remove('mf-hidden');

        if (adminPagination) {
            adminPagination.classList.add('mf-hidden');
        }

        try {
            const response = await fetch("{{ route('admin.products.live-search') }}", {
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

            adminLoading.classList.add('mf-hidden');

            if (data.success) {
                renderProductRows(data.products);
            } else {
                adminTableBody.innerHTML = `
                    <tr>
                        <td colspan="9" class="mf-empty">
                            Data produk gagal dimuat.
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            adminLoading.classList.add('mf-hidden');

            adminTableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="mf-empty">
                        Terjadi kesalahan saat mencari produk.
                    </td>
                </tr>
            `;
        }
    }

    adminSearchInput.addEventListener('input', function () {
        clearTimeout(adminSearchTimer);

        adminSearchTimer = setTimeout(() => {
            adminLiveSearchProducts();
        }, 400);
    });

    adminCategoryInput.addEventListener('change', function () {
        adminLiveSearchProducts();
    });

    adminResetButton.addEventListener('click', function () {
        adminSearchInput.value = '';
        adminCategoryInput.value = '';
        adminLiveSearchProducts();
    });
</script>
@endsection