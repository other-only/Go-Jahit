@extends('client.master')

@section('title', 'Formulir Pemesanan')

@section('page-title', 'Form Pemesanan Produk')

@section('content')
    <div class="row">
        <!-- Detail Toko -->
        <div class="col-md-4 mb-4">
            <div class="card store-card sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title text-center mb-0 serif-heading">{{ $toko->nama_toko }}</h5>
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
            <div id="purchase-form">
                <div class="card">
                    <div class="card-body">
                        <form id="orderForm" enctype="multipart/form-data">
                            @csrf

                            <!-- Produk -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Jenis Produk</label>
                                @if ($produks->count() > 0)
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
                                                                <div class="text-success mt-1 small">
                                                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted small">Belum ada produk tersedia dari toko ini.</p>
                                @endif
                            </div>

                            <!-- Kain -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Jenis Kain</label>
                                @if ($details->count() > 0)
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
                                                                <div class="text-success mt-1 small">
                                                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted small">Belum ada kain tersedia dari toko ini.</p>
                                @endif
                            </div>

                            <!-- Ukuran -->
                            <div class="mb-3">
                                <label for="size" class="form-label fw-semibold">Ukuran Baju</label>
                                <select class="form-select" id="size" name="size" required>
                                    <option value="" selected disabled>Pilih ukuran</option>
                                    <option value="S">S (Small)</option>
                                    <option value="M">M (Medium)</option>
                                    <option value="L">L (Large)</option>
                                    <option value="XL">XL (Extra Large)</option>
                                    <option value="XXL">XXL (Double Extra Large)</option>
                                </select>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="clothing_quantity" class="form-label fw-semibold">Jumlah Baju</label>
                                    <input type="number" class="form-control" id="clothing_quantity"
                                        name="clothing_quantity" min="1" value="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="fabric_quantity" class="form-label fw-semibold">Jumlah Kain (meter)</label>
                                    <input type="number" class="form-control" id="fabric_quantity" name="fabric_quantity"
                                        min="1" value="1" step="0.5" required>
                                </div>
                            </div>

                            <!-- Ringkasan Harga -->
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3">Ringkasan Harga</h6>
                                    <div class="row mb-2 small">
                                        <div class="col-7 text-muted">Produk:</div>
                                        <div class="col-5 text-end" id="product-price">Rp 0</div>
                                    </div>
                                    <div class="row mb-2 small">
                                        <div class="col-7 text-muted">Kain:</div>
                                        <div class="col-5 text-end" id="fabric-price">Rp 0</div>
                                    </div>
                                    <div class="row mb-2 small">
                                        <div class="col-7 text-muted">Jumlah Baju:</div>
                                        <div class="col-5 text-end"><span id="clothing-quantity-display">1</span>x</div>
                                    </div>
                                    <div class="row mb-2 small">
                                        <div class="col-7 text-muted">Jumlah Kain:</div>
                                        <div class="col-5 text-end"><span id="fabric-quantity-display">1</span> meter</div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row fw-bold">
                                        <div class="col-7">Total:</div>
                                        <div class="col-5 text-end" id="total-price">Rp 0</div>
                                    </div>
                                    <input type="hidden" name="total_price" id="total-price-input" value="0">
                                </div>
                            </div>

                            <!-- Pembayaran -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Metode Pembayaran</label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod"
                                            id="transfer" value="transfer" required>
                                        <label class="form-check-label" for="transfer">Transfer Bank</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod"
                                            id="cod" value="cod" required>
                                        <label class="form-check-label" for="cod">COD (Cash On Delivery)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Transfer Details -->
                            <div id="transferDetails" class="mb-4" style="display: none;">
                                <div class="card border-info mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Informasi Rekening Toko</h6>
                                    </div>
                                    <div class="card-body small">
                                        <p class="mb-1"><strong>Bank:</strong> {{ strtoupper($toko->bank) }}</p>
                                        <p class="mb-1"><strong>No. Rekening:</strong> {{ $toko->no_rekening }}</p>
                                        <p class="mb-0"><strong>Atas Nama:</strong> {{ $toko->atas_nama }}</p>
                                        <p class="mb-0 text-muted mt-2"><small>Silakan transfer sesuai total harga ke rekening di atas</small></p>
                                    </div>
                                </div>
                                <input type="hidden" name="bankType" value="{{ $toko->bank }}">
                                <div class="mb-3">
                                    <label for="bukti_transfer" class="form-label fw-semibold">Upload Bukti Transfer <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="bukti_transfer"
                                        name="bukti_transfer" accept="image/*">
                                    <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                                    <div class="mt-2" id="image-preview-container" style="display: none;">
                                        <p class="small text-muted">Preview:</p>
                                        <img id="image-preview" src="#" alt="Preview bukti transfer"
                                            class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Data Penerima -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Nama Penerima</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $user?->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="{{ $user?->no_hp }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Alamat Pengiriman</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required>{{ $user?->alamat }}</textarea>
                            </div>

                            <!-- Error Summary -->
                            <div class="alert alert-danger d-none" id="form-errors" role="alert">
                                <ul id="error-list" class="mb-0 small"></ul>
                            </div>

                            <!-- Actions -->
                            @if ($user)
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="backToStores()">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </button>
                                    <button type="button" class="btn btn-primary" id="submit-order">
                                        <i class="bi bi-send me-1"></i> Kirim Pesanan
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center gap-2 mb-3" role="alert">
                                    <i class="bi bi-info-circle"></i>
                                    <span>Silakan login untuk melanjutkan pemesanan.</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="backToStores()">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </button>
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loading-section" class="row" style="display: none;">
                <div class="col-12 text-center">
                    <div class="card py-5">
                        <div class="card-body">
                            <div class="spinner-border mb-4" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5 class="fw-semibold">Memproses Pesanan...</h5>
                            <p class="text-muted small">Mohon tunggu sebentar, kami sedang memproses pesanan Anda.</p>
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
        }

        .product-card:hover,
        .fabric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--signal-violet);
        }

        .product-card.selected,
        .fabric-card.selected {
            border: 2px solid var(--signal-violet);
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

        @media (prefers-reduced-motion: reduce) {
            .product-card,
            .fabric-card {
                transition: none;
            }
            .product-card:hover,
            .fabric-card:hover {
                transform: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            function calculateTotal() {
                let productPrice = 0;
                let fabricPrice = 0;
                let clothingQuantity = parseInt($('#clothing_quantity').val()) || 1;
                let fabricQuantity = parseFloat($('#fabric_quantity').val()) || 1;

                const selectedProduct = $('input[name="productType"]:checked');
                if (selectedProduct.length > 0) {
                    productPrice = parseFloat(selectedProduct.data('price')) || 0;
                    $('#product-price').text(formatRupiah(productPrice));
                }

                const selectedFabric = $('input[name="fabricType"]:checked');
                if (selectedFabric.length > 0) {
                    fabricPrice = parseFloat(selectedFabric.data('price')) || 0;
                    $('#fabric-price').text(formatRupiah(fabricPrice));
                }

                $('#clothing-quantity-display').text(clothingQuantity);
                $('#fabric-quantity-display').text(fabricQuantity);

                const totalPrice = (productPrice * clothingQuantity) + (fabricPrice * fabricQuantity);
                $('#total-price').text(formatRupiah(totalPrice));
                $('#total-price-input').val(totalPrice);
                return totalPrice;
            }

            // Payment method toggle
            $('input[name="paymentMethod"]').change(function() {
                if (this.value === 'transfer') {
                    $('#transferDetails').show();
                } else {
                    $('#transferDetails').hide();
                    $('#bukti_transfer').val('');
                    $('#image-preview-container').hide();
                }
            });

            // Preview bukti transfer
            $('#bukti_transfer').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#image-preview-container').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#image-preview-container').hide();
                }
            });

            // Card select
            $('.product-card, .fabric-card').on('click', function() {
                const radio = $(this).find('input[type="radio"]');
                radio.prop('checked', true);
                $(this).closest('.row').find('.product-card, .fabric-card').removeClass('selected');
                $(this).addClass('selected');
                calculateTotal();
            });

            // Quantity changes
            $('#clothing_quantity, #fabric_quantity').on('input change', function() {
                calculateTotal();
            });

            // Submit
            $('#submit-order').click(function() {
                // Validate
                const required = {
                    'productType': 'Pilih jenis produk',
                    'fabricType': 'Pilih jenis kain',
                    'size': 'Pilih ukuran baju',
                    'paymentMethod': 'Pilih metode pembayaran',
                    'name': 'Masukkan nama penerima',
                    'address': 'Masukkan alamat pengiriman',
                    'phone': 'Masukkan nomor telepon'
                };

                let errors = [];
                $('#form-errors').addClass('d-none');
                $('#error-list').empty();
                $('.is-invalid').removeClass('is-invalid');

                for (const [field, msg] of Object.entries(required)) {
                    const val = $(`[name="${field}"]:checked, [name="${field}"]`).val();
                    if (!val || val.trim === '') {
                        errors.push(msg);
                        $(`[name="${field}"]`).addClass('is-invalid');
                    }
                }

                const paymentMethod = $('input[name="paymentMethod"]:checked').val();
                if (paymentMethod === 'transfer') {
                    const file = $('#bukti_transfer')[0].files[0];
                    if (!file) {
                        errors.push('Upload bukti transfer');
                        $('#bukti_transfer').addClass('is-invalid');
                    } else if (file.size > 2 * 1024 * 1024) {
                        errors.push('Ukuran bukti transfer maksimal 2MB');
                        $('#bukti_transfer').addClass('is-invalid');
                    }
                }

                if (errors.length > 0) {
                    errors.forEach(e => $('#error-list').append(`<li>${e}</li>`));
                    $('#form-errors').removeClass('d-none');
                    $('html, body').animate({ scrollTop: $('#form-errors').offset().top - 100 }, 200);
                    return;
                }

                // Submit
                $('#purchase-form').hide();
                $('#loading-section').show();

                const formData = new FormData(document.getElementById('orderForm'));
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('client.order.post', ['toko' => $toko]) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#loading-section').hide();
                        if (response.status) {
                            window.location.href = response.url;
                        } else {
                            errors = [response.message || 'Terjadi kesalahan. Silakan coba lagi.'];
                            errors.forEach(e => $('#error-list').append(`<li>${e}</li>`));
                            $('#form-errors').removeClass('d-none');
                            $('#purchase-form').show();
                        }
                    },
                    error: function(xhr) {
                        $('#loading-section').hide();
                        $('#purchase-form').show();
                        if (xhr.status === 422) {
                            const errs = xhr.responseJSON.errors;
                            const list = [];
                            for (const key in errs) {
                                errs[key].forEach(m => list.push(m));
                                $(`[name="${key}"]`).addClass('is-invalid');
                            }
                            list.forEach(e => $('#error-list').append(`<li>${e}</li>`));
                            $('#form-errors').removeClass('d-none');
                        } else {
                            $('#error-list').append('<li>Terjadi kesalahan server. Silakan coba lagi.</li>');
                            $('#form-errors').removeClass('d-none');
                        }
                        $('html, body').animate({ scrollTop: $('#form-errors').offset().top - 100 }, 200);
                    }
                });
            });
        });

        function backToStores() {
            window.location.href = "{{ route('client.belanja') }}";
        }
    </script>
@endpush
