@extends('client.master')

@section('content')
    <!-- Order Status Page -->
    <div id="order-status" class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Status Pesanan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Kode Order: <span id="status-order-code">{{ $order->kode_order }}</span></h5>
                    </div>

                    @php
                        // Tentukan persentase progres berdasarkan status
                        $progressPercentage = 0;
                        $statusSteps = [
                            'menunggu-konfirmasi' => 25,
                            'dalam-proses' => 50,
                            'sudah-dikirim' => 75,
                            'selesai' => 100,
                            'batal' => 0,
                        ];
                        $progressPercentage = $statusSteps[$order->status] ?? 0;

                        // Tentukan warna progress bar
                        $progressBarColor = 'bg-success';
                        if ($order->status == 'batal') {
                            $progressBarColor = 'bg-danger';
                        }
                    @endphp

                    <div class="mb-4">
                        <div class="progress" style="height: 30px;">
                            <div id="order-progress-bar" class="progress-bar {{ $progressBarColor }}" role="progressbar"
                                style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                                {{ $progressPercentage }}%
                            </div>
                        </div>
                    </div>

                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>1. Pesanan Dibuat</strong>
                                <p class="mb-0 text-muted">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="badge bg-success rounded-pill">✓</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>2. Pembayaran Dikonfirmasi</strong>
                                @if (in_array($order->status, ['dalam-proses', 'sudah-dikirim', 'selesai']))
                                    <p class="mb-0 text-muted">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                @elseif($order->status == 'batal')
                                    <p class="mb-0 text-muted">Dibatalkan</p>
                                @else
                                    <p class="mb-0 text-muted">Menunggu konfirmasi</p>
                                @endif
                            </div>
                            @if (in_array($order->status, ['dalam-proses', 'sudah-dikirim', 'selesai']))
                                <span class="badge bg-success rounded-pill">✓</span>
                            @elseif($order->status == 'batal')
                                <span class="badge bg-danger rounded-pill">✕</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">⋯</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>3. Pesanan Diproses</strong>
                                @if (in_array($order->status, ['sudah-dikirim', 'selesai']))
                                    <p class="mb-0 text-muted">
                                        {{ $order->processed_at ? $order->processed_at->format('d M Y, H:i') : '-' }}</p>
                                @elseif($order->status == 'dalam-proses')
                                    <p class="mb-0 text-muted">Sedang diproses</p>
                                @elseif($order->status == 'batal')
                                    <p class="mb-0 text-muted">Dibatalkan</p>
                                @else
                                    <p class="mb-0 text-muted">-</p>
                                @endif
                            </div>
                            @if (in_array($order->status, ['sudah-dikirim', 'selesai']))
                                <span class="badge bg-success rounded-pill">✓</span>
                            @elseif($order->status == 'dalam-proses')
                                <span class="badge bg-warning text-dark rounded-pill">⋯</span>
                            @elseif($order->status == 'batal')
                                <span class="badge bg-danger rounded-pill">✕</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">⋯</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>4. Pesanan Dikirim</strong>
                                @if ($order->status == 'selesai')
                                    <p class="mb-0 text-muted">
                                        {{ $order->shipped_at ? $order->shipped_at->format('d M Y, H:i') : '-' }}</p>
                                @elseif($order->status == 'sudah-dikirim')
                                    <p class="mb-0 text-muted">Sedang dalam pengiriman</p>
                                @elseif($order->status == 'batal')
                                    <p class="mb-0 text-muted">Dibatalkan</p>
                                @else
                                    <p class="mb-0 text-muted">-</p>
                                @endif
                            </div>
                            @if ($order->status == 'selesai')
                                <span class="badge bg-success rounded-pill">✓</span>
                            @elseif($order->status == 'sudah-dikirim')
                                <span class="badge bg-warning text-dark rounded-pill">⋯</span>
                            @elseif($order->status == 'batal')
                                <span class="badge bg-danger rounded-pill">✕</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">⋯</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>5. Pesanan Selesai</strong>
                                @if ($order->status == 'selesai')
                                    <p class="mb-0 text-muted">
                                        {{ $order->completed_at ? $order->completed_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }}
                                    </p>
                                @elseif($order->status == 'batal')
                                    <p class="mb-0 text-muted">Dibatalkan</p>
                                @else
                                    <p class="mb-0 text-muted">-</p>
                                @endif
                            </div>
                            @if ($order->status == 'selesai')
                                <span class="badge bg-success rounded-pill">✓</span>
                            @elseif($order->status == 'batal')
                                <span class="badge bg-danger rounded-pill">✕</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">⋯</span>
                            @endif
                        </li>
                    </ul>

                    @if ($order->status == 'batal')
                        <div class="alert alert-danger mb-4">
                            <h5><i class="bi bi-exclamation-triangle-fill me-2"></i> Pesanan Dibatalkan</h5>
                            <p class="mb-0">{{ $order->cancel_reason ?? 'Pesanan ini telah dibatalkan.' }}</p>
                        </div>
                    @endif

                    <div class="d-grid">
                        <a href="{{ route('client.order.success', $order->id) }}" class="btn btn-secondary">
                            Kembali ke Detail Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
