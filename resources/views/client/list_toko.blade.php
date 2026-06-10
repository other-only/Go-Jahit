@extends('client.master')

@section('title', 'Pilih Toko')

@section('page-title', 'Pilih Toko')

@section('content')
    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <form action="{{ route('client.belanja') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-lg"
                        placeholder="Cari toko atau alamat..." value="{{ $search ?? '' }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('client.belanja') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div id="store-selection" class="row mb-4">
        @forelse ($tokos as $toko)
            <div class="col-md-4 mb-4">
                <div class="card store-card h-100">
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
                        <a href="{{ route('client.order', ['toko' => $toko->id]) }}" class="btn btn-primary">
                            <i class="bi bi-shop me-1"></i> Kunjungi Toko
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-shop-window display-4 d-block mb-3"></i>
                    @if ($search)
                        <h4>Toko tidak ditemukan</h4>
                        <p class="mb-0">Tidak ada toko yang cocok dengan pencarian "{{ $search }}".</p>
                        <a href="{{ route('client.belanja') }}" class="btn btn-primary mt-3">Lihat Semua Toko</a>
                    @else
                        <h4>Belum ada toko tersedia</h4>
                        <p class="mb-0">Silakan coba kembali nanti.</p>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    @if ($tokos->hasPages())
        <div class="d-flex justify-content-center">
            {{ $tokos->links() }}
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .store-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .store-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-footer {
            border-top: none;
            background-color: transparent;
        }

        .input-group .btn {
            border-radius: 0 6px 6px 0;
        }
    </style>
@endpush
