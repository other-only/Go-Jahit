@extends('panels.penjahit-master')

@section('title', 'Detail Toko')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header">Daftar Detail Toko</h5>
                <div class="p-3">
                    <a class="btn btn-primary" href="{{ route('penjahit.detail.create') }}">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span class="text-nowrap">Tambah Detail</span>
                    </a>
                </div>
            </div>

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
                        @forelse ($details as $detail)
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
                                        <a href="{{ route('penjahit.detail.edit', $detail->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('penjahit.detail.delete', $detail->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus detail ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada detail. <a href="{{ route('penjahit.detail.create') }}">Tambah detail sekarang</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-3">
                {{ $details->links() }}
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
