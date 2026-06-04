<div class="mf-form-grid">
    <div class="mf-field">
        <label class="mf-label">
            Kode Barang *
        </label>

        <input type="text"
               name="kode_barang"
               value="{{ old('kode_barang', $product->kode_barang ?? '') }}"
               placeholder="Contoh: MF-001"
               class="mf-input"
               required
               minlength="3"
               maxlength="50"
               data-label="Kode barang"
               data-msg-required="Kode barang wajib diisi. Contoh: MF-001."
               data-msg-minlength="Kode barang minimal 3 karakter."
               data-msg-maxlength="Kode barang maksimal 50 karakter.">

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
               class="mf-input"
               required
               minlength="3"
               maxlength="100"
               data-label="Nama produk"
               data-msg-required="Nama produk wajib diisi."
               data-msg-minlength="Nama produk minimal 3 karakter."
               data-msg-maxlength="Nama produk maksimal 100 karakter.">

        @error('nama_produk')
            <div class="mf-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mf-field">
        <label class="mf-label">
            Kategori *
        </label>

        <select name="kategori"
                class="mf-select"
                required
                data-label="Kategori produk"
                data-msg-required="Kategori produk wajib dipilih.">
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
               class="mf-input"
               required
               min="0"
               step="1"
               data-label="Harga"
               data-msg-required="Harga produk wajib diisi."
               data-msg-number="Harga harus berupa angka."
               data-msg-min="Harga tidak boleh kurang dari 0.">

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
               class="mf-input"
               required
               min="0"
               step="1"
               data-label="Stok"
               data-msg-required="Stok produk wajib diisi."
               data-msg-number="Stok harus berupa angka."
               data-msg-min="Stok tidak boleh kurang dari 0.">

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
               class="mf-input"
               data-label="Tanggal masuk">

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
           accept="image/jpeg,image/png,image/webp"
           class="mf-file"
           data-label="Gambar produk"
           data-max-size="2"
           data-msg-file-type="Gambar produk harus berformat JPG, JPEG, PNG, atau WEBP."
           data-msg-file-size="Ukuran gambar produk maksimal 2MB.">

    <p class="mf-subtitle" style="font-size: 13px; margin-top: 6px;">
        Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2MB.
    </p>

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