@extends('panels.penjahit-master')

@section('title', 'Edit Produk')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Produk /</span> Edit Produk
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Produk</h5>

                    <form action="{{ route('penjahit.produk.update', $produk->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <!-- Foto Produk -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="upload">Foto Produk</label>
                                <div class="col-sm-10">
                                    <div class="d-flex align-items-start gap-4 mb-3">
                                        <img src="{{ $produk->getFoto() }}" alt="{{ Str::slug($produk->nama_produk) }}"
                                            class="d-block rounded" height="100" width="100" id="uploadedAvatar" />

                                        <div class="button-wrapper">
                                            <label for="upload" class="btn btn-primary me-2 mb-2" tabindex="0">
                                                <span class="d-none d-sm-block">Upload foto baru</span>
                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                <input type="file" id="upload" name="foto"
                                                    class="account-file-input" hidden accept="image/png, image/jpeg" />
                                            </label>
                                            <button type="button" class="btn btn-outline-secondary mb-2"
                                                onclick="resetFoto()">
                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>

                                            @error('foto')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Preview Area -->
                                    <div id="preview-container" class="mt-3" style="display: none;">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Preview Foto Baru</h6>
                                            </div>
                                            <div class="card-body text-center bg-light">
                                                <img id="preview-image" src="#" alt="Preview Foto"
                                                    class="img-fluid mb-2" style="max-height: 200px; max-width: 100%;">
                                                <div class="mt-2 text-start">
                                                    <table class="table table-sm table-borderless mb-0">
                                                        <tr>
                                                            <td style="width: 100px;"><small class="text-muted">Nama
                                                                    File:</small></td>
                                                            <td><small class="fw-semibold"
                                                                    id="file-name">filename.jpg</small></td>
                                                        </tr>
                                                        <tr>
                                                            <td><small class="text-muted">Ukuran:</small></td>
                                                            <td><small class="fw-semibold"><span id="file-size">0</span>
                                                                    KB</small></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nama Produk -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="nama_produk">Nama Produk</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                        id="nama_produk" name="nama_produk"
                                        value="{{ old('nama_produk', $produk->nama_produk) }}" placeholder="Nama Produk"
                                        required />
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="deskripsi">Deskripsi</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                        placeholder="Deskripsi produk">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Harga -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="harga">Harga</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                            id="harga" name="harga" value="{{ old('harga', $produk->harga) }}"
                                            placeholder="0" min="0" step="1000" />
                                    </div>
                                    <div class="form-text">Masukkan harga dalam Rupiah tanpa titik atau koma</div>
                                    @error('harga')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <a href="{{ route('penjahit.produk.index') }}"
                                        class="btn btn-outline-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadInput = document.getElementById('upload');
            const previewImage = document.getElementById('uploadedAvatar');
            const previewContainer = document.getElementById('preview-container');
            const previewImg = document.getElementById('preview-image');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');

            uploadInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const objectUrl = URL.createObjectURL(file);

                    previewImage.src = objectUrl;
                    previewImg.src = objectUrl;

                    fileName.textContent = file.name;
                    fileSize.textContent = (file.size / 1024).toFixed(2);

                    previewContainer.style.display = 'block';
                }
            });

            const hargaInput = document.getElementById('harga');
            if (hargaInput) {
                hargaInput.addEventListener('blur', function() {
                    let nilai = this.value.replace(/\D/g, '');
                    if (nilai === '') nilai = '0';
                    this.value = nilai;
                });
            }
        });

        function resetFoto() {
            const originalFoto = "{{ $produk->getFoto() }}";
            document.getElementById('uploadedAvatar').src = originalFoto;
            document.getElementById('upload').value = '';

            const previewContainer = document.getElementById('preview-container');
            if (previewContainer) {
                previewContainer.style.display = 'none';
            }
        }
    </script>
@endpush
