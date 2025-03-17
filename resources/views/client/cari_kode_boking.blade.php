@extends('client.master')

@section('title', 'Lacak Pesanan')

@section('page-title', 'Lacak Pesanan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Cek Status Pesanan</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Masukkan kode order Anda untuk memeriksa status pesanan terakhir.
                    </p>

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('client.track.order.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="kode_order" class="form-label">Kode Order</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" class="form-control @error('kode_order') is-invalid @enderror"
                                    id="kode_order" name="kode_order" placeholder="Contoh: BK-12345678"
                                    value="{{ old('kode_order') }}" required>
                                @error('kode_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Kode order terdapat pada email konfirmasi atau halaman detail pesanan Anda.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i> Cek Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Cara Melacak Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="text-primary mb-2">
                                <i class="bi bi-1-circle" style="font-size: 2rem;"></i>
                            </div>
                            <h6>Temukan Kode Order</h6>
                            <p class="small text-muted">Kode order ada di email konfirmasi atau halaman pesanan Anda</p>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="text-primary mb-2">
                                <i class="bi bi-2-circle" style="font-size: 2rem;"></i>
                            </div>
                            <h6>Masukkan Kode</h6>
                            <p class="small text-muted">Masukkan kode order Anda pada kolom di atas</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="text-primary mb-2">
                                <i class="bi bi-3-circle" style="font-size: 2rem;"></i>
                            </div>
                            <h6>Lihat Status</h6>
                            <p class="small text-muted">Lihat status pesanan dan perkiraan pengiriman</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted">Butuh bantuan lainnya?</p>
                <a href="" class="btn btn-outline-primary">
                    <i class="bi bi-chat-dots me-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
@endsection
