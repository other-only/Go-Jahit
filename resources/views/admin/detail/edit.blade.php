@extends('panels.master')

@section('title', 'Edit Detail')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Detail /</span> Edit Detail
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Kain</h5>
                    @if (session('success'))
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('admin.detail.update', $detail->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <!-- Foto Detail -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="upload">Foto Kain</label>
                                <div class="col-sm-10">
                                    <div class="d-flex align-items-start gap-4 mb-3">
                                        <img src="{{ $detail->getFoto() }}" alt="{{ Str::slug($detail->nama_detail) }}"
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

                            <!-- Nama Detail -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="nama_detail">Nama Kain</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('nama_detail') is-invalid @enderror"
                                        id="nama_detail" name="nama_detail"
                                        value="{{ old('nama_detail', $detail->nama_detail) }}" placeholder="Nama Kain"
                                        required />
                                    @error('nama_detail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="deskripsi">Deskripsi</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                        placeholder="Deskripsi jenis kain">{{ old('deskripsi', $detail->deskripsi) }}</textarea>
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
                                            id="harga" name="harga" value="{{ old('harga', $detail->harga) }}"
                                            placeholder="0" min="0" step="1000" />
                                    </div>
                                    <div class="form-text">Masukkan harga tambahan untuk jenis kain ini dalam Rupiah (tanpa
                                        titik atau koma)</div>
                                    @error('harga')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <a href="{{ route('admin.detail.index') }}"
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
            // Elemen-elemen DOM
            const uploadInput = document.getElementById('upload');
            const previewImage = document.getElementById('uploadedAvatar');
            const previewContainer = document.getElementById('preview-container');
            const previewImg = document.getElementById('preview-image');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');

            // Event listener untuk file input
            uploadInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];

                    // Buat URL objek untuk file
                    const objectUrl = URL.createObjectURL(file);

                    // Update preview
                    previewImage.src = objectUrl;
                    previewImg.src = objectUrl;

                    // Update informasi file
                    fileName.textContent = file.name;
                    fileSize.textContent = (file.size / 1024).toFixed(2);

                    // Tampilkan container preview
                    previewContainer.style.display = 'block';
                }
            });

            // Format harga
            const hargaInput = document.getElementById('harga');
            if (hargaInput) {
                hargaInput.addEventListener('blur', function() {
                    // Hapus karakter non-numerik dan pastikan tidak kosong
                    let nilai = this.value.replace(/\D/g, '');
                    if (nilai === '') nilai = '0';

                    // Tampilkan kembali dalam input
                    this.value = nilai;
                });
            }
        });

        // Reset foto ke default
        function resetFoto() {
            const originalFoto = "{{ $detail->getFoto() }}";
            document.getElementById('uploadedAvatar').src = originalFoto;
            document.getElementById('upload').value = '';

            // Sembunyikan preview
            const previewContainer = document.getElementById('preview-container');
            if (previewContainer) {
                previewContainer.style.display = 'none';
            }
        }
    </script>
@endpush
