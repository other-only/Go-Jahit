@extends('panels.master')

@section('title', 'Detail Toko')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-header">Daftar Detail Toko</h5>
                @if (auth()->user()->hasRole('penjahit'))
                    <div class="p-3">
                        <a class="btn btn-primary" href="{{ route('admin.detail.create') }}">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span class="text-nowrap">Tambah Detail</span>
                        </a>
                    </div>
                @endif
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
                            <th>Foto Detail</th>
                            <th>Nama Detail</th>
                            <th>Deskribsi</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($details as $detail)
                            <tr>
                                <td>
                                    <img src="{{ $detail->getFoto() }}"
                                        alt="{{ Str::slug($detail->nama_detail) . '-' . $detail->id }}" width="100">
                                </td>
                                <td>{{ $detail->nama_detail }}</td>
                                <td>{{ $detail->deskripsi }}</td>
                                <td>{{ formatRupiah($detail->harga) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('admin.detail.edit', $detail->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
