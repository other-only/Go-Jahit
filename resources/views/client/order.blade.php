@extends('client.master')

@section('content')
    <!-- Step 2: Purchase Form -->
    <div id="purchase-form" class="row mb-4">
        <div class="col-md-8 mx-auto">
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
                                                    <input class="form-check-input" type="radio" name="productType"
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
                                    <div class="col-7">Jumlah:</div>
                                    <div class="col-5 text-end"><span id="quantity-display">1</span>x</div>
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
                                <input class="form-check-input" type="radio" name="paymentMethod" id="transfer"
                                    value="transfer" required>
                                <label class="form-check-label" for="transfer">
                                    Transfer Bank
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="cod"
                                    value="cod" required>
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
                                    <p class="mb-0 text-muted"><small>Silakan transfer sesuai dengan total harga ke rekening
                                            di atas</small></p>
                                </div>
                            </div>

                            <input type="hidden" name="bankType" value="{{ $toko->bank }}">

                            <div class="mb-3">
                                <label for="bukti_transfer" class="form-label">Upload Bukti Transfer <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="bukti_transfer" name="bukti_transfer"
                                    accept="image/*">
                                <div class="form-text">Format yang diterima: JPG, PNG, JPEG. Maksimal 2MB.</div>

                                <div class="mt-2" id="image-preview-container" style="display: none;">
                                    <p>Preview:</p>
                                    <img id="image-preview" src="#" alt="Preview bukti transfer"
                                        class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                value="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Pengiriman</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="alert alert-danger" id="form-errors" style="display: none;">
                            <ul id="error-list"></ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-secondary" onclick="backToStores()">Kembali</button>
                            <button type="button" class="btn btn-success" id="submit-order">Kirim Pesanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-section" class="row" style="display: none;">
        <div class="col-md-8 mx-auto text-center">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Fungsi untuk memformat angka ke format rupiah
            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            // Fungsi untuk menghitung total harga
            function calculateTotal() {
                let productPrice = 0;
                let fabricPrice = 0;
                let quantity = parseInt($('#quantity').val()) || 1;

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
                $('#quantity-display').text(quantity);

                // Hitung total harga
                const totalPrice = (productPrice + fabricPrice) * quantity;
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
            });

            // Handle klik pada kartu kain
            $('.fabric-card').click(function() {
                const radioBtn = $(this).find('input[type="radio"]');
                radioBtn.prop('checked', true);
                calculateTotal();
            });

            // Update total ketika input radio berubah
            $('input[name="productType"], input[name="fabricType"]').change(function() {
                calculateTotal();
            });

            // Update total ketika jumlah berubah
            $('#quantity').change(function() {
                calculateTotal();
            }).on('input', function() {
                calculateTotal();
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
                const paymentMethod = $('input[name="paymentMethod"]:checked').val();
                const name = $('#name').val();
                const address = $('#address').val();
                const phone = $('#phone').val();

                const errors = [];

                if (!productType) errors.push('Pilih jenis produk');
                if (!fabricType) errors.push('Pilih jenis kain');
                if (!paymentMethod) errors.push('Pilih metode pembayaran');
                if (!name) errors.push('Masukkan nama penerima');
                if (!address) errors.push('Masukkan alamat pengiriman');
                if (!phone) errors.push('Masukkan nomor telepon');

                // Validasi tambahan untuk pembayaran transfer
                if (paymentMethod === 'transfer') {
                    const buktiTransfer = $('#bukti_transfer')[0].files[0];
                    if (!buktiTransfer) errors.push('Upload bukti transfer');

                    // Validasi ukuran file (maksimal 2MB)
                    if (buktiTransfer && buktiTransfer.size > 2 * 1024 * 1024) {
                        errors.push('Ukuran bukti transfer tidak boleh melebihi 2MB');
                    }

                    // Validasi tipe file
                    if (buktiTransfer) {
                        const acceptedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!acceptedTypes.includes(buktiTransfer.type)) {
                            errors.push('Format bukti transfer harus berupa JPG, PNG, atau JPEG');
                        }
                    }
                }

                if (errors.length > 0) {
                    showErrors(errors);
                    return false;
                }

                return true;
            }

            // Fungsi untuk menampilkan pesan error
            function showErrors(errors) {
                const errorList = $('#error-list');
                errorList.empty();

                errors.forEach(function(error) {
                    errorList.append(`<li>${error}</li>`);
                });

                $('#form-errors').show();

                // Scroll ke pesan error
                $('html, body').animate({
                    scrollTop: $('#form-errors').offset().top - 100
                }, 200);
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
