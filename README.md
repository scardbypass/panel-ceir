# Panel CEIR — V2 Rework

Panel layanan digital / IMEI dengan sistem lama tetap dipertahankan, sementara provider, katalog, order, payment, dan API dipisahkan agar lebih aman dan mudah dirawat.

## Target V2

- UI/UX boleh dirombak tanpa mengubah alur bisnis member/admin.
- CEIRGo dan DHRU Fusion sebagai provider.
- Sinkronisasi katalog layanan + harga.
- Admin dapat menentukan produk DHRU yang tampil di halaman depan.
- Public DHRU Fusion-compatible API untuk reseller eksternal.
- Order dengan debit saldo atomic/idempotent dan status provider yang jelas.
- Payment gateway melalui adapter terpisah.
- SayaBayar disiapkan dengan API key dan callback; endpoint production diisi dari developer credentials akun karena halaman publik SayaBayar hanya mengonfirmasi fitur API key/webhook tanpa mempublikasikan path API developer secara terbuka.

## Arsitektur

```text
CEIRGo / DHRU
      ↓
Provider Adapter
      ↓
Local Catalog
      ↓
Admin: harga + status + tampil di depan
      ↓
Member / Reseller Order
      ↓
Atomic wallet debit
      ↓
Provider Order
      ↓
Webhook / status polling
      ↓
Success / Failed / Refund
```

## CEIRGo

`lib/providers/CeirGoClient.php` menggunakan API CEIRGo terbaru dengan Bearer token:

```text
GET  /api/me
GET  /api/wallet/snap
GET  /api/services
GET  /api/services/{idOrCode}
POST /api/order
GET  /api/order/{id}
```

Client otomatis menormalkan konfigurasi provider lama seperti `https://ceirgo.id/api/` agar tidak menjadi `/api/api/...`.

## DHRU upstream

`lib/providers/DhruClient.php` menangani account info, service list, place order, dan order status ke provider DHRU upstream.

Sinkronisasi:

```text
admin-dashboard/dhru-products.php
get/sync-dhru.php
```

Produk baru hasil sync memiliki `public_visible=0`, sehingga tidak otomatis muncul di depan. Admin memilih produk yang dijual melalui toggle **Tampilkan**.

## Public DHRU Fusion API

Endpoint kompatibilitas tersedia di:

```text
POST /api/dhru
POST /api/dhru/index.php
POST /api/index.php
```

Handler:

```text
lib/providers/DhruServer.php
```

Action utama:

```text
accountinfo
imeiservicelist
placeimeiorder
getimeiorder
```

Compatibility tambahan:

```text
servicelist
placeorder
placeimeiorderbulk
placeorderbulk
getorder
orderstatus
getimeiorderbulk
getorderbulk
orderstatusbulk
```

Dokumentasi lengkap:

```text
docs/DHRU.md
```

API DHRU hanya mengirim produk yang `status='Normal'` dan `public_visible=1`. Saldo reseller berasal dari wallet lokal Panel CEIR.

## Public catalog

```text
GET /api/catalog.php
GET /api/catalog.php?category=...
GET /api/catalog.php?search=...
```

Harga provider/upstream tidak dibocorkan ke public catalog.

## Database migration

Jalankan:

```text
database/migrations/2026_09_04_v2.sql
```

Migration menambahkan katalog visibility, wallet ledger, API clients, payment transactions, dan provider order tracking.

## SayaBayar

SayaBayar menyatakan menyediakan API key, invoice otomatis, QRIS/bank, pencocokan pembayaran otomatis, dan webhook. Adapter berada di:

```text
lib/payment/SayaBayarClient.php
```

Konfigurasi:

```text
SAYABAYAR_CREATE_URL
SAYABAYAR_CHECK_URL
SAYABAYAR_API_KEY
SAYABAYAR_WEBHOOK_SECRET
```

Jangan menyimpan credential asli di Git.

## reCAPTCHA

reCAPTCHA sudah dinonaktifkan. Tidak ada widget, validasi captcha, atau request ke endpoint Google reCAPTCHA pada alur login/register/reset password.

## Deployment

```bash
git fetch origin
git checkout feat/ceirgo-dhru-qris-rework
git pull origin feat/ceirgo-dhru-qris-rework
```

Requirements: PHP 8.1+, cURL, mysqli, JSON, SimpleXML, MySQL/MariaDB.

## Catatan migrasi

File dan sistem legacy **jangan dihapus sebelum penggantinya sudah aktif dan diuji**. Setelah seluruh route/order/payment V2 lolos pengujian, file legacy dapat dihapus dalam satu cleanup commit sehingga tidak meninggalkan dead code.

## Branch

`feat/ceirgo-dhru-qris-rework`
