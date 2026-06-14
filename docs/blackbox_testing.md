# Blackbox Testing — Go-Jahit

Dokumentasi skenario blackbox testing untuk aplikasi Go-Jahit (platform jahit online). Pengujian dilakukan menggunakan Laravel Feature Tests dengan pendekatan blackbox, yaitu menguji fungsionalitas sistem dari perspektif pengguna tanpa mengetahui implementasi internal kode.

**Total Test:** 99 passed, 0 failed (162 assertions) — 2 detik eksekusi.

---

## 1. Konfigurasi Lingkungan Test

| Item | Konfigurasi |
|------|-------------|
| **Database** | SQLite `:memory:` |
| **Cache** | `array` driver |
| **Session** | `array` driver |
| **Queue** | `sync` driver |
| **Mail** | `array` driver |
| **BCRYPT rounds** | 4 (mempercepat hashing) |
| **Base TestCase** | RefreshDatabase + role seeder |
| **PHPUnit** | v11 |
| **Laravel** | v12 |

---

## 2. Skenario Blackbox Testing

### 2.1 Modul Autentikasi (Login, Register, Logout)

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| A-01 | Pengunjung membuka halaman login | GET `/login` | Response 200, menampilkan form login | Valid |
| A-02 | Pengunjung login dengan data valid (role pelanggan) | POST `/login` email=pelanggan@test.com password=password | Redirect ke `/client/belanja`, session terisi | Valid |
| A-03 | Pengunjung login dengan data valid (role penjahit) | POST `/login` email=penjahit@test.com password=password | Redirect ke `/penjahit/dashboard` | Valid |
| A-04 | Pengunjung login dengan data valid (role admin) | POST `/login` email=admin@test.com password=password | Redirect ke `/admin/dashboard` | Valid |
| A-05 | Pengunjung login dengan password salah | POST `/login` email=pelanggan@test.com password=salah | Redirect balik dengan pesan error "Email atau password salah." | Valid |
| A-06 | Pengunjung login dengan email tidak terdaftar | POST `/login` email=unknown@test.com password=password | Redirect balik dengan pesan error "Email atau password salah." | Valid |
| A-07 | Pengunjung login tanpa mengisi email | POST `/login` email="" password="password" | Error validasi: email wajib diisi | Valid |
| A-08 | Pengunjung login tanpa mengisi password | POST `/login` email=pelanggan@test.com password="" | Error validasi: password wajib diisi | Valid |
| A-09 | Pengguna sudah login membuka halaman login | GET `/login` (sudah autentikasi) | Redirect ke `/dashboard` (default Laravel, bukan role-based) | Valid |
| A-10 | Pengunjung membuka halaman register | GET `/register` | Response 200, menampilkan form register | Valid |
| A-11 | Pengunjung register dengan data valid | POST `/register` name=nama email=baru@test.com password=secret1234 password_confirmation=secret1234 alamat=alamat no_hp=08123456789 terms=true | User terdaftar dengan role pelanggan, redirect ke `/client/belanja` | Valid |
| A-12 | Register dengan email yang sudah terdaftar | POST `/register` email=existing@test.com | Error validasi: email sudah terdaftar | Valid |
| A-13 | Register dengan password kurang dari 8 karakter | POST `/register` password=short | Error validasi: password minimal 8 karakter | Valid |
| A-14 | Register dengan konfirmasi password tidak cocok | POST `/register` password=secret1234 password_confirmation=berbeda | Error validasi: konfirmasi password tidak cocok | Valid |
| A-15 | Register tanpa menyetujui terms | POST `/register` terms=false | Error validasi: terms wajib dicentang | Valid |
| A-16 | Register dengan format no_hp tidak valid | POST `/register` no_hp=abc123 | Error validasi: format no_hp tidak valid | Valid |
| A-17 | Pengguna logout | GET `/logout` | Session dihapus, redirect ke `/login` | Valid |

