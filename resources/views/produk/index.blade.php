@extends('layouts.app')

@section('page-title', 'Manajemen Produk')

@section('content')
<section class="content">
    <div class="content-box">
        <div class="table-header">
            <h2>Daftar Produk</h2>
            <p>Kelola produk tanaman hias Mitra Flora.</p>
        </div>

        <div class="add-wrapper" style="margin-bottom: 20px;">
            <a href="{{ route('produk.create') }}" class="btn-add">➕ Tambah Produk</a>
        </div>

        <div class="table-wrapper">
            <table class="table-produk">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Kode Barang</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->gambar)
                                    <img src="{{ asset('img/' . $product->gambar) }}" class="thumb" alt="{{ $product->nama_produk }}">
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $product->kode_barang }}</td>
                            <td>{{ $product->nama_produk }}</td>
                            <td>{{ $product->kategori }}</td>
                            <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td>{{ $product->stok }}</td>
                            <td>{{ $product->tanggal_masuk->format('Y-m-d') }}</td>

                            <td>
                                @if($product->stok < 5)
                                    <span class="status hampir">Hampir Habis</span>
                                @else
                                    <span class="status tersedia">Tersedia</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('produk.show', $product) }}" class="btn-ubah">Detail</a>
                                <a href="{{ route('produk.edit', $product) }}" class="btn-edit">Edit</a>

                                <form action="{{ route('produk.destroy', $product) }}"
                                      method="POST"
                                      style="display:inline-block;"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:30px;">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection