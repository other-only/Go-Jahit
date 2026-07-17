@extends('client.master')

@section('title', 'Riwayat Pesanan')

@section('page-title', 'Riwayat Pesanan Saya')

@section('content')
    <div class="row">
        <div class="col-12">

            @if ($errors->has('rating') || $errors->has('ulasan'))
                <div class="alert alert-danger">
                    {{ $errors->first('rating') ?: $errors->first('ulasan') }}
                </div>
            @endif

            @if (count($orders) > 0)
                <!-- Daftar Pesanan -->
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Order</th>
                                        <th>Toko</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('client.order.success', ['order' => $order->id]) }}"
                                                    class="fw-bold text-decoration-none">
                                                    {{ $order->kode_order }}
                                                </a>
                                            </td>
                                            <td>{{ $order->toko->nama_toko }}</td>
                                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($order->status == 'menunggu-konfirmasi')
                                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                                @elseif($order->status == 'dalam-proses')
                                                    <span class="badge bg-info text-dark">Dalam Proses</span>
                                                @elseif($order->status == 'sudah-dikirim')
                                                    <span class="badge bg-primary">Sudah Dikirim</span>
                                                @elseif($order->status == 'selesai')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($order->status == 'batal')
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('client.order.success', ['order' => $order->id]) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('client.order.status', ['order' => $order->kode_order]) }}"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-truck"></i>
                                                    </a>

                                                    @if ($order->status == 'menunggu-konfirmasi')
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmCancel('{{ $order->kode_order }}')">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    @endif

                                                    @if ($order->status == 'selesai')
                                                        @if ($order->rating)
                                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                                title="Rating: {{ $order->rating->rating }} dari 5 bintang"
                                                                disabled>
                                                                <i class="bi bi-star-fill"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ratingModal{{ $order->id }}"
                                                                title="Beri rating">
                                                                <i class="bi bi-star"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center py-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="card shadow-sm">
                    <div class="card-body py-5 text-center">
                        <img src="{{ asset('assets/images/empty-order.svg') }}" alt="Tidak ada pesanan" class="mb-3"
                            style="max-height: 150px">
                        <h4>Belum Ada Pesanan</h4>
                        <p class="text-muted">Anda belum memiliki riwayat pesanan</p>
                        <a href="{{ route('client.belanja') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-cart-plus me-1"></i> Mulai Berbelanja
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi Pembatalan -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                    <p class="text-danger"><small>Pesanan yang sudah dibatalkan tidak dapat dikembalikan.</small></p>

                    <form id="cancelForm" action="{{ route('client.cancel.order') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kode_order" id="cancel_kode_order">
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label">Alasan Pembatalan</label>
                            <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3"
                                placeholder="Berikan alasan pembatalan pesanan" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak Jadi</button>
                    <button type="button" class="btn btn-danger" id="submitCancel">Ya, Batalkan Pesanan</button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($orders as $order)
        @if ($order->status == 'selesai' && !$order->rating)
            <div class="modal fade" id="ratingModal{{ $order->id }}" tabindex="-1"
                aria-labelledby="ratingModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content" action="{{ route('client.order.rating.store', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="ratingModalLabel{{ $order->id }}">Beri Rating Toko</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">Bagaimana pengalaman Anda di <strong>{{ $order->toko->nama_toko }}</strong>?</p>
                            <div class="mb-3">
                                <fieldset class="rating-picker" data-rating-picker>
                                    <legend class="form-label mb-1">Rating</legend>
                                    <p class="small text-muted mb-2" data-rating-label>Pilih jumlah bintang</p>
                                    <div class="d-flex gap-1" role="radiogroup" aria-label="Pilih rating">
                                        @for ($rating = 1; $rating <= 5; $rating++)
                                            <input class="rating-star-input" type="radio" id="rating{{ $order->id }}-{{ $rating }}"
                                                name="rating" value="{{ $rating }}" required>
                                            <label class="rating-star" for="rating{{ $order->id }}-{{ $rating }}"
                                                data-rating="{{ $rating }}" title="{{ $rating }} bintang">
                                                <i class="bi bi-star-fill"></i>
                                                <span class="visually-hidden">{{ $rating }} bintang</span>
                                            </label>
                                        @endfor
                                    </div>
                                </fieldset>
                            </div>
                            <div class="mb-0">
                                <label for="ulasan{{ $order->id }}" class="form-label">Ulasan <span class="text-muted">(opsional)</span></label>
                                <textarea class="form-control" id="ulasan{{ $order->id }}" name="ulasan" rows="3" maxlength="1000"
                                    placeholder="Ceritakan pengalaman Anda"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Kirim Rating</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-rating-picker]').forEach((picker) => {
            const stars = [...picker.querySelectorAll('.rating-star')];
            const inputs = [...picker.querySelectorAll('.rating-star-input')];
            const label = picker.querySelector('[data-rating-label]');

            const paintStars = (value = 0) => {
                stars.forEach((star) => {
                    star.classList.toggle('is-active', Number(star.dataset.rating) <= value);
                });
                label.textContent = value ? `${value} dari 5 bintang` : 'Pilih jumlah bintang';
            };

            picker.addEventListener('mouseover', (event) => {
                const star = event.target.closest('.rating-star');
                if (star) paintStars(Number(star.dataset.rating));
            });

            picker.addEventListener('mouseleave', () => {
                paintStars(Number(inputs.find((input) => input.checked)?.value || 0));
            });

            inputs.forEach((input) => {
                input.addEventListener('change', () => paintStars(Number(input.value)));
            });
        });

        function confirmCancel(kodeOrder) {
            $('#cancel_kode_order').val(kodeOrder);
            $('#cancelModal').modal('show');
        }

        $(document).ready(function() {
            $('#submitCancel').click(function() {
                const reason = $('#cancel_reason').val();
                if (!reason || reason.trim() === '') {
                    alert('Silakan berikan alasan pembatalan pesanan');
                    return;
                }
                $('#cancelForm').submit();
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .rating-picker {
            border: 0;
            padding: 0;
        }

        .rating-star-input {
            position: absolute;
            opacity: 0;
        }

        .rating-star {
            color: #ced4da;
            cursor: pointer;
            font-size: 2rem;
            line-height: 1;
            transition: color .15s ease, transform .15s ease;
        }

        .rating-star:hover,
        .rating-star.is-active {
            color: #ffab00;
        }

        .rating-star:hover {
            transform: scale(1.12);
        }

        .rating-star-input:focus-visible + .rating-star {
            border-radius: 4px;
            outline: 3px solid rgba(105, 108, 255, .35);
            outline-offset: 2px;
        }
    </style>
@endpush
