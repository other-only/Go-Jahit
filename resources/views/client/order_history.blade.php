@extends('client.master')

@section('title', 'Riwayat Pesanan')

@section('page-title', 'Riwayat Pesanan Saya')

@section('content')
    <div class="row">
        <div class="col-12">

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
@endsection

@push('scripts')
    <script>
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
