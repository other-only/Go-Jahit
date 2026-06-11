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

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Penjahit</th>
                            <th>Email</th>
                            <th>Nama Toko</th>
                            <th>Alamat Toko</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($penjahits as $penjahit)
                            <tr>
                                <td>{{ $penjahit->name }}</td>
                                <td>{{ $penjahit->email }}</td>
                                <td>{{ $penjahit->toko->nama_toko }}</td>
                                <td>{{ $penjahit->toko->alamat }}</td>
                                <td>
                                    @if($penjahit->toko->latitude && $penjahit->toko->longitude)
                                        <span class="badge bg-label-success">Terverifikasi</span>
                                    @else
                                        <span class="badge bg-label-warning">Belum set lokasi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-3">
                {{ $penjahits->links() }}
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
