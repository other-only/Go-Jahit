# Fitur Chat Pelanggan & Penjahit — Design Spec

**Tanggal:** 2026-06-11
**Status:** Draft
**Project:** Go-Jahit

---

## 1. Ringkasan

Fitur chat antara pelanggan (customer) dan penjahit (tailor). Mendukung dua jenis chat: chat bebas (general) dan chat per pesanan (order-based). Tidak menggunakan real-time WebSocket — cukup polling/refresh berkala.

---

## 2. Tipe Chat

| Tipe | Deskripsi | Inisiasi |
|------|-----------|----------|
| **general** | Chat bebas, tidak terikat pesanan | Pelanggan klik icon chat di daftar toko |
| **order** | Chat dalam konteks pesanan tertentu | Pelanggan klik "Chat" di halaman detail order |

---

## 3. Database

### Tabel: `conversations`

```php
Schema::create('conversations', function (Blueprint $table) {
    $table->id();
    $table->enum('type', ['general', 'order']);
    $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
    $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('penjahit_id')->constrained('users')->cascadeOnDelete();
    $table->timestamp('last_message_at')->nullable();
    $table->timestamps();
});
```

### Tabel: `messages`

```php
Schema::create('messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
    $table->foreignId('sender_id')->constrained('users');
    $table->text('message');
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});
```

### Indexes

- `conversations`: index on `(customer_id, penjahit_id)`, index on `order_id`
- `messages`: index on `(conversation_id, created_at)`

---

## 4. Model & Relasi

### Conversation

```php
class Conversation extends Model
{
    protected $fillable = ['type', 'order_id', 'customer_id', 'penjahit_id', 'last_message_at'];

    public function order()          { return $this->belongsTo(Order::class); }
    public function customer()       { return $this->belongsTo(User::class, 'customer_id'); }
    public function penjahit()       { return $this->belongsTo(User::class, 'penjahit_id'); }
    public function messages()       { return $this->hasMany(Message::class); }
    public function latestMessage()  { return $this->hasOne(Message::class)->latestOfMany(); }
}
```

### Message

```php
class Message extends Model
{
    protected $fillable = ['conversation_id', 'sender_id', 'message', 'read_at'];

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function sender()       { return $this->belongsTo(User::class, 'sender_id'); }
}
```

### User (tambahan)

```php
public function customerConversations() { return $this->hasMany(Conversation::class, 'customer_id'); }
public function penjahitConversations() { return $this->hasMany(Conversation::class, 'penjahit_id'); }
```

### Order (tambahan)

```php
public function chat() { return $this->hasOne(Conversation::class)->where('type', 'order'); }
```

### Toko (tambahan)

```php
public function penjahit() { return $this->belongsTo(User::class, 'penjahit_id'); }
```

---

## 5. Routes

```php
// Client — pelanggan
Route::group(['prefix' => 'client', 'middleware' => ['auth', 'role:pelanggan']], function () {
    Route::get('chat', [ChatController::class, 'index'])->name('client.chat.index');
    Route::get('chat/{conversation}', [ChatController::class, 'show'])->name('client.chat.show');
    Route::get('chat/{conversation}/messages', [ChatController::class, 'fetchMessages'])->name('client.chat.messages');
    Route::post('chat/{conversation}/send', [ChatController::class, 'send'])->name('client.chat.send');
    Route::get('chat/start/{penjahit}', [ChatController::class, 'startGeneral'])->name('client.chat.start');
    Route::get('order/{order}/chat', [ChatController::class, 'startOrder'])->name('client.chat.order');
});

// Penjahit
Route::group(['prefix' => 'penjahit', 'middleware' => ['auth', 'role:penjahit', 'has.toko']], function () {
    Route::get('chat', [PenjahitChatController::class, 'index'])->name('penjahit.chat.index');
    Route::get('chat/{conversation}', [PenjahitChatController::class, 'show'])->name('penjahit.chat.show');
    Route::get('chat/{conversation}/messages', [PenjahitChatController::class, 'fetchMessages'])->name('penjahit.chat.messages');
    Route::post('chat/{conversation}/send', [PenjahitChatController::class, 'send'])->name('penjahit.chat.send');
});
```

