# Pemisahan Halaman Admin dan Penjahit

**Tanggal:** 2026-06-10
**Status:** Approved Design
**Project:** Go-Jahit (Laravel)

## Latar Belakang

Saat ini aplikasi Go-Jahit menggunakan satu panel admin bersama (`/admin/*`) untuk dua role: **admin** dan **penjahit**. Kedua role berbagi layout, sidebar, controller, dan view yang sama. Pemisahan data dilakukan melalui pengecekan role di controller (`if hasRole('penjahit')`). Hal ini membuat kode kurang bersih dan pengalaman setiap role tidak optimal.

## Tujuan

Memisahkan halaman admin dan penjahit secara total — URL, controller, view, layout, dan sidebar masing-masing berdiri sendiri.

## Pendekatan

Pendekatan **Controller Terpisah** — membuat controller dan view baru khusus penjahit tanpa mengubah kode admin yang sudah berjalan.

## Struktur URL

| Role | Prefix | Middleware |
|---|---|---|
| Admin | `/admin/*` | `role:admin` |
| Penjahit | `/penjahit/*` | `role:penjahit` |

### Detail Route

**Admin (`/admin/*`) — hanya untuk admin:**
```
/admin/dashboard
/admin/seting/toko
/admin/seting/produk            (read-only, lihat semua)
/admin/seting/detail            (read-only, lihat semua)
/admin/order/*
/admin/penjahit/*               (kelola akun penjahit)
```

**Penjahit (`/penjahit/*`) — hanya untuk penjahit:**
```
/penjahit/dashboard
/penjahit/toko                  (edit toko sendiri)
/penjahit/toko/edit
/penjahit/toko/update
/penjahit/produk                (CRUD produk sendiri)
/penjahit/produk/add
/penjahit/produk/store
/penjahit/produk/edit/{produk}
/penjahit/produk/update/{produk}
/penjahit/detail                (CRUD detail sendiri)
/penjahit/detail/add
/penjahit/detail/store
/penjahit/detail/edit/{detail}
/penjahit/detail/update/{detail}
/penjahit/pesanan               (lihat pesanan sendiri)
/penjahit/pesanan/detail/{order}
/penjahit/pesanan/update/{order}/status
/penjahit/pesanan/update/{order}/confirm
```

## Controller

### Controller Baru (`app/Http/Controllers/Penjahit/`)

| Controller | Method | Keterangan |
|---|---|---|
| `DashboardController` | `index()` | Dashboard penjahit |
| `TokoController` | `index()`, `edit()`, `update()` | Kelola toko miliknya (`penjahit_id = auth()->id()`) |
| `ProdukController` | `index()`, `create()`, `store()`, `edit()`, `update()` | CRUD produk miliknya |
| `DetailController` | `index()`, `create()`, `store()`, `edit()`, `update()` | CRUD detail miliknya |
| `PesananController` | `index()`, `detail()`, `status()`, `confirm()` | Kelola pesanan tokonya |

**Tidak ada pengecekan role** di controller karena sudah dijamin oleh route middleware.

### Controller Admin (Existing — Tidak Diubah)

| Controller | Perubahan |
|---|---|
| `Admin\TokoController` | Tidak diubah — tetap lihat semua toko |
| `Admin\ProdukController` | **Dibersihkan**: hapus role check `hasRole('penjahit')` karena sudah tidak diakses penjahit |
| `Admin\DetailController` | **Dibersihkan**: hapus role check `hasRole('penjahit')` karena sudah tidak diakses penjahit |
| `Admin\OrderController` | Tidak diubah — tetap lihat semua order |
| `Admin\PenjahitController` | Tidak diubah — tetap khusus admin |
| `LoginController` | **Dimodifikasi**: redirect penjahit ke `/penjahit/dashboard` |

## View Structure

```
resources/views/
├── admin/
│   ├── dashboard.blade.php       ← dashboard admin (existing)
│   ├── toko/index.blade.php       ← lihat semua toko (existing)
│   ├── produk/index.blade.php     ← lihat semua produk (existing)
│   ├── detail/index.blade.php     ← lihat semua detail (existing)
│   ├── order/index.blade.php      ← lihat semua order (existing)
│   ├── order/detail.blade.php     ← detail order (existing)
│   ├── penjahit/index.blade.php   ← kelola penjahit (existing)
│   └── penjahit/add.blade.php     ← tambah penjahit (existing)
│
├── penjahit/                      ← BARU
│   ├── dashboard.blade.php
│   ├── toko/
│   │   ├── index.blade.php
│   │   └── edit.blade.php
│   ├── produk/
│   │   ├── index.blade.php
│   │   ├── add.blade.php
│   │   └── edit.blade.php
│   ├── detail/
│   │   ├── index.blade.php
│   │   ├── add.blade.php
│   │   └── edit.blade.php
│   └── pesanan/
│       ├── index.blade.php
│       └── detail.blade.php
│
├── panels/
│   ├── master.blade.php           ← layout admin (existing)
│   ├── sidebar.blade.php          ← sidebar admin (existing)
│   ├── header.blade.php           ← header admin (existing)
│   ├── penjahit-master.blade.php  ← BARU (layout penjahit)
│   ├── penjahit-sidebar.blade.php ← BARU (sidebar penjahit)
│   └── penjahit-header.blade.php  ← BARU (header penjahit)
```

## Layout & Sidebar

### `panels.penjahit-master.blade.php`
Copy dari `panels.master.blade.php` dengan perubahan:
- Include `panels.penjahit-sidebar` bukan `panels.sidebar`
- Include `panels.penjahit-header` bukan `panels.header`

### `panels.penjahit-sidebar.blade.php`
Sidebar khusus penjahit dengan menu:
- Dashboard
- Toko Saya
- Produk (dengan submenu: Daftar Produk, Tambah Produk)
- Detail (dengan submenu: Daftar Detail, Tambah Detail)
- Pesanan

### `panels.penjahit-header.blade.php`
Copy dari `panels.header.blade.php` — bisa sama atau disesuaikan nanti.

## Login Redirect

Di `LoginController::postLogin()`:
```php
if ($user->hasRole('pelanggan')) {
    return redirect()->route('client.belanja');
}
if ($user->hasRole('penjahit')) {
    return redirect()->route('penjahit.dashboard');  // BARU
}
return redirect()->route('admin.dashboard');
```

## Hal yang Perlu Diperhatikan

1. **Route middleware:** Perubahan route `/admin/*` dari `role:admin|penjahit` menjadi `role:admin` — pastikan tidak ada pengguna penjahit yang masih mengakses URL admin lama.
2. **Redirect penjahit yang sedang login:** Penjahit yang sedang login dan mencoba akses `/admin/*` akan kena 403 karena middleware. Mereka harus diarahkan ke `/penjahit/dashboard` via logout/login ulang.
3. **ProdukController & DetailController admin:** Hapus pengecekan `hasRole('penjahit')` karena sudah tidak diakses penjahit lagi. Admin view-only untuk produk dan detail.
4. **Dashboard admin vs penjahit:** Dashboard admin bisa ditambahkan statistik global; dashboard penjahit menampilkan data tokonya sendiri.
