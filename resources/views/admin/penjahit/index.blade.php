@extends('panels.master')

@section('title', 'Penjahit')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header">Daftar Penjahit</h5>
                <div class="p-3">
                    <a class="btn btn-primary" href="{{ route('admin.penjahit.create') }}">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span class="text-nowrap">Tambah Penjahit</span>
                    </a>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Penjahit</th>
                            <th>Email</th>
                            <th>Nama Toko</th>
                            <th>Alamat Toko</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($penjahits as $penjahit)
                            <tr>
                                <td>{{ $penjahit->name }}</td>
                                <td>{{ $penjahit->email }}</td>
                                <td>{{ $penjahit->toko->nama_toko }}</td>
                                <td>{{ $penjahit->toko->alamat }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
