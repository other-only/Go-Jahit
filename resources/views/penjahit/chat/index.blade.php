@extends('panels.penjahit-master')

@section('title', 'Chat')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Percakapan</h4>

    <div class="card">
        <div class="card-body p-0">
            @forelse($conversations as $conv)
                <a href="{{ route('penjahit.chat.show', $conv) }}" class="text-decoration-none text-dark">
                    <div class="d-flex align-items-center p-3 border-bottom {{ $conv->latestMessage && $conv->latestMessage->sender_id !== auth()->id() && !$conv->latestMessage->read_at ? 'bg-light' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:45px;height:45px;">
                                {{ substr($conv->customer->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $conv->customer->name }}</strong>
                                <small class="text-muted text-nowrap">
                                    {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '' }}
                                </small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted text-truncate d-block" style="max-width:400px;">
                                    @if($conv->type === 'order' && $conv->order)
                                        <span class="badge bg-info me-1">Pesanan #{{ $conv->order->kode_order }}</span>
                                    @endif
                                    {{ $conv->latestMessage ? Str::limit($conv->latestMessage->message, 60) : 'Belum ada pesan' }}
                                </small>
                                @if($conv->latestMessage && $conv->latestMessage->sender_id !== auth()->id() && !$conv->latestMessage->read_at)
                                    <span class="badge bg-primary rounded-pill">Baru</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-5 text-muted">
                    <p>Belum ada percakapan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
