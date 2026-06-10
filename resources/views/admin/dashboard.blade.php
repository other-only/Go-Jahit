@extends('panels.master')

@section('title', 'Dashboard')
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
                                    Panel administrasi Go-Jahit. Kelola toko, penjahit, dan pantau semua pesanan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/icons/logo.png') }}" alt="Toko" class="rounded" width="45">
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Toko</span>
                        <h3 class="card-title mb-2">{{ $tokoCount }}</h3>
                        <a href="{{ route('admin.toko.index') }}" class="small text-primary fw-semibold">Lihat detail</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/icons/logo.png') }}" alt="Penjahit" class="rounded" width="45">
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Penjahit</span>
                        <h3 class="card-title mb-2">{{ $penjahitCount }}</h3>
                        <a href="{{ route('admin.penjahit.index') }}" class="small text-primary fw-semibold">Lihat detail</a>
                    </div>
                </div>
            </div>

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
                        <a href="{{ route('admin.produk.index') }}" class="small text-primary fw-semibold">Lihat detail</a>
                    </div>
                </div>
            </div>

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
                        <a href="{{ route('admin.order.index') }}" class="small text-primary fw-semibold">Lihat detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Status Pesanan -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-6 mb-6">
                <div class="card h-100 border-warning">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1 text-warning">Menunggu Konfirmasi</span>
                        <h3 class="card-title mb-0">{{ $orderStats['menunggu'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-6">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1 text-info">Dalam Proses</span>
                        <h3 class="card-title mb-0">{{ $orderStats['proses'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-6">
                <div class="card h-100 border-primary">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1 text-primary">Sudah Dikirim</span>
                        <h3 class="card-title mb-0">{{ $orderStats['dikirim'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-6">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1 text-success">Selesai</span>
                        <h3 class="card-title mb-0">{{ $orderStats['selesai'] }}</h3>
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
                        <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Produk</th>
                                    <th>Toko</th>
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
                                        <td>{{ $order->toko->nama_toko }}</td>
                                        <td>{{ formatRupiah($order->total_harga) }}</td>
                                        <td>
                                            <span class="badge bg-label-{{ $order->getStatusColor() }}">{{ $order->getStatusOrder() }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.order.detail', $order->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
