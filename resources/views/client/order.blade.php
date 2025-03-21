@extends('client.master')

@section('title', 'Formulir Pemesanan')

@section('page-title', 'Form Pemesanan Produk')

@section('content')
    <div class="row">
        <!-- Detail Toko -->
        <div class="col-md-4 mb-4">
            <div class="card store-card sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title text-center mb-0">{{ $toko->nama_toko }}</h5>
                </div>
                <img src="{{ $toko->getLogo() }}" class="card-img-top p-3" alt="{{ $toko->nama_toko }}"
                    style="height: 200px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">{{ $toko->deskripsi }}</p>
                    <div class="store-info mt-3">
                        <div class="d-flex align-items-start mb-2">
                            <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
                            <p class="text-muted mb-0 small">{{ $toko->alamat }}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-whatsapp text-primary me-2"></i>
                            <p class="text-muted mb-0 small">{{ $toko->no_wa }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $toko->no_wa) }}" target="_blank"
                        class="btn btn-success btn-sm">
                        <i class="bi bi-whatsapp me-1"></i> Hubungi Toko
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Pemesanan -->
        <div class="col-md-8">
            <div id="purchase-form" class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form id="orderForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label">Jenis Produk</label>
                                    <div class="row g-3">
                                        @foreach ($produks as $produk)
                                            <div class="col-md-4 col-6">
                                                <div class="card product-card h-100" data-price="{{ $produk->harga }}">
                                                    <img src="{{ $produk->getFoto() }}" class="card-img-top"
                                                        alt="{{ Str::slug($produk->nama_produk) }}">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="productType"
                                                                id="{{ Str::slug($produk->nama_produk) . '-' . $produk->id }}"
                                                                value="{{ $produk->id }}" required
                                                                data-price="{{ $produk->harga }}">
                                                            <label class="form-check-label w-100"
                                                                for="{{ Str::slug($produk->nama_produk) . '-' . $produk->id }}">
                                                                <strong>{{ $produk->nama_produk }}</strong>
                                                                <div class="text-success mt-1">
                                                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Jenis Kain</label>
                                    <div class="row g-3">
                                        @foreach ($details as $detail)
                                            <div class="col-md-4 col-6">
                                                <div class="card fabric-card h-100" data-price="{{ $detail->harga }}">
                                                    <img src="{{ $detail->getFoto() }}" class="card-img-top"
                                                        alt="{{ Str::slug($detail->nama_detail) . '-' . $detail->id }}">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="fabricType"
                                                                id="{{ Str::slug($detail->nama_detail) . '-' . $detail->id }}"
                                                                value="{{ $detail->id }}" required
                                                                data-price="{{ $detail->harga }}">
                                                            <label class="form-check-label w-100"
                                                                for="{{ Str::slug($detail->nama_detail) . '-' . $detail->id }}">
                                                                <strong>{{ $detail->nama_detail }}</strong>
                                                                <div class="text-success mt-1">
                                                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="size" class="form-label">Ukuran Baju</label>
                                    <select class="form-control" id="size" name="size" required>
                                        <option value="" selected disabled>Pilih ukuran</option>
                                        <option value="S">S (Small)</option>
                                        <option value="M">M (Medium)</option>
                                        <option value="L">L (Large)</option>
                                        <option value="XL">XL (Extra Large)</option>
                                        <option value="XXL">XXL (Double Extra Large)</option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="clothing_quantity" class="form-label">Jumlah Baju</label>
                                    <input type="number" class="form-control" id="clothing_quantity"
                                        name="clothing_quantity" min="1" value="1" required>
                                </div>

                                <div class="mb-3">
                                    <label for="fabric_quantity" class="form-label">Jumlah Kain (meter)</label>
                                    <input type="number" class="form-control" id="fabric_quantity" name="fabric_quantity"
                                        min="1" value="1" step="0.5" required>
                                </div>

                                <!-- Replace the summary card with an updated version that includes all the new fields -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Ringkasan Harga</h5>
                                        <div class="row mb-2">
                                            <div class="col-7">Produk:</div>
                                            <div class="col-5 text-end" id="product-price">Rp 0</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-7">Kain:</div>
                                            <div class="col-5 text-end" id="fabric-price">Rp 0</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-7">Jumlah Baju:</div>
                                            <div class="col-5 text-end"><span id="clothing-quantity-display">1</span>x
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-7">Jumlah Kain:</div>
                                            <div class="col-5 text-end"><span id="fabric-quantity-display">1</span> meter
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row fw-bold">
                                            <div class="col-7">Total:</div>
                                            <div class="col-5 text-end" id="total-price">Rp 0</div>
                                        </div>
                                        <input type="hidden" name="total_price" id="total-price-input" value="0">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jenis Pembayaran</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod"
                                            id="transfer" value="transfer" required>
                                        <label class="form-check-label" for="transfer">
                                            Transfer Bank
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod"
                                            id="cod" value="cod" required>
                                        <label class="form-check-label" for="cod">
                                            COD (Cash On Delivery)
                                        </label>
                                    </div>
                                </div>

                                <div id="transferDetails" class="mb-4" style="display: none;">
                                    <div class="card border-info mb-3">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0">Informasi Rekening Toko</h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Bank:</strong> {{ strtoupper($toko->bank) }}</p>
                                            <p><strong>No. Rekening:</strong> {{ $toko->no_rekening }}</p>
                                            <p><strong>Atas Nama:</strong> {{ $toko->atas_nama }}</p>
                                            <p class="mb-0 text-muted"><small>Silakan transfer sesuai dengan total harga ke
                                                    rekening
                                                    di atas</small></p>
                                        </div>
                                    </div>

                                    <input type="hidden" name="bankType" value="{{ $toko->bank }}">

                                    <div class="mb-3">
                                        <label for="bukti_transfer" class="form-label">Upload Bukti Transfer <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="bukti_transfer"
                                            name="bukti_transfer" accept="image/*">
                                        <div class="form-text">Format yang diterima: JPG, PNG, JPEG. Maksimal 2MB.</div>

                                        <div class="mt-2" id="image-preview-container" style="display: none;">
                                            <p>Preview:</p>
                                            <img id="image-preview" src="#" alt="Preview bukti transfer"
                                                class="img-thumbnail" style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Penerima</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $user?->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Pengiriman</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ $user?->alamat }}
                                    </textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="{{ $user?->no_hp }}" required>
                                </div>

                                <div class="alert alert-danger" id="form-errors" style="display: none;">
                                    <ul id="error-list"></ul>
                                </div>

                                @if ($user)
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="backToStores()">Kembali</button>
                                        <button type="button" class="btn btn-success" id="submit-order">Kirim
                                            Pesanan</button>
                                    </div>
                                @else
                                    <span class="text-danger">Anda belum login. Silakan login untuk melanjutkan.</span>
                                    <div class="d-grid gap-2"></div>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="backToStores()">Kembali</button>
                                    <a href="{{ route('login') }}" class="btn btn-success">Login</a>
                        </div>
                        @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loading-section" class="row" style="display: none;">
            <div class="col-12 text-center">
                <div class="card shadow py-5">
                    <div class="card-body">
                        <div class="spinner-border text-success mb-4" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h4>Memproses Pesanan...</h4>
                        <p class="text-muted">Mohon tunggu sebentar, kami sedang memproses pesanan Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-card,
        .fabric-card {
            transition: all 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-card:hover,
        .fabric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .store-card {
            border-radius: 10px;
            overflow: hidden;
        }

        @media (max-width: 767.98px) {
            .sticky-top {
                position: relative !important;
                top: 0 !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Show/hide custom size section based on selection
            $('#size').change(function() {
                if ($(this).val() === 'Custom') {
                    $('#customSizeSection').show();
                } else {
                    $('#customSizeSection').hide();
                }
                hideFieldError('size');
            });

            // Fungsi untuk memformat angka ke format rupiah
            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            // Fungsi untuk menghitung total harga
            function calculateTotal() {
                let productPrice = 0;
                let fabricPrice = 0;
                let clothingQuantity = parseInt($('#clothing_quantity').val()) || 1;
                let fabricQuantity = parseFloat($('#fabric_quantity').val()) || 1;

                // Harga produk
                const selectedProduct = $('input[name="productType"]:checked');
                if (selectedProduct.length > 0) {
                    productPrice = parseFloat(selectedProduct.data('price')) || 0;
                    $('#product-price').text(formatRupiah(productPrice));
                }

                // Harga kain
                const selectedFabric = $('input[name="fabricType"]:checked');
                if (selectedFabric.length > 0) {
                    fabricPrice = parseFloat(selectedFabric.data('price')) || 0;
                    $('#fabric-price').text(formatRupiah(fabricPrice));
                }

                // Update tampilan jumlah
                $('#clothing-quantity-display').text(clothingQuantity);
                $('#fabric-quantity-display').text(fabricQuantity);

                // Hitung total harga (harga produk * jumlah baju + harga kain * jumlah kain)
                const totalPrice = (productPrice * clothingQuantity) + (fabricPrice * fabricQuantity);
                $('#total-price').text(formatRupiah(totalPrice));
                $('#total-price-input').val(totalPrice);

                return totalPrice;
            }

            // Tampilkan/sembunyikan detail transfer ketika radio button diubah
            $('input[name="paymentMethod"]').change(function() {
                if (this.value === 'transfer') {
                    $('#transferDetails').show();
                } else {
                    $('#transferDetails').hide();
                    // Reset bukti transfer ketika metode pembayaran bukan transfer
                    $('#bukti_transfer').val('');
                    $('#image-preview-container').hide();
                }
            });

            // Preview gambar bukti transfer
            $('#bukti_transfer').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#image-preview-container').show();
                    }

                    reader.readAsDataURL(file);
                } else {
                    $('#image-preview-container').hide();
                }
            });

            // Handle klik pada kartu produk
            $('.product-card').click(function() {
                const radioBtn = $(this).find('input[type="radio"]');
                radioBtn.prop('checked', true);
                calculateTotal();

                // Hilangkan pesan error jika ada
                hideFieldError('productType');
            });

            // Handle klik pada kartu kain
            $('.fabric-card').click(function() {
                const radioBtn = $(this).find('input[type="radio"]');
                radioBtn.prop('checked', true);
                calculateTotal();

                // Hilangkan pesan error jika ada
                hideFieldError('fabricType');
            });

            // Update total ketika input radio berubah
            $('input[name="productType"], input[name="fabricType"]').change(function() {
                calculateTotal();

                // Hilangkan pesan error jika ada
                hideFieldError($(this).attr('name'));
            });

            // Update total ketika jumlah berubah
            $('#clothing_quantity').change(function() {
                calculateTotal();
                hideFieldError('clothing_quantity');
            }).on('input', function() {
                calculateTotal();
                hideFieldError('clothing_quantity');
            });

            $('#fabric_quantity').change(function() {
                calculateTotal();
                hideFieldError('fabric_quantity');
            }).on('input', function() {
                calculateTotal();
                hideFieldError('fabric_quantity');
            });

            // Hapus error pada perubahan input
            $('input[name="paymentMethod"]').change(function() {
                hideFieldError('paymentMethod');
            });

            $('#name').on('input', function() {
                hideFieldError('name');
            });

            $('#address').on('input', function() {
                hideFieldError('address');
            });

            $('#phone').on('input', function() {
                hideFieldError('phone');
            });

            $('#bukti_transfer').on('change', function() {
                hideFieldError('bukti_transfer');
            });

            // Handle form submission
            $('#submit-order').click(function() {
                // Validasi form sebelum submit
                if (!validateForm()) {
                    return;
                }

                // Hitung total harga terakhir sebelum mengirim
                const totalPrice = calculateTotal();

                // Tampilkan loading spinner
                $('#purchase-form').hide();
                $('#loading-section').show();

                // Kumpulkan data form
                const formData = new FormData(document.getElementById('orderForm'));

                // tambahkan csrf token
                formData.append('_token', '{{ csrf_token() }}');

                // Kirim data menggunakan AJAX
                $.ajax({
                    url: "{{ route('client.order.post', ['toko' => $toko]) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Sembunyikan loading spinner
                        $('#loading-section').hide();

                        console.log(response);

                        if (response.status) {
                            // Redirect ke halaman konfirmasi dengan booking code
                            window.location.href = response.url;
                        } else {
                            // Tampilkan pesan error
                            showErrors([response.message]);
                            $('#purchase-form').show();
                        }
                    },
                    error: function(xhr) {
                        // Sembunyikan loading spinner
                        $('#loading-section').hide();
                        $('#purchase-form').show();

                        // Tangani error validasi Laravel
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            const errorList = [];

                            for (const key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorList.push(errors[key][0]);

                                    // Tandai field yang error
                                    showFieldError(key, errors[key][0]);
                                }
                            }

                            showErrors(errorList);
                        } else {
                            // Error umum
                            showErrors(['Terjadi kesalahan. Silakan coba lagi nanti.']);
                        }
                    }
                });
            });

            // Fungsi untuk validasi form
            function validateForm() {
                const productType = $('input[name="productType"]:checked').val();
                const fabricType = $('input[name="fabricType"]:checked').val();
                const size = $('#size').val();
                const clothingQuantity = $('#clothing_quantity').val();
                const fabricQuantity = $('#fabric_quantity').val();
                const paymentMethod = $('input[name="paymentMethod"]:checked').val();
                const name = $('#name').val();
                const address = $('#address').val();
                const phone = $('#phone').val();

                const errors = [];
                let isValid = true;

                // Reset semua error
                resetAllErrors();

                if (!productType) {
                    errors.push('Pilih jenis produk');
                    showFieldError('productType', 'Silakan pilih jenis produk');
                    isValid = false;
                }

                if (!fabricType) {
                    errors.push('Pilih jenis kain');
                    showFieldError('fabricType', 'Silakan pilih jenis kain');
                    isValid = false;
                }

                if (!size) {
                    errors.push('Pilih ukuran baju');
                    showFieldError('size', 'Silakan pilih ukuran baju');
                    isValid = false;
                }

                // Add validation for custom size if selected
                if (size === 'Custom') {
                    const lingkarDada = $('#custom_lingkar_dada').val();
                    const lingkarPinggang = $('#custom_lingkar_pinggang').val();
                    const panjangBaju = $('#custom_panjang_baju').val();
                    const panjangLengan = $('#custom_panjang_lengan').val();

                    if (!lingkarDada || !lingkarPinggang || !panjangBaju || !panjangLengan) {
                        errors.push('Isi ukuran custom dengan lengkap');
                        showFieldError('custom_size', 'Semua ukuran custom harus diisi');
                        isValid = false;
                    }
                }

                if (!clothingQuantity || clothingQuantity < 1) {
                    errors.push('Masukkan jumlah baju yang valid');
                    showFieldError('clothing_quantity', 'Jumlah baju harus minimal 1');
                    isValid = false;
                }

                if (!fabricQuantity || fabricQuantity < 1) {
                    errors.push('Masukkan jumlah kain yang valid');
                    showFieldError('fabric_quantity', 'Jumlah kain harus minimal 1 meter');
                    isValid = false;
                }

                if (!paymentMethod) {
                    errors.push('Pilih metode pembayaran');
                    showFieldError('paymentMethod', 'Silakan pilih metode pembayaran');
                    isValid = false;
                }

                if (!name || name.trim() === '') {
                    errors.push('Masukkan nama penerima');
                    showFieldError('name', 'Nama penerima harus diisi');
                    isValid = false;
                }

                if (!address || address.trim() === '') {
                    errors.push('Masukkan alamat pengiriman');
                    showFieldError('address', 'Alamat pengiriman harus diisi');
                    isValid = false;
                }

                if (!phone || phone.trim() === '') {
                    errors.push('Masukkan nomor telepon');
                    showFieldError('phone', 'Nomor telepon harus diisi');
                    isValid = false;
                }

                // Validasi tambahan untuk pembayaran transfer
                if (paymentMethod === 'transfer') {
                    const buktiTransfer = $('#bukti_transfer')[0].files[0];
                    if (!buktiTransfer) {
                        errors.push('Upload bukti transfer');
                        showFieldError('bukti_transfer', 'Silakan upload bukti transfer');
                        isValid = false;
                    } else {
                        // Validasi ukuran file (maksimal 2MB)
                        if (buktiTransfer.size > 2 * 1024 * 1024) {
                            errors.push('Ukuran bukti transfer tidak boleh melebihi 2MB');
                            showFieldError('bukti_transfer', 'Ukuran file maksimal 2MB');
                            isValid = false;
                        }

                        // Validasi tipe file
                        const acceptedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!acceptedTypes.includes(buktiTransfer.type)) {
                            errors.push('Format bukti transfer harus berupa JPG, PNG, atau JPEG');
                            showFieldError('bukti_transfer', 'Format file harus JPG, PNG, atau JPEG');
                            isValid = false;
                        }
                    }
                }

                if (errors.length > 0) {
                    showErrors(errors);
                    scrollToFirstError();
                    return false;
                }

                return isValid;
            }

            // Fungsi untuk menampilkan error pada field
            function showFieldError(fieldName, message) {
                // Tambahkan kelas error dan pesan untuk field
                switch (fieldName) {
                    case 'productType':
                        // Semua product card mendapatkan border merah
                        $('.product-card').addClass('border-danger');
                        // Tambahkan pesan error di bawah label
                        $('label:contains("Jenis Produk")').after(
                            '<div class="text-danger field-error product-error mb-2">' + message + '</div>');
                        break;

                    case 'fabricType':
                        // Semua fabric card mendapatkan border merah
                        $('.fabric-card').addClass('border-danger');
                        // Tambahkan pesan error di bawah label
                        $('label:contains("Jenis Kain")').after(
                            '<div class="text-danger field-error fabric-error mb-2">' + message + '</div>');
                        break;

                    case 'size':
                        $('#size').addClass('is-invalid');
                        $('#size').after('<div class="invalid-feedback field-error">' + message + '</div>');
                        break;

                    case 'custom_size':
                        $('#customSizeSection').addClass('border border-danger');
                        $('#customSizeSection .card-title').after(
                            '<div class="text-danger field-error custom-size-error mb-2">' + message + '</div>');
                        break;

                    case 'paymentMethod':
                        // Tambahkan pesan error di bawah radio buttons
                        $('#cod').parents('.form-check').last().after(
                            '<div class="text-danger field-error payment-error mb-2">' + message + '</div>');
                        break;

                    case 'bukti_transfer':
                        // Tambahkan kelas error pada input file
                        $('#bukti_transfer').addClass('is-invalid');
                        // Tambahkan pesan error
                        $('#bukti_transfer').after('<div class="invalid-feedback field-error">' + message +
                            '</div>');
                        break;

                    default:
                        // Untuk field biasa (name, address, phone, dll)
                        $('#' + fieldName).addClass('is-invalid');
                        $('#' + fieldName).after('<div class="invalid-feedback field-error">' + message + '</div>');
                }
            }

            // Fungsi untuk menghapus error pada field tertentu
            function hideFieldError(fieldName) {
                switch (fieldName) {
                    case 'productType':
                        $('.product-card').removeClass('border-danger');
                        $('.product-error').remove();
                        break;

                    case 'fabricType':
                        $('.fabric-card').removeClass('border-danger');
                        $('.fabric-error').remove();
                        break;

                    case 'size':
                        $('#size').removeClass('is-invalid');
                        $('#size').next('.invalid-feedback').remove();
                        break;

                    case 'custom_size':
                        $('#customSizeSection').removeClass('border border-danger');
                        $('.custom-size-error').remove();
                        break;

                    case 'paymentMethod':
                        $('.payment-error').remove();
                        break;

                    default:
                        $('#' + fieldName).removeClass('is-invalid');
                        $('#' + fieldName).next('.invalid-feedback').remove();
                }
            }

            // Reset semua error
            function resetAllErrors() {
                // Hapus semua pesan error field
                $('.field-error').remove();
                $('.is-invalid').removeClass('is-invalid');
                $('.border-danger').removeClass('border-danger');

                // Hapus error list global
                $('#form-errors').hide();
                $('#error-list').empty();
            }

            // Fungsi untuk menampilkan pesan error global
            function showErrors(errors) {
                const errorList = $('#error-list');
                errorList.empty();

                errors.forEach(function(error) {
                    errorList.append(`<li>${error}</li>`);
                });

                $('#form-errors').show();
            }

            // Scroll ke error pertama
            function scrollToFirstError() {
                // Cari elemen error pertama
                const firstError = $('.field-error').first();
                if (firstError.length) {
                    // Scroll ke elemen tersebut
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 200);
                } else {
                    // Jika tidak ada field error, scroll ke error summary
                    $('html, body').animate({
                        scrollTop: $('#form-errors').offset().top - 100
                    }, 200);
                }
            }

            // Inisialisasi kalkulasi harga saat halaman dimuat
            calculateTotal();
        });

        // Fungsi untuk kembali ke halaman sebelumnya
        function backToStores() {
            window.location.href = "{{ route('client.belanja') }}";
        }
    </script>
@endpush