### 2.2 Modul Belanja (Client)

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| B-01 | Pengunjung melihat daftar toko | GET `/client/belanja` | Response 200, menampilkan daftar toko (12 per halaman) | Valid |
| B-02 | Pengunjung mencari toko berdasarkan nama | GET `/client/belanja?search=nama_toko` | Response 200, menampilkan toko yang cocok | Valid |
| B-03 | Pengunjung membuka form pemesanan toko | GET `/client/order/{toko}` | Response 200, menampilkan produk & detail dari toko tersebut | Valid |
| B-04 | Pengunjung submit pemesanan dengan data valid | POST `/client/order/{toko}` productType=X fabricType=Y clothing_quantity=1 name=Nama address=Alamat phone=08123 paymentMethod=transfer total_price=50000 | Order tersimpan di database, response JSON 200 dengan URL halaman sukses | Valid |
| B-05 | Order memiliki kode booking format yang benar | Cek kode_order setelah submit | Format: `BK-XXXXXXXX` (8 digit angka setelah BK-) | Valid |
| B-06 | Status order default "menunggu-konfirmasi" | Cek status setelah submit | Status = "menunggu-konfirmasi" | Valid |
| B-07 | Submit order dengan data tidak lengkap | POST `/client/order/{toko}` tanpa field wajib | Error validasi: field wajib harus diisi (redirect back + session errors) | Valid |
| B-08 | Pengunjung melihat halaman sukses order (via JSON redirect dari frontend) | GET `/client/order/{order}/success` | Response 200, menampilkan kode booking | Valid |
| B-09 | Pengunjung membuka halaman lacak order | GET `/client/track/order` | Response 200, menampilkan form input kode booking | Valid |
| B-10 | Pengunjung melacak order dengan kode booking valid | POST `/client/track/order` kode_order=BK-XXXXXXXX | Menampilkan status order | Valid |
| B-11 | Pengunjung melacak order dengan kode booking tidak valid | POST `/client/track/order` kode_order=BK-INVALID | Redirect balik dengan pesan error "Kode booking tidak ditemukan." | Valid |
| B-12 | Pengguna terautentikasi melihat riwayat order | GET `/client/orders` (sebagai pelanggan) | Response 200, menampilkan daftar order miliknya | Valid |
| B-13 | Pelanggan membatalkan order yang masih "dalam-proses"/"menunggu-konfirmasi" | POST `/client/cancel/order` kode_order=BK-XXXXXXXX (status=menunggu-konfirmasi) | Status berubah menjadi "batal" | Valid |
| B-14 | Pelanggan membatalkan order yang sudah "selesai" | POST `/client/cancel/order` kode_order=BK-XXXXXXXX (status=selesai) | Gagal, muncul error "Order dengan status Selesai tidak dapat dibatalkan." | Valid |

### 2.3 Modul Chat (Pelanggan)

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| C-01 | Pelanggan membuka daftar chat | GET `/client/chat` | Response 200, menampilkan daftar percakapan | Valid |
| C-02 | Pelanggan memulai chat umum dengan penjahit | GET `/client/chat/start/{penjahit}` | Percakapan baru dibuat (type: general), redirect ke halaman chat | Valid |
| C-03 | Pelanggan mengirim pesan | POST `/client/chat/{conversation}/send` message=Halo | Pesan tersimpan, response sukses | Valid |
| C-04 | Pelanggan mengambil pesan baru (AJAX) | GET `/client/chat/{conversation}/messages?after={lastId}` | Response JSON berisi pesan-pesan baru | Valid |
| C-05 | Pelanggan membuka chat spesifik | GET `/client/chat/{conversation}` | Response 200, pesan ditandai sudah dibaca | Valid |
| C-06 | Pelanggan memulai chat terkait order | GET `/client/order/{order}/chat` | Percakapan baru (type: order) atau lanjutkan yang sudah ada | Valid |
| C-07 | Pengunjung (guest) mengakses halaman chat | GET `/client/chat` (belum login) | Redirect ke `/login` | Valid |

### 2.4 Modul Admin — Dashboard

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| D-01 | Admin membuka dashboard | GET `/admin/dashboard` | Response 200, menampilkan statistik (total toko, penjahit, produk, order) | Valid |

### 2.5 Modul Admin — Manajemen Toko

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| E-01 | Admin melihat daftar toko | GET `/admin/seting/toko` | Response 200, menampilkan daftar toko (10 per halaman) | Valid |
| E-02 | Admin membuka form edit toko | GET `/admin/seting/toko/edit/{toko}` | Response 200, menampilkan form | Valid |
| E-03 | Admin mengupdate toko dengan data valid | POST `/admin/seting/toko/update/{toko}` nama_toko=Baru deskripsi=Desc alamat=Alamat no_wa=08123 bank=bri no_rekening=12345 atas_nama=Testing | Data toko berubah, redirect | Valid |
| E-04 | Admin mengupdate toko dengan bank tidak valid | POST update bank=invalid | Error validasi: bank harus bca/bni/bri/mandiri | Valid |
| E-05 | Admin mengupdate toko tanpa data wajib | POST update tanpa nama_toko | Error validasi: nama_toko wajib diisi | Valid |

### 2.6 Modul Admin — Manajemen Produk

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| F-01 | Admin melihat daftar produk | GET `/admin/seting/produk` | Response 200 | Valid |
| F-02 | Admin membuka form tambah produk | GET `/admin/seting/produk/add` | Response 200 | Valid |
| F-03 | Admin menambah produk dengan data valid | POST `/admin/seting/produk/store` nama_produk=Kemeja deskripsi=Kemeja Formal harga=150000 foto=file_gambar | Produk tersimpan di database | Valid |
| F-04 | Admin menambah produk tanpa foto | POST store tanpa foto | Error validasi: foto wajib diisi | Valid |
| F-05 | Admin menambah produk dengan harga non-numeric | POST store harga=abc | Error validasi: harga harus angka | Valid |
| F-06 | Admin membuka form edit produk | GET `/admin/seting/produk/edit/{produk}` | Response 200 | Valid |
| F-07 | Admin mengupdate produk | POST `/admin/seting/produk/update/{produk}` dengan data baru | Produk terupdate | Valid |

