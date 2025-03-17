@extends('panels.master')

@section('title', 'Produk')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Daftar Toko</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Deskribsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($tokos as $toko)
                            <tr>
                                <td>{{ $toko->nama_toko }}</td>
                                <td>{{ $toko->deskripsi }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
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