### Authorization Policy

- Pelanggan hanya bisa akses conversation dengan `customer_id = auth()->id()`
- Penjahit hanya bisa akses conversation dengan `penjahit_id = auth()->id()`

---

## 6. Controller Logic

### ChatController (Client)

| Method | Logic |
|--------|-------|
| `index()` | Ambil semua conversation milik customer,urutkan by `last_message_at` desc. Return view `client.chat.index`. |
| `show(conversation)` | Ambil messages, tandai `read_at` untuk pesan dari penjahit. Return view `client.chat.show`. |
| `send(conversation)` | Validasi message tidak kosong. Insert ke messages. Update `last_message_at`. Redirect back. |
| `startGeneral(penjahit)` | Cari conversation `type=general` antara customer & penjahit ini. Jika tidak ada, buat baru. Redirect ke `show()`. |
| `startOrder(order)` | Pastikan order milik customer. Cari/ buat conversation `type=order` dengan `order_id`. Redirect ke `show()`. |

### PenjahitChatController

| Method | Logic |
|--------|-------|
| `index()` | Ambil semua conversation milik penjahit, urutkan by `last_message_at` desc. Pass `$unreadCount`. Return view `penjahit.chat.index`. |
| `show(conversation)` | Ambil messages, tandai `read_at` untuk pesan dari customer. Return view `penjahit.chat.show`. |
| `send(conversation)` | Validasi message, insert, update `last_message_at`. Redirect back. |

---

## 7. UI / Halaman

### Client Chat Index (`/client/chat`)

- Layout: 2 panel (daftar percakapan kiri 35%, chat kanan 65%)
- Panel kiri: daftar conversation, masing-masing menampilkan avatar, nama penjahit/nama toko, cuplikan pesan terakhir, timestamp
- Tipe order ditandai dengan label "Pesanan #ORD-xxx"
- Panel kanan: jika ada conversation aktif, tampilkan chat; jika tidak, tampilkan empty state "Pilih percakapan"

### Client Chat Show (`/client/chat/{id}`)

- Header: nama toko/penjahit + label tipe chat + badge "Online"
- Body: bubble chat, pesan sendiri di kanan (biru), pesan lawan di kiri (abu-abu)
- Footer: input text + tombol Kirim
- Auto-scroll ke bawah saat load
- Polling via JS: fetch pesan baru setiap 8 detik

### Penjahit Chat Index (`/penjahit/chat`)

- Struktur sama dengan client, hanya daftar percakapan dari sisi penjahit
- Nama pelanggan ditampilkan, bukan nama toko

### Penjahit Chat Show (`/penjahit/chat/{id}`)

- Sama dengan client show
- Header menampilkan nama pelanggan
- Ada tombol/icon ke halaman pesanan terkait (jika type=order)

### Dashboard Widget (Penjahit)

- Di `penjahit/dashboard.blade.php`: kartu "Pesan Terbaru"
- Menampilkan 3-5 pesan terakhir dari semua conversation
- Badge unread count
- Link "Lihat Semua" → `/penjahit/chat`

### Tombol Chat di Daftar Toko

- Di `client/list_toko.blade.php`: tiap kartu toko ditambah icon chat
- Icon: `bx bx-chat` atau `bi bi-chat-dots`
- Link: `route('client.chat.start', $toko->penjahit_id)`

### Tombol Chat di Halaman Order

- Di halaman status order client: tombol "Chat Pesanan"
- Link: `route('client.chat.order', $order->id)`

---

## 8. Unread System

- **Read at:** Kolom `read_at` di tabel `messages` menandai kapan pesan dibaca oleh penerima
- **Mark read:** Saat user membuka halaman chat, semua pesan dari lawan bicara yang `read_at IS NULL` diupdate
- **Badge unread:** Query count messages where `read_at IS NULL AND sender_id != auth_id`, dalam lingkup conversation milik user
- **Dashboard widget:** Menampilkan pesan terbaru + unread count