### 2.7 Modul Admin — Manajemen Detail Produk

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| G-01 | Admin melihat daftar detail | GET `/admin/seting/detail` | Response 200 | Valid |
| G-02 | Admin menambah detail produk | POST `/admin/seting/detail/store` nama_detail=Polyester deskripsi=Bahan Polyester harga=50000 foto=file_gambar | Detail tersimpan | Valid |
| G-03 | Admin mengupdate detail produk | POST `/admin/seting/detail/update/{detail}` | Detail terupdate | Valid |

### 2.8 Modul Admin — Manajemen Order

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| H-01 | Admin melihat daftar order | GET `/admin/order` | Response 200, menampilkan semua order | Valid |
| H-02 | Admin melihat detail order | GET `/admin/order/detail/{order}` | Response 200, menampilkan informasi lengkap order | Valid |
| H-03 | Admin mengupdate status order | POST `/admin/order/update/{order}/status` status=sudah-dikirim | Status order berubah | Valid |
| H-04 | Admin mengkonfirmasi order selesai | POST `/admin/order/update/{order}/confirm` | Status order menjadi "selesai" | Valid |

### 2.9 Modul Admin — Manajemen Penjahit

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| I-01 | Admin melihat daftar penjahit | GET `/admin/penjahit` | Response 200 | Valid |
| I-02 | Admin membuka form tambah penjahit | GET `/admin/penjahit/add` | Response 200 | Valid |
| I-03 | Admin menambah penjahit dengan toko (data valid) | POST `/admin/penjahit/store` name=Penjahit email=baru@test.com password=rahasia123 password_confirmation=rahasia123 nama_toko=Toko Baru alamat_toko=Alamat deskripsi_toko=Deskripsi no_wa=08123 bank=bri no_rekening=12345 atas_nama=Nama foto_toko=file_gambar | User penjahit + toko tersimpan dalam satu transaksi | Valid |
| I-04 | Admin menambah penjahit dengan email sudah terdaftar | POST store email=existing@test.com | Error validasi: email sudah terdaftar | Valid |
| I-05 | Admin menambah penjahit tanpa foto_toko | POST store tanpa foto_toko | Error validasi: foto_toko wajib diisi | Valid |

### 2.10 Modul Penjahit — Dashboard

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| J-01 | Penjahit membuka dashboard | GET `/penjahit/dashboard` | Response 200, menampilkan statistik toko sendiri | Valid |

### 2.11 Modul Penjahit — Toko

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| K-01 | Penjahit melihat profil toko sendiri | GET `/penjahit/toko` | Response 200 | Valid |
| K-02 | Penjahit membuka form edit toko | GET `/penjahit/toko/edit` | Response 200 | Valid |
| K-03 | Penjahit mengupdate toko sendiri | POST `/penjahit/toko/update` dengan data valid | Toko terupdate | Valid |

### 2.12 Modul Penjahit — Produk

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| L-01 | Penjahit tanpa toko mengakses produk | GET `/penjahit/produk` (penjahit tanpa toko) | Redirect ke halaman setup toko (middleware `has.toko`) | Valid |
| L-02 | Penjahit melihat daftar produk miliknya | GET `/penjahit/produk` (memiliki toko) | Response 200 | Valid |
| L-03 | Penjahit menambah produk | POST `/penjahit/produk/store` | Produk tersimpan, terasosiasi dengan toko penjahit | Valid |
| L-04 | Penjahit mengupdate produk milik sendiri | POST `/penjahit/produk/update/{produk}` | Produk terupdate | Valid |
| L-05 | Penjahit menghapus produk milik sendiri | DELETE `/penjahit/produk/delete/{produk}` | Produk terhapus | Valid |
| L-06 | Penjahit mengakses produk milik penjahit lain | GET `/penjahit/produk/edit/{produk_lain}` | Response 403 (Forbidden) | Valid |
| L-07 | Penjahit menghapus produk milik penjahit lain | DELETE `/penjahit/produk/delete/{produk_lain}` | Response 403 | Valid |

