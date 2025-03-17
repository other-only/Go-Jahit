@extends('client.master')

@section('content')
    <!-- Booking Code Page -->
    <div id="booking-confirmation" class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Pesanan Berhasil Dibuat!</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title">Terima kasih atas pesanan Anda</h5>
                    <p class="card-text">Silakan simpan kode booking Anda untuk keperluan selanjutnya.</p>

                    <div class="my-4">
                        <div class="bg-light p-4 rounded">
                            <h3 class="booking-code mb-0" id="booking-code">{{ $order->kode_order }}</h3>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary mt-2" onclick="copyBookingCode()">
                            <i class="bi bi-clipboard"></i> Salin Kode
                        </button>
                    </div>

                    <div class="alert alert-info text-start">
                        <strong>Detail Pesanan:</strong>
                        <div id="order-details">
                            <p><strong>Toko:</strong> {{ $order->toko->nama_toko }}</p>
                            <p><strong>Jenis Produk:</strong> {{ $order->produk->nama_produk }}</p>
                            <p><strong>Jenis Kain:</strong> {{ $order->detail->nama_detail }}</p>
                            <p><strong>Jumlah:</strong> {{ $order->jumlah }}</p>
                            <p><strong>Jenis Pembayaran:</strong>
                                {{ $order->jenis_pembayaran == 'transfer' ? 'Transfer Bank' : 'COD (Cash On Delivery)' }}
                            </p>
                            @if ($order->jenis_pembayaran == 'transfer')
                                <p><strong>Bank Tujuan:</strong> {{ strtoupper($order->bank_tujuan) }}</p>
                            @endif
                            <p><strong>Nama Penerima:</strong> {{ $order->nama_penerima }}</p>
                            <p><strong>Alamat Pengiriman:</strong> {{ $order->alamat_penerima }}</p>
                            <p><strong>Nomor Telepon:</strong> {{ $order->no_hp_penerima }}</p>
                            <p><strong>Total Harga:</strong> {{ formatRupiah($order->total_harga) }}</p>
                            <p><strong>Tanggal Pemesanan:</strong> {{ $order->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-2">
                            <div class="d-grid">
                                <a href="{{ route('client.order.status', ['order' => $order->kode_order]) }}"
                                    class="btn btn-outline-success">
                                    Cek Status Pesanan
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="d-grid">
                                <a href="{{ route('client.belanja') }}" class="btn btn-primary">
                                    Buat Pesanan Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyBookingCode() {
            const bookingCode = document.getElementById('booking-code').textContent;

            // Gunakan Clipboard API jika tersedia
            if (navigator.clipboard) {
                navigator.clipboard.writeText(bookingCode)
                    .then(() => {
                        alert('Kode booking berhasil disalin!');
                    })
                    .catch(err => {
                        console.error('Error menyalin kode booking:', err);
                        // Fallback ke metode alternatif jika Clipboard API gagal
                        copyToClipboardFallback(bookingCode);
                    });
            } else {
                // Fallback untuk browser yang tidak mendukung Clipboard API
                copyToClipboardFallback(bookingCode);
            }
        }

        // Metode alternatif untuk menyalin ke clipboard
        function copyToClipboardFallback(text) {
            // Buat elemen temporary
            const textArea = document.createElement('textarea');
            textArea.value = text;

            // Pastikan tidak terlihat
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);

            // Pilih dan salin teks
            textArea.focus();
            textArea.select();

            let success = false;
            try {
                success = document.execCommand('copy');
            } catch (err) {
                console.error('Fallback: Error menyalin ke clipboard', err);
            }

            // Bersihkan
            document.body.removeChild(textArea);

            if (success) {
                alert('Kode booking berhasil disalin!');
            } else {
                alert('Tidak dapat menyalin kode booking. Silakan salin manual.');
            }
        }
    </script>
@endpush
