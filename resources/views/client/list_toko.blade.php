@extends('client.master')


@section('content')
    <div id="store-selection" class="row mb-4">
        @foreach ($tokos as $toko)
            <a href="{{ route('client.order', ['toko' => $toko->id]) }}" class="col-md-4 mb-3">
                <div class="card store-card">
                    <img src="{{ $toko->getLogo() }}" class="card-img-top" alt="{{ $toko->nama_toko }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $toko->nama_toko }}</h5>
                        <p class="card-text">{{ $toko->deskripsi }}</p>
                        {{--  <p class="card-text"><small class="text-muted">Rating: ⭐⭐⭐⭐ (4.2/5)</small></p>  --}}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