---

## 9. Polling JavaScript

```javascript
// Auto-refresh pesan baru setiap 8 detik
let lastMessageId = 0;

// Inisialisasi lastMessageId dari pesan terakhir yang tampil
document.addEventListener('DOMContentLoaded', function() {
    const lastMsg = document.querySelector('.message-item:last-child');
    if (lastMsg) lastMessageId = lastMsg.dataset.messageId;
});

// Polling
setInterval(function() {
    fetch(`/chat/${conversationId}/messages?after=${lastMessageId}`)
        .then(res => res.json())
        .then(data => {
            data.messages.forEach(msg => {
                appendMessage(msg);
                lastMessageId = msg.id;
            });
            scrollToBottom();
        })
        .catch(() => {});
}, 8000);
```

Untuk polling perlu ditambah route GET:
```php
Route::get('chat/{conversation}/messages', [ChatController::class, 'fetchMessages'])->name('chat.messages');
```

---

## 10. File yang Akan Dibuat/Dimodifikasi

### Files to create:

| File | Keterangan |
|------|------------|
| `app/Models/Conversation.php` | Model conversation |
| `app/Models/Message.php` | Model message |
| `app/Http/Controllers/Client/ChatController.php` | Controller chat pelanggan |
| `app/Http/Controllers/Penjahit/ChatController.php` | Controller chat penjahit |
| `database/migrations/xxxx_xx_xx_create_conversations_table.php` | Migration conversations |
| `database/migrations/xxxx_xx_xx_create_messages_table.php` | Migration messages |
| `resources/views/client/chat/index.blade.php` | Halaman chat pelanggan |
| `resources/views/client/chat/show.blade.php` | Detail chat pelanggan |
| `resources/views/penjahit/chat/index.blade.php` | Halaman chat penjahit |
| `resources/views/penjahit/chat/show.blade.php` | Detail chat penjahit |

### Files to modify:

| File | Perubahan |
|------|-----------|
| `routes/web.php` | Tambah routes chat |
| `app/Models/User.php` | Tambah relasi `customerConversations()`, `penjahitConversations()` |
| `app/Models/Order.php` | Tambah relasi `chat()` |
| `app/Models/Toko.php` | Tambah relasi `penjahit()` |
| `resources/views/client/list_toko.blade.php` | Tambah icon chat |
| `resources/views/client/order_history.blade.php` | Tambah tombol chat |
| `resources/views/penjahit/dashboard.blade.php` | Tambah widget chat |
| `resources/views/panels/penjahit-sidebar.blade.php` | Tambah menu Chat + badge unread |

---

## 11. Error Handling

- **Conversation tidak ditemukan:** 404, tampilkan pesan "Percakapan tidak ditemukan"
- **Akses tidak sah:** 403, tampilkan "Anda tidak memiliki akses ke percakapan ini"
- **Pesan kosong:** Validasi server, balik dengan error "Pesan tidak boleh kosong"
- **Order tidak ditemukan / bukan milik customer:** 404/403
- **Penjahit tidak ditemukan:** 404 saat startGeneral
- **Polling gagal:** Silent catch, jangan tampilkan error ke user

---

## 12. Keamanan

- Semua route chat menggunakan middleware `auth`
- Route client chat menggunakan `role:pelanggan`
- Route penjahit chat menggunakan `role:penjahit` + `has.toko`
- Policy/controller memvalidasi bahwa user hanya bisa akses conversation miliknya
- CSRF protection via `@csrf` di form
- Input message di-strip/escape sebelum ditampilkan

---

## 13. Catatan Implementasi

1. Migration dijalankan pertama
2. Model dibuat
3. Route ditambahkan
4. Controller dibuat
5. Views dibuat
6. Modifikasi file existing (sidebar, dashboard, dll)
7. Test manual
