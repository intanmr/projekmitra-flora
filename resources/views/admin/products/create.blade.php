@extends('layouts.admin')

@section('content')
<div class="mf-card mf-card-narrow">
    <div class="mf-header">
        <h1 class="mf-title">
            Input Produk
        </h1>

        <p class="mf-subtitle">
            Tambahkan data tanaman hias baru ke katalog Mitra Flora.
        </p>
    </div>

    <form method="POST"
          action="{{ route('admin.products.store') }}"
          enctype="multipart/form-data">
        @csrf

        @include('admin.products.form', ['product' => null])

        <div class="mf-actions">
            <a href="{{ route('admin.products.index') }}" class="mf-btn mf-btn-secondary">
                Batal
            </a>

            <button type="submit" class="mf-btn mf-btn-primary">
                Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection