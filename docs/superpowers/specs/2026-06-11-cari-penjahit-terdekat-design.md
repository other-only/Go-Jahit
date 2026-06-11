# Cari Penjahit Terdekat — Design Spec

**Tanggal:** 2026-06-11
**Status:** Draft
**Project:** Go-Jahit

---

## 1. Ringkasan

Fitur untuk mencari toko penjahit terdekat berdasarkan lokasi pelanggan. Menggunakan Geolocation API browser untuk mendapat posisi pelanggan, Nominatim (OpenStreetMap) untuk geocoding alamat, dan rumus Haversine di MySQL untuk menghitung jarak.

---

## 2. Database

### Yang sudah ada

`tokos` dan `users` sudah memiliki kolom `latitude` dan `longitude` (nullable string).

### Migration baru

Migration untuk mengubah tipe data lat/lng di `tokos` dari string ke decimal agar lebih presisi:

```php
Schema::table('tokos', function (Blueprint $table) {
    $table->decimal('latitude', 10, 7)->nullable()->change();
    $table->decimal('longitude', 10, 7)->nullable()->change();
});
```

### Model

`Toko` model — tambah cast:

```php
protected $casts = [
    'latitude' => 'decimal:7',
    'longitude' => 'decimal:7',
];
```

---

## 3. Isi Koordinat Toko

### Cara 1: Otomatis dari alamat (geocoding)

Saat admin atau penjahit create/edit toko, alamat di-geocode via Nominatim API:

```
GET https://nominatim.openstreetmap.org/search?q={alamat}&format=json&limit=1
```

Response:
```json
[{ "lat": "-6.21462", "lon": "106.84513" }]
```

Geocode dijalankan di controller setelah form disubmit. Hasilnya disimpan ke `latitude` dan `longitude`.

### Cara 2: Input manual

Admin bisa langsung isi latitude dan longitude di form toko. Tetap di-validasi sebagai numeric.

### Catatan

- Nominatim punya rate limit 1 request/detik. Untuk development aman.
- Jika geocode gagal, koordinat dikosongkan, toko tetap tampil (tapi tanpa info jarak).
- Untuk production, bisa di-upgrade ke Google Maps Geocoding.

---

## 4. Dapatkan Lokasi Pelanggan

### Langkah 1: Minta izin GPS

Saat pelanggan membuka halaman `/client/belanja`, JavaScript akan:

```javascript
// Cek apakah ada parameter lat/lng di URL
const urlParams = new URLSearchParams(window.location.search);
if (!urlParams.has('lat')) {
    // Minta izin GPS
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            window.location.href = '/client/belanja?lat=' + lat + '&lng=' + lng;
        },
        () => {
            // Izin ditolak atau error
            document.getElementById('manual-location').style.display = 'block';
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}
```

### Langkah 2: Input manual (jika GPS ditolak)

Tampilkan form input alamat + tombol "Cari" di atas daftar toko:

```
┌─────────────────────────────────────────────┐
│ 📍 Masukkan alamat Anda                     │
│ [Input alamat...]        [Cari]             │
└─────────────────────────────────────────────┘
```

Alamat di-geocode via Nominatim, lalu redirect ke `?lat=X&lng=Y`.

### Langkah 3: Lokasi tersimpan di session

Untuk menghindari minta GPS terus, simpan `lat` dan `lng` di session atau cookie selama sesi browsing.

---

## 5. Hitung & Urutkan Jarak

### Haversine Formula di MySQL

```php
// Di BelanjaController
$lat = $request->get('lat');
$lng = $request->get('lng');

$tokos = Toko::select('*')
    ->selectRaw(
        '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
        * cos(radians(longitude) - radians(?)) + sin(radians(?)) 
        * sin(radians(latitude)))) AS distance',
        [$lat, $lng, $lat]
    )
    ->having('distance', '<', 50) // maksimal 50 km
    ->orderBy('distance')
    ->paginate(12);
```

### Tampilkan jarak

Di view `list_toko.blade.php`:

```blade
@if(isset($toko->distance))
    <small class="text-muted">
        @if($toko->distance < 1)
                            {{ round($toko->distance * 1000) }} m
        @else
                            {{ number_format($toko->distance, 1) }} km
        @endif
    </small>
@endif
```

---

## 6. Perubahan File

### Files to modify

| File | Perubahan |
|------|-----------|
| `database/migrations/xxxx_xx_xx_update_tokos_location_decimal.php` | Cast lat/lng ke decimal |
| `app/Models/Toko.php` | Tambah `$casts` |
| `app/Http/Controllers/Client/BelanjaController.php` | Sorting by distance + geocode |
| `resources/views/client/list_toko.blade.php` | Tampilkan jarak + input alamat manual |
| `resources/views/client/master.blade.php` | Tambah script Geolocation API |

### Files to create

Tidak ada file baru.

---

## 7. Aliran Data Lengkap

```
Pelanggan buka /client/belanja
   │
   ├─ URL ada params ?lat=...&lng=...?
   │   └─ Ya → skip GPS, langsung sort by distance
   │
   ├─ Tidak → JS minta izin GPS
   │   ├─ Izin → redirect ke ?lat=X&lng=Y
   │   └─ Tolak → tampilkan input alamat
   │       └─ Input alamat → geocode → redirect ke ?lat=X&lng=Y
   │
   └─ Controller order by Haversine distance
       └─ View tampilkan toko + info jarak
```

---

## 8. Edge Cases

| Situasi | Penanganan |
|---------|------------|
| GPS tidak didukung browser | Tampilkan input alamat langsung |
| Izin GPS ditolak | Tampilkan input alamat langsung |
| Geocode gagal (Nominatim error) | Fallback: tampilkan daftar toko tanpa urutan jarak |
| Toko tanpa koordinat | Tetap tampil di daftar, di urutan paling bawah |
| Alamat tidak ditemukan | Tampilkan error "Alamat tidak ditemukan" |
| Semua toko di luar radius 50km | Tampilkan pesan "Tidak ada toko di sekitar lokasi Anda" |

---

## 9. Catatan

- Nominatim memblokir request tanpa `User-Agent`. Set header `User-Agent: GoJahit/1.0`.
- Untuk production dengan traffic tinggi, pertimbangkan upgrade ke Google Maps Geocoding atau Mapbox.
- Radius 50km bisa dijadikan config di file konfigurasi atau env.