### 2.13 Modul Penjahit — Detail Produk

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| M-01 | Penjahit melihat daftar detail | GET `/penjahit/detail` | Response 200 | Valid |
| M-02 | Penjahit menambah detail | POST `/penjahit/detail/store` | Detail tersimpan | Valid |
| M-03 | Penjahit mengupdate detail sendiri | POST `/penjahit/detail/update/{detail}` | Detail terupdate | Valid |
| M-04 | Penjahit menghapus detail sendiri | DELETE `/penjahit/detail/delete/{detail}` | Detail terhapus | Valid |
| M-05 | Penjahit mengakses detail penjahit lain | GET `/penjahit/detail/edit/{detail_lain}` | Response 403 | Valid |

### 2.14 Modul Penjahit — Pesanan

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| N-01 | Penjahit melihat daftar pesanan | GET `/penjahit/pesanan` | Response 200, menampilkan order untuk tokonya | Valid |
| N-02 | Penjahit melihat detail pesanan | GET `/penjahit/pesanan/detail/{order}` | Response 200 | Valid |
| N-03 | Penjahit mengupdate status pesanan | POST `/penjahit/pesanan/update/{order}/status` | Status berubah | Valid |
| N-04 | Penjahit mengkonfirmasi pesanan selesai | POST `/penjahit/pesanan/update/{order}/confirm` | Status = "selesai" | Valid |
| N-05 | Penjahit mengakses pesanan toko lain | GET `/penjahit/pesanan/detail/{order_lain}` | Response 403 | Valid |

### 2.15 Modul Penjahit — Chat

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| O-01 | Penjahit membuka daftar chat | GET `/penjahit/chat` | Response 200 | Valid |
| O-02 | Penjahit melihat percakapan | GET `/penjahit/chat/{conversation}` | Response 200, pesan ditandai dibaca | Valid |
| O-03 | Penjahit mengirim pesan | POST `/penjahit/chat/{conversation}/send` message=Halo | Pesan tersimpan | Valid |
| O-04 | Penjahit mengambil pesan baru (AJAX) | GET `/penjahit/chat/{conversation}/messages` | Response JSON | Valid |

### 2.16 Otorisasi & Akses Role

| ID | Skenario | Langkah Uji | Hasil yang Diharapkan | Hasil |
|----|----------|-------------|----------------------|:-----:|
| P-01 | Guest mengakses halaman admin | GET `/admin/dashboard` | Redirect ke `/login` | Valid |
| P-02 | Guest mengakses halaman penjahit | GET `/penjahit/dashboard` | Redirect ke `/login` | Valid |
| P-03 | Guest mengakses chat pelanggan | GET `/client/chat` | Redirect ke `/login` | Valid |
| P-04 | Pelanggan mengakses halaman admin | GET `/admin/dashboard` (role: pelanggan) | Response 403 | Valid |
| P-05 | Pelanggan mengakses halaman penjahit | GET `/penjahit/dashboard` (role: pelanggan) | Response 403 | Valid |
| P-06 | Penjahit mengakses halaman admin | GET `/admin/dashboard` (role: penjahit) | Response 403 | Valid |
| P-07 | Admin mengakses halaman penjahit | GET `/penjahit/dashboard` (role: admin) | Response 403 | Valid |

---

---

## 4. Tools & Approach

| Tools | Keterangan |
|-------|------------|
| **PHPUnit 11** | Test runner |
| **Laravel `$this->get()` / `$this->post()`** | Simulasi HTTP request |
| **`assertStatus()` / `assertRedirect()`** | Validasi response |
| **`assertSessionHasErrors()`** | Validasi error form |
| **`assertDatabaseHas()` / `assertDatabaseMissing()`** | Validasi perubahan database |
| **`actingAs($user)`** | Autentikasi sebagai user tertentu |
| **RefreshDatabase** | Reset database antar test |
| **UploadedFile::fake()** | Simulasi upload gambar |

---

## 5. Ringkasan

| Modul | Total Skenario | Diuji | Valid |
|-------|:--------------:|:-----:|:-----:|
| Autentikasi (Login, Register, Logout) | 17 | 17 | 17 |
| Belanja (Client) | 14 | 14 | 14 |
| Chat Pelanggan | 7 | 7 | 7 |
| Admin Dashboard | 1 | 1 | 1 |
| Admin Manajemen Toko | 5 | 5 | 5 |
| Admin Manajemen Produk | 7 | 7 | 7 |
| Admin Manajemen Detail | 3 | 3 | 3 |
| Admin Manajemen Order | 4 | 4 | 4 |
| Admin Manajemen Penjahit | 5 | 5 | 5 |
| Penjahit Dashboard | 1 | 1 | 1 |
| Penjahit Toko | 3 | 3 | 3 |
| Penjahit Produk | 7 | 7 | 7 |
| Penjahit Detail | 5 | 5 | 5 |
| Penjahit Pesanan | 5 | 5 | 5 |
| Penjahit Chat | 4 | 4 | 4 |
| Otorisasi & Akses Role | 7 | 7 | 7 |
| **Total** | **~95** | **95** | **95 valid** |


