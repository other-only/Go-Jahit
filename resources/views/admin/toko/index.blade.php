@extends('panels.master')

@section('title', 'Toko')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Daftar Toko</h5>
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
                            <th>Foto Toko</th>
                            <th>Nama Toko</th>
                            <th>Deskribsi</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($tokos as $toko)
                            <tr>
                                <td>
                                    <img src="{{ $toko->getLogo() }}"
                                        alt="{{ Str::slug($toko->nama_toko) . '-' . $toko->id }}" width="100">
                                </td>
                                <td>{{ $toko->nama_toko }}</td>
                                <td>{{ $toko->deskripsi }}</td>
                                <td>{{ $toko->alamat }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('admin.toko.edit', $toko->id) }}"
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
