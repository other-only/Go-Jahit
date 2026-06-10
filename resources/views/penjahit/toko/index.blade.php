@extends('panels.penjahit-master')

@section('title', 'Toko Saya')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header">Toko Saya</h5>

            @if ($toko)
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            @if ($toko->logo)
                                <img src="{{ $toko->getLogo() }}" alt="{{ $toko->nama_toko }}" class="img-fluid rounded" width="200">
                            @endif
                        </div>
                        <div class="col-sm-9">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 150px;"><strong>Nama Toko</strong></td>
                                    <td>{{ $toko->nama_toko }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Deskripsi</strong></td>
                                    <td>{{ $toko->deskripsi }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>{{ $toko->alamat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. WhatsApp</strong></td>
                                    <td>{{ $toko->no_wa ? '62' . $toko->no_wa : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bank</strong></td>
                                    <td>{{ $toko->bank ? strtoupper($toko->bank) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Rekening</strong></td>
                                    <td>{{ $toko->no_rekening ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Atas Nama</strong></td>
                                    <td>{{ $toko->atas_nama ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card-body">
                    <div class="alert alert-info">
                        Anda belum memiliki toko. Silakan hubungi admin untuk membuat toko.
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- / Content -->
@endsection
