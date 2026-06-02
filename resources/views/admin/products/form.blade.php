<div class="mf-form-grid">
    <div class="mf-field">
        <label class="mf-label">
            Kode Barang *
        </label>

        <input type="text"
               name="kode_barang"
               value="{{ old('kode_barang', $product->kode_barang ?? '') }}"
               placeholder="Contoh: MF-001"
               class="mf-input">

        @error('kode_barang')
            <div class="mf-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mf-field">
        <label class="mf-label">
            Nama Produk *
        </label>

        <input type="text"
               name="nama_produk"
               value="{{ old('nama_produk', $product->nama_produk ?? '') }}"
               placeholder="Contoh: Anggrek Bulan"
               class="mf-input">

        @error('nama_produk')
            <div class="mf-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mf-field">
        <label class="mf-label">
            Kategori *
        </label>

        <select name="kategori" class="mf-select">
            <option value="">-- Pilih Kategori --</option>
            <option value="indoor" @selected(old('kategori', $product->kategori ?? '') === 'indoor')>
                Tanaman Indoor
            </option>
            <option value="outdoor" @selected(old('kategori', $product->kategori ?? '') === 'outdoor')>
                Tanaman Outdoor
            </option>
        </select>

        @error('kategori')
            <div class="mf-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mf-field">
        <label class="mf-label">
            Harga *
        </label>

        <input type="number"
               name="harga"
               value="{{ old('harga', $product->harga ?? '') }}"
               placeholder="Contoh: 150000"
               class="mf-input">

        @error('harga')
            <div class="mf-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mf-field">
        <label class="mf-label">
            Stok *
        </label>

        <input type="number"
               name="stok"
               value="{{ old('stok', $product->stok ?? '') }}"
               placeholder="Contoh: 8"
               class="mf-input">

        @error('stok')
            <div class="mf-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mf-field">
        <label class="mf-label">
            Tanggal Masuk
        </label>

        <input type="date"
               name="tanggal_masuk"
               value="{{ old('tanggal_masuk', $product->tanggal_masuk ?? '') }}"
               class="mf-input">

        @error('tanggal_masuk')
            <div class="mf-error">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mf-field" style="margin-top: 18px;">
    <label class="mf-label">
        Gambar Produk
    </label>

    <input type="file"
           name="gambar"
           accept="image/*"
           class="mf-file">

    @error('gambar')
        <div class="mf-error">{{ $message }}</div>
    @enderror

    @if(isset($product) && $product && $product->gambar)
        <div class="mf-current-image">
            <p>Gambar saat ini:</p>
            <img src="{{ asset('storage/' . $product->gambar) }}"
                 alt="{{ $product->nama_produk }}">
        </div>
    @endif
</div>