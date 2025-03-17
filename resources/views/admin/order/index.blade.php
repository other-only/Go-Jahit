@extends('panels.master')

@section('title', 'Order')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Daftar Pemesanan</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Order</th>
                            <th>Produk</th>
                            <th>Detail</th>
                            <th>Banyak Pemesanan</th>
                            <th>Total Harga</th>
                            <th>Metode Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->kode_order }}</td>
                                <td>{{ $order->produk->nama_produk }}</td>
                                <td>{{ $order->detail->nama_detail }}</td>
                                <td>{{ $order->jumlah }}</td>
                                <td>{{ formatRupiah($order->total_harga) }}</td>
                                <td>
                                    {{ $order->bayar == 'transfer' ? 'Transfer Bank' : 'COD (Cash On Delivery)' }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="#" class="btn btn-sm btn-primary">Lihat</a>
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
