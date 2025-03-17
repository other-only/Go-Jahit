@extends('panels.master')

@section('title', 'Produk')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Daftar Produk</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
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
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->deskripsi }}</td>
                                <td>{{ formatRupiah($produk->harga) }}</td>
                                <td>{{ $produk->toko->nama_toko }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="#" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
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
