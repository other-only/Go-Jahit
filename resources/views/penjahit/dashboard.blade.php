@extends('panels.penjahit-master')

@section('title', 'Dashboard Penjahit')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl-12 mb-6 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">Selamat Datang, {{ Auth::user()->name }}! 👋</h5>
                                <p class="mb-0">
                                    Selamat bekerja! Kelola toko, produk, dan pesanan Anda di sini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->toko)
            <div class="row">
                <!-- Produk -->
                <div class="col-lg-3 col-md-6 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/icons/logo.png') }}" alt="Produk" class="rounded" width="45">
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Produk</span>
                            <h3 class="card-title mb-2">{{ $produkCount }}</h3>
                            <a href="{{ route('penjahit.produk.index') }}" class="small text-primary fw-semibold">Lihat detail</a>
                        </div>
                    </div>
                </div>

                <!-- Detail -->
                <div class="col-lg-3 col-md-6 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/icons/logo.png') }}" alt="Detail" class="rounded" width="45">
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Detail</span>
                            <h3 class="card-title mb-2">{{ $detailCount }}</h3>
                            <a href="{{ route('penjahit.detail.index') }}" class="small text-primary fw-semibold">Lihat detail</a>
                        </div>
                    </div>
                </div>

                <!-- Pesanan -->
                <div class="col-lg-3 col-md-6 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/icons/logo.png') }}" alt="Pesanan" class="rounded" width="45">
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Pesanan</span>
                            <h3 class="card-title mb-2">{{ $orderCount }}</h3>
                            <a href="{{ route('penjahit.pesanan.index') }}" class="small text-primary fw-semibold">Lihat detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Terbaru -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pesanan Terbaru</h5>
                            <a href="{{ route('penjahit.pesanan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kode Order</th>
                                        <th>Produk</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->kode_order }}</td>
                                            <td>{{ $order->produk->nama_produk }}</td>
                                            <td>{{ formatRupiah($order->total_harga) }}</td>
                                            <td>
                                                <span class="badge bg-label-{{ $order->getStatusColor() }}">{{ $order->getStatusOrder() }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('penjahit.pesanan.detail', $order->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada pesanan masuk.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Widget -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">💬 Pesan Terbaru</h5>
                            <a href="{{ route('penjahit.chat.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            @php
                                $recentChats = auth()->user()->penjahitConversations()
                                    ->with('customer', 'latestMessage')
                                    ->orderBy('last_message_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($recentChats as $chat)
                                <a href="{{ route('penjahit.chat.show', $chat) }}" class="text-decoration-none text-dark d-block border-bottom pb-2 mb-2">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $chat->customer->name }}</strong>
                                        <small class="text-muted">{{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : '' }}</small>
                                    </div>
                                    <small class="text-muted">{{ $chat->latestMessage ? Str::limit($chat->latestMessage->message, 60) : 'Belum ada pesan' }}</small>
                                </a>
                            @empty
                                <p class="text-muted mb-0">Belum ada chat masuk.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h5 class="text-muted mb-3">Anda belum memiliki toko</h5>
                            <p class="mb-0">Silakan hubungi admin untuk membuat toko agar bisa mulai mengelola produk dan pesanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- / Content -->
@endsection
