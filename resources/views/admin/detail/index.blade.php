@extends('panels.master')

@section('title', 'Produk')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Daftar Detail Toko</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Deskribsi</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($details as $detail)
                            <tr>
                                <td>{{ $detail->nama_detail }}</td>
                                <td>{{ $detail->deskripsi }}</td>
                                <td>{{ formatRupiah($detail->harga) }}</td>
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
