@extends('layouts.admin')

@section('content')
<div class="mf-card mf-card-narrow">
    <div class="mf-header">
        <h1 class="mf-title">
            Edit Produk
        </h1>

        <p class="mf-subtitle">
            Perbarui data produk tanaman hias.
        </p>
    </div>

    <form method="POST"
          action="{{ route('admin.products.update', $product) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.products.form', ['product' => $product])

        <div class="mf-actions">
            <a href="{{ route('admin.products.index') }}" class="mf-btn mf-btn-secondary">
                Batal
            </a>

            <button type="submit" class="mf-btn mf-btn-primary">
                Update Produk
            </button>
        </div>
    </form>
</div>
@endsection