@extends('panels.master')

@section('title', 'Tambah Penjahit')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Penjahit /</span> Tambah Penjahit
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Registrasi Penjahit Baru</h5>
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
                    <form action="{{ route('admin.penjahit.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                    <div>
                                        <strong>Informasi</strong>
                                        <p class="mb-0">Setelah pendaftaran berhasil, penjahit akan dapat login
                                            menggunakan email dan password yang didaftarkan.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Personal -->
                            <div class="divider">
                                <div class="divider-text">Informasi Personal</div>
                            </div>

                            <!-- Nama Penjahit -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">Nama Penjahit</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap"
                                        required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="email">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}"
                                        placeholder="email@example.com" required />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="password">Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="••••••" aria-describedby="password" required />
                                        <span class="input-group-text cursor-pointer" onclick="togglePassword('password')">
                                            <i class="bx bx-hide"></i>
                                        </span>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Minimal 8 karakter</div>
                                </div>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="password_confirmation">Konfirmasi
                                    Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password_confirmation" class="form-control"
                                            name="password_confirmation" placeholder="••••••"
                                            aria-describedby="password_confirmation" required />
                                        <span class="input-group-text cursor-pointer"
                                            onclick="togglePassword('password_confirmation')">
                                            <i class="bx bx-hide"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Toko -->
                            <div class="divider">
                                <div class="divider-text">Informasi Toko</div>
                            </div>

                            <!-- Foto Toko -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="upload">Logo Toko</label>
                                <div class="col-sm-10">
                                    <div class="d-flex align-items-start gap-4 mb-3">
                                        <img src="{{ asset('assets/icons/placeholder_produk.jpeg') }}"
                                            alt="Default Product Image" class="d-block rounded" height="100"
                                            width="100" id="uploadedAvatar" />
                                        <div class="button-wrapper">
                                            <label for="upload" class="btn btn-primary me-2 mb-2" tabindex="0">
                                                <span class="d-none d-sm-block">Upload logo</span>
                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                <input type="file" id="upload" name="foto_toko"
                                                    class="account-file-input" hidden accept="image/png, image/jpeg"
                                                    required />
                                            </label>
                                            <button type="button" class="btn btn-outline-secondary mb-2"
                                                onclick="resetFoto()">
                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>

                                            @error('foto_toko')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Preview Area -->
                                    <div id="preview-container" class="mt-3" style="display: none;">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Preview Logo</h6>
                                            </div>
                                            <div class="card-body text-center bg-light">
                                                <img id="preview-image" src="#" alt="Preview Logo"
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

                            <!-- Nama Toko -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="nama_toko">Nama Toko</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('nama_toko') is-invalid @enderror"
                                        id="nama_toko" name="nama_toko" value="{{ old('nama_toko') }}"
                                        placeholder="Nama Toko" required />
                                    @error('nama_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat Toko -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="alamat_toko">Alamat Toko</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('alamat_toko') is-invalid @enderror" id="alamat_toko" name="alamat_toko"
                                        rows="3" placeholder="Alamat lengkap toko" required>{{ old('alamat_toko') }}</textarea>
                                    @error('alamat_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deskripsi Toko -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="deskripsi_toko">Deskripsi Toko</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('deskripsi_toko') is-invalid @enderror" id="deskripsi_toko"
                                        name="deskripsi_toko" rows="4" placeholder="Deskripsi tentang toko">{{ old('deskripsi_toko') }}</textarea>
                                    @error('deskripsi_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- No. WhatsApp -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="no_wa">No. WhatsApp</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('no_wa') is-invalid @enderror"
                                        id="no_wa" name="no_wa" value="{{ old('no_wa') }}"
                                        placeholder="Contoh: 081234567890" required />
                                    @error('no_wa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bank -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="bank">Bank</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('bank') is-invalid @enderror"
                                        id="bank" name="bank" value="{{ old('bank') }}"
                                        placeholder="Contoh: BCA, BNI, Mandiri, dll" required />
                                    @error('bank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- No. Rekening -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="no_rekening">No. Rekening</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('no_rekening') is-invalid @enderror"
                                        id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}"
                                        placeholder="Nomor rekening bank" required />
                                    @error('no_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Atas Nama -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="atas_nama">Atas Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('atas_nama') is-invalid @enderror"
                                        id="atas_nama" name="atas_nama" value="{{ old('atas_nama') }}"
                                        placeholder="Nama pemilik rekening" required />
                                    @error('atas_nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <a href="{{ route('admin.penjahit.index') }}"
                                        class="btn btn-outline-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Daftarkan Penjahit</button>
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
        });

        // Reset foto ke default
        function resetFoto() {
            const defaultFoto = "{{ asset('assets/img/placeholder-shop.png') }}";
            document.getElementById('uploadedAvatar').src = defaultFoto;
            document.getElementById('upload').value = '';

            // Sembunyikan preview
            const previewContainer = document.getElementById('preview-container');
            if (previewContainer) {
                previewContainer.style.display = 'none';
            }
        }

        // Toggle visibility password
        function togglePassword(id) {
            const passwordField = document.getElementById(id);
            const icon = passwordField.nextElementSibling.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
@endpush
