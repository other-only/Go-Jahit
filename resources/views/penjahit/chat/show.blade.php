@extends('panels.penjahit-master')

@section('title', 'Chat - {{ $conversation->customer->name }}')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header -->
            <div class="card mb-3">
                <div class="card-body d-flex align-items-center">
                    <a href="{{ route('penjahit.chat.index') }}" class="btn btn-sm btn-outline-secondary me-3">&larr;</a>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                        {{ substr($conversation->customer->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $conversation->customer->name }}</h6>
                        @if($conversation->type === 'order' && $conversation->order)
                            <a href="{{ route('penjahit.pesanan.detail', $conversation->order) }}" class="small">Pesanan #{{ $conversation->order->kode_order }}</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="card mb-3" style="height: 60vh; overflow-y: auto;" id="message-container">
                <div class="card-body d-flex flex-column gap-2" id="messages-list">
                    @foreach($conversation->messages as $msg)
                        <div class="d-flex {{ $msg->sender_id === auth()->id() ? 'justify-content-end' : '' }}">
                            <div class="p-3 rounded-3 {{ $msg->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
                                {{ $msg->message }}
                                <div class="small {{ $msg->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }} mt-1">
                                    {{ $msg->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Input -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('penjahit.chat.send', $conversation) }}" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="message" class="form-control" placeholder="Ketik pesan..." required maxlength="5000" autofocus>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Prevent double submit
    document.querySelector('form')?.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Mengirim...';
    });

    const conversationId = {{ $conversation->id }};
    const messagesList = document.getElementById('messages-list');
    const msgContainer = document.getElementById('message-container');
    let lastMessageId = {{ $conversation->messages->last()->id ?? 0 }};

    function scrollToBottom() {
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }

    scrollToBottom();

    setInterval(function() {
        fetch('{{ route("penjahit.chat.messages", $conversation) }}?after=' + lastMessageId)
            .then(res => res.json())
            .then(data => {
                data.messages.forEach(function(msg) {
                    const div = document.createElement('div');
                    div.className = 'd-flex ' + (msg.sender_id !== {{ auth()->id() }} ? '' : 'justify-content-end');
                    div.innerHTML = '<div class="p-3 rounded-3 ' +
                        (msg.sender_id !== {{ auth()->id() }} ? 'bg-light' : 'bg-primary text-white') +
                        '" style="max-width: 75%;">' +
                        e(msg.message) +
                        '<div class="small ' + (msg.sender_id !== {{ auth()->id() }} ? 'text-muted' : 'text-white-50') +
                        ' mt-1">' + new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}) + '</div></div>';
                    messagesList.appendChild(div);
                    lastMessageId = msg.id;
                });
                if (data.messages.length > 0) scrollToBottom();
            })
            .catch(function() {});

        function e(str) {
            const d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }
    }, 8000);
</script>
@endpush
@endsection
