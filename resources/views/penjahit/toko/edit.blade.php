@extends('panels.penjahit-master')

@section('title', 'Edit Toko')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Toko /</span> Edit Toko
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Toko</h5>

                    <form action="{{ route('penjahit.toko.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <!-- Logo Toko -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="upload">Logo Toko</label>
                                <div class="col-sm-10">
                                    <div class="d-flex align-items-start gap-4 mb-3">
                                        <img src="{{ $toko->getLogo() }}" alt="{{ Str::slug($toko->nama_toko) }}"
                                            class="d-block rounded" height="100" width="100" id="uploadedAvatar" />

                                        <div class="button-wrapper">
                                            <label for="upload" class="btn btn-primary me-2 mb-2" tabindex="0">
                                                <span class="d-none d-sm-block">Upload logo baru</span>
                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                <input type="file" id="upload" name="logo"
                                                    class="account-file-input" hidden accept="image/png, image/jpeg" />
                                            </label>
                                            <button type="button" class="btn btn-outline-secondary mb-2"
                                                onclick="resetLogo()">
                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>

                                            @error('logo')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Preview Area -->
                                    <div id="preview-container" class="mt-3" style="display: none;">
                                        <p class="fw-semibold mb-2">Preview Logo:</p>
                                        <div class="border rounded p-3 bg-light text-center" style="max-width: 300px;">
                                            <img id="preview-image" src="#" alt="Preview Logo" class="img-fluid"
                                                style="max-height: 200px;">
                                        </div>
                                        <div class="text-muted small mt-2">
                                            <span id="file-name"></span> (<span id="file-size"></span> KB)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nama Toko -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="nama_toko">Nama Toko</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('nama_toko') is-invalid @enderror"
                                        id="nama_toko" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}"
                                        placeholder="Nama Toko" required />
                                    @error('nama_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="deskripsi">Deskripsi</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                        placeholder="Deskripsi toko">{{ old('deskripsi', $toko->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="alamat">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                        placeholder="Alamat lengkap toko">{{ old('alamat', $toko->alamat) }}</textarea>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="detectLocation()">
                                            <i class="bx bx-current-location"></i> Gunakan Lokasi Saya
                                        </button>
                                        <small class="text-muted ms-2" id="geocode-status"></small>
                                    </div>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lokasi (Latitude / Longitude) -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Lokasi</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                                id="latitude" name="latitude" value="{{ old('latitude', $toko->latitude) }}"
                                                placeholder="Latitude (opsional)" />
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                                id="longitude" name="longitude" value="{{ old('longitude', $toko->longitude) }}"
                                                placeholder="Longitude (opsional)" />
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        Koordinat lokasi toko. Klik "Gunakan Lokasi Saya" untuk isi otomatis via GPS, atau input manual.
                                        @if(!$toko->latitude || !$toko->longitude)
                                            <br><span class="text-warning fw-semibold">⚠️ Belum diisi — toko tidak akan muncul di pencarian terdekat.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- No. WhatsApp -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="no_wa">No. WhatsApp</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">+62</span>
                                        <input type="text" class="form-control @error('no_wa') is-invalid @enderror"
                                            id="no_wa" name="no_wa" value="{{ old('no_wa', $toko->no_wa) }}"
                                            placeholder="8123456789" />
                                    </div>
                                    <div class="form-text">Format: 8123456789 (tanpa 0 di depan)</div>
                                    @error('no_wa')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informasi Bank -->
                            <div class="divider">
                                <div class="divider-text">Informasi Bank</div>
                            </div>

                            <!-- Bank -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="bank">Bank</label>
                                <div class="col-sm-10">
                                    <select class="form-select @error('bank') is-invalid @enderror" id="bank"
                                        name="bank">
                                        <option value="" disabled>Pilih Bank</option>
                                        <option value="bca" {{ old('bank', $toko->bank) == 'bca' ? 'selected' : '' }}>
                                            BCA</option>
                                        <option value="bni" {{ old('bank', $toko->bank) == 'bni' ? 'selected' : '' }}>
                                            BNI</option>
                                        <option value="bri" {{ old('bank', $toko->bank) == 'bri' ? 'selected' : '' }}>
                                            BRI</option>
                                        <option value="mandiri"
                                            {{ old('bank', $toko->bank) == 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                                    </select>
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
                                        id="no_rekening" name="no_rekening"
                                        value="{{ old('no_rekening', $toko->no_rekening) }}"
                                        placeholder="Nomor rekening" />
                                    @error('no_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nama Pemilik Rekening -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="atas_nama">Atas Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('atas_nama') is-invalid @enderror"
                                        id="atas_nama" name="atas_nama" value="{{ old('atas_nama', $toko->atas_nama) }}"
                                        placeholder="Nama pemilik rekening" />
                                    @error('atas_nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <a href="{{ route('penjahit.toko.index') }}"
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
        // Preview logo yang diupload
        document.addEventListener('DOMContentLoaded', function(e) {
            const uploadInput = document.getElementById('upload');
            const previewImage = document.getElementById('uploadedAvatar');
            const previewContainer = document.getElementById('preview-container');
            const previewImg = document.getElementById('preview-image');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');

            uploadInput.onchange = function() {
                if (uploadInput.files && uploadInput.files[0]) {
                    const file = uploadInput.files[0];
                    const reader = new FileReader();

                    // Update current preview
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;

                        // Update detailed preview
                        previewImg.src = e.target.result;
                        fileName.textContent = file.name;
                        fileSize.textContent = (file.size / 1024).toFixed(2);
                        previewContainer.style.display = 'block';
                    };

                    reader.readAsDataURL(file);
                }
            };
        });

        // Reset logo ke default
        function resetLogo() {
            const originalLogo = "{{ $toko->getLogo() }}";
            document.getElementById('uploadedAvatar').src = originalLogo;
            document.getElementById('upload').value = '';
            document.getElementById('preview-container').style.display = 'none';
        }

        // Deteksi lokasi otomatis via GPS
        function detectLocation() {
            let status = document.getElementById('geocode-status');

            if (!navigator.geolocation) {
                status.innerHTML = '<span class="text-danger">❌ Browser tidak mendukung GPS.</span>';
                return;
            }

            status.innerHTML = '📍 Mendapatkan lokasi...';

            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    document.getElementById('latitude').value = pos.coords.latitude;
                    document.getElementById('longitude').value = pos.coords.longitude;
                    status.innerHTML = '<span class="text-success">✅ Lokasi terdeteksi!</span>';
                },
                function() {
                    status.innerHTML = '<span class="text-danger">❌ Gagal mendapat lokasi. Izinkan akses GPS atau input manual.</span>';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
    </script>
@endpush
