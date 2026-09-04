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
Member Order
      ↓
Atomic wallet debit
      ↓
Provider Order
      ↓
Webhook / status sync
      ↓
Success / Failed / Refund
```

## DHRU upstream

`lib/providers/DhruClient.php` menangani account info, service list, place order, dan order status. Standar DHRU memang menyediakan fungsi tersebut dan juga mendukung server sendiri sebagai API listener.

## Sinkronisasi DHRU

```text
admin-dashboard/dhru-products.php
get/sync-dhru.php
```

Produk baru hasil sync memiliki `public_visible=0`, sehingga tidak otomatis muncul di halaman depan. Admin memilih produk yang dijual melalui checkbox **Tampilkan**.

## Public catalog

```text
GET /api/catalog.php
GET /api/catalog.php?category=...
GET /api/catalog.php?search=...
```

Harga API/provider tidak dibocorkan ke public catalog.

## Public DHRU API

```text
POST https://DOMAIN-ANDA/api/dhru/index.php
```

Parameter standar:

```text
username=USERNAME
apiaccesskey=API_KEY
requestformat=JSON
action=accountinfo|imeiservicelist|placeimeiorder|getimeiorder
```

Endpoint ini ditujukan untuk menjual produk panel ke reseller yang menggunakan standar DHRU Fusion.

## Database migration

Jalankan:

```text
database/migrations/2026_09_04_v2.sql
```

Migration bersifat additive agar database dan route lama tidak langsung rusak.

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
