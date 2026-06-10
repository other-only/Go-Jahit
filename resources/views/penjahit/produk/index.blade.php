@extends('panels.penjahit-master')

@section('title', 'Produk')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header">Daftar Produk</h5>
                <div class="p-3">
                    <a class="btn btn-primary" href="{{ route('penjahit.produk.create') }}">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span class="text-nowrap">Tambah Produk</span>
                    </a>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Foto Produk</th>
                            <th>Nama Produk</th>
                            <th>Deskribsi</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($produks as $produk)
                            <tr>
                                <td>
                                    <img src="{{ $produk->getFoto() }}"
                                        alt="{{ Str::slug($produk->nama_produk) . '-' . $produk->id }}" width="100">
                                </td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->deskripsi }}</td>
                                <td>{{ formatRupiah($produk->harga) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('penjahit.produk.edit', $produk->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('penjahit.produk.delete', $produk->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada produk. <a href="{{ route('penjahit.produk.create') }}">Tambah produk sekarang</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-3">
                {{ $produks->links() }}
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
