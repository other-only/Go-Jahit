@extends('panels.master')

@section('title', 'Detail Order')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Pemesanan</h5>
                        <a href="{{ route('admin.order.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Kode Order</h6>
                                    <p>{{ $order->kode_order }}</p>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Tanggal Order</h6>
                                    <p>{{ $order->created_at->format('d F Y, H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Status Order</h6>
                                    <span
                                        class="badge bg-label-{{ $order->getStatusColor() }}">{{ $order->getStatusOrder() }}</span>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Metode Pembayaran</h6>
                                    <p>{{ $order->bayar == 'transfer' ? 'Transfer Bank' : 'COD (Cash On Delivery)' }}</p>
                                </div>
                                @if ($order->bayar == 'transfer')
                                    <div class="mb-3">
                                        <h6 class="fw-semibold">Bank Tujuan</h6>
                                        <p>{{ strtoupper($order->nama_bank) }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Nama Penerima</h6>
                                    <p>{{ $order->nama_penerima }}</p>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Nomor Telepon</h6>
                                    <p>{{ $order->no_hp_penerima }}</p>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Alamat Pengiriman</h6>
                                    <p>{{ $order->alamat_penerima }}</p>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-semibold mb-3">Rincian Pesanan</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jenis Kain</th>
                                        <th>Ukuran Baju</th>
                                        <th>Jumlah Baju</th>
                                        <th>Jumlah Kain (meter)</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $order->produk->nama_produk }}</td>
                                        <td>{{ $order->detail->nama_detail }}</td>
                                        <td class="text-uppercase">
                                            {{ $order->ukuran_baju }}
                                        </td>
                                        <td>{{ $order->jumlah_baju }}</td>
                                        <td>{{ $order->jumlah_kain }}</td>
                                        <td>{{ formatRupiah($order->total_harga) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if ($order->bayar == 'transfer')
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-3">Bukti Transfer</h6>
                                    <div class="text-center">
                                        <img src="{{ $order->getBuktiBayar() }}" class="img-fluid img-thumbnail"
                                            style="max-height: 400px;" alt="Bukti Transfer">
                                        <div class="mt-2">
                                            <a href="{{ $order->getBuktiBayar() }}" class="btn btn-primary btn-sm"
                                                target="_blank">
                                                <i class="bx bx-fullscreen me-1"></i> Lihat Gambar Penuh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light border mb-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between flex-wrap">
                                            <div>
                                                <h6 class="fw-semibold mb-0">Status Saat Ini:</h6>
                                                <span
                                                    class="badge bg-label-{{ $order->getStatusColor() }} fs-6 mt-2">{{ $order->getStatusOrder() }}</span>
                                            </div>
                                            <div class="mt-3 mt-md-0">
                                                @if (in_array($order->status, ['menunggu-konfirmasi', 'dalam-proses']) &&
                                                        $order->status != 'selesai' &&
                                                        $order->status != 'batal')
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#updateStatusModal">
                                                        <i class="bx bx-edit me-1"></i> Update Status
                                                    </button>
                                                @endif

                                                @if ($order->status == 'sudah-dikirim')
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#confirmOrderModal">
                                                        <i class="bx bx-check-circle me-1"></i> Konfirmasi Pesanan Selesai
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal untuk update status -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Baru</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                @if ($order->status == 'menunggu-konfirmasi')
                                    <option value="dalam-proses">Dalam Proses</option>
                                @endif
                                @if (in_array($order->status, ['menunggu-konfirmasi', 'dalam-proses']))
                                    <option value="sudah-dikirim">Sudah Dikirim</option>
                                @endif
                                @if (in_array($order->status, ['menunggu-konfirmasi', 'dalam-proses', 'sudah-dikirim']))
                                    <option value="selesai">Selesai</option>
                                @endif
                                @if (!in_array($order->status, ['batal', 'selesai']))
                                    <option value="batal">Dibatalkan</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk konfirmasi pesanan selesai -->
    <div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.order.confirm', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pesanan Selesai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <i class="bx bx-check-circle text-success fs-1"></i>
                            <h4 class="mt-2">Konfirmasi Penyelesaian Pesanan</h4>
                            <p>Apakah Anda yakin ingin mengonfirmasi bahwa pesanan ini telah selesai?</p>
                        </div>
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <i class="bx bx-info-circle me-2 fs-5"></i>
                                <div>
                                    <p class="mb-0">Dengan mengkonfirmasi, status pesanan akan berubah menjadi
                                        <strong>Selesai</strong> dan tidak dapat diubah kembali.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Konfirmasi Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan script tambahan jika diperlukan
        });
    </script>
@endpush
