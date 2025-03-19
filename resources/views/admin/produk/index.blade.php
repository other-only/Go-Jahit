@extends('panels.master')

@section('title', 'Produk')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header">Daftar Produk</h5>
                @if (auth()->user()->hasRole('penjahit'))
                    <div class="p-3">
                        <a class="btn btn-primary" href="{{ route('admin.produk.create') }}">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span class="text-nowrap">Tambah Produk</span>
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
                            <th>Foto Produk</th>
                            <th>Nama Produk</th>
                            <th>Deskribsi</th>
                            <th>Harga</th>
                            <th>Toko</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($produks as $produk)
                            <tr>
                                <td>
                                    <img src="{{ $produk->getFoto() }}"
                                        alt="{{ Str::slug($produk->nama_produk) . '-' . $produk->id }}" width="100">
                                </td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->deskripsi }}</td>
                                <td>{{ formatRupiah($produk->harga) }}</td>
                                <td>{{ $produk->toko->nama_toko }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('admin.produk.edit', $produk->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        {{--  <form action="#" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>  --}}
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
