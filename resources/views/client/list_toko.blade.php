@extends('client.master')

@section('title', 'Pilih Toko')

@section('page-title', 'Pilih Toko')

@section('content')
    <div id="store-selection" class="row mb-4">
        @foreach ($tokos as $toko)
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
        @endforeach
    </div>

    @if (count($tokos) == 0)
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-shop-window display-4 d-block mb-3"></i>
            <h4>Belum ada toko tersedia</h4>
            <p class="mb-0">Silakan coba kembali nanti.</p>
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
    </style>
@endpush
