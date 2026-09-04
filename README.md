# Panel CEIR — V3 Rework

Panel layanan digital / IMEI yang mempertahankan workflow lama sambil memisahkan catalog, order, wallet, provider, DHRU API dan payment agar lebih mudah dirawat.

## V3 — Dynamic Order Catalog

Sekarang produk order tidak lagi dikunci ke tiga menu lama. Admin dapat menentukan sendiri:

- Produk apa yang muncul di sidebar/member.
- Nama menu yang tampil.
- Icon menu.
- Grup menu.
- Urutan menu.
- Field form order per produk.
- Field wajib/tidak wajib.
- Tipe field: text, tel/IMEI, number, email, textarea, select.
- Placeholder, min/max length dan pilihan select.

### Admin

Buka:

```text
/admin-dashboard/order-menu
```

Konsepnya seperti service catalog DHRU Fusion: produk di catalog menjadi service, lalu admin menentukan service mana yang dipublish ke menu reseller/member dan field apa yang dibutuhkan ketika order.

Contoh produk `Unlock IMEI 3B` cukup dikonfigurasi:

```json
[
  {
    "name": "imei",
    "label": "Nomor IMEI",
    "type": "tel",
    "required": true,
    "placeholder": "Masukkan 15 digit IMEI",
    "min": 14,
    "max": 16
  }
]
```

Member kemudian mendapat halaman:

```text
/pemesanan/order?service=SERVICE_ID
```

dan hanya perlu mengisi IMEI lalu menekan **Order Sekarang**.

## Arsitektur order

```text
Admin Catalog
     ↓
Menu Builder + Form Builder
     ↓
Member Sidebar
     ↓
Dynamic Order Page
     ↓
Atomic Wallet Debit
     ↓
CEIRGo / DHRU / Manual Provider
     ↓
Provider Order ID
     ↓
Polling / Webhook
     ↓
Success / Failed / Refund
```

## Database migration

Jalankan berurutan:

```text
database/migrations/2026_09_04_v2.sql
database/migrations/2026_09_04_order_catalog_v3.sql
```

V3 menambahkan konfigurasi form/menu dan tabel:

```text
order_menu
```

serta kolom:

```text
order_form_json
menu_label
menu_icon
```

`public_visible` tetap dipakai untuk public catalog/DHRU service list. Visibility menu member berdiri sendiri di `order_menu`.

## DHRU upstream

```text
/admin-dashboard/dhru-settings
/admin-dashboard/dhru-products
/admin-dashboard/dhru-orders
```

`lib/providers/DhruClient.php` menangani:

```text
accountinfo
imeiservicelist
placeimeiorder
getimeiorder
```

Sync produk tidak menimpa harga jual yang sudah diatur admin.

## Public DHRU Fusion API

Endpoint kompatibilitas:

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

Detail ada di `docs/DHRU.md`.

## CEIRGo

`lib/providers/CeirGoClient.php` menggunakan Bearer API dan endpoint CEIRGo untuk account, service, order dan status.

## Public catalog

```text
GET /api/catalog.php
```

Harga provider/upstream tidak dibocorkan.

## UI

Header member dan developer dashboard sudah direbuild dengan layout responsive, sidebar modern, dashboard control center dan halaman order yang responsif untuk mobile.

## reCAPTCHA

reCAPTCHA benar-benar dinonaktifkan pada login/register/reset password: tidak ada widget, validasi captcha, atau request ke endpoint Google reCAPTCHA.

## Testing

GitHub Actions menjalankan PHP syntax check untuk seluruh file PHP non-vendor pada branch:

```text
feat/ceirgo-dhru-qris-rework
```

Sebelum production, lakukan test berurutan:

1. PHP lint.
2. DHRU Account Info / Test Connection.
3. DHRU Sync Product.
4. Public DHRU `accountinfo` dan `imeiservicelist`.
5. Test order memakai service/IMEI test yang memang diizinkan provider.
6. Pastikan provider order ID masuk dan status dapat direkonsiliasi.
7. Test refund hanya dengan transaksi test/error yang aman.

## Deployment

```bash
git fetch origin
git checkout feat/ceirgo-dhru-qris-rework
git pull origin feat/ceirgo-dhru-qris-rework
```

Requirements: PHP 8.1+, cURL, mysqli, JSON, SimpleXML, MySQL/MariaDB.

## Security

- Credential provider hanya di server/environment.
- HTTPS wajib.
- API key reseller tidak boleh ditampilkan ke frontend/log.
- Jangan menjalankan order berbayar hanya untuk menguji koneksi.
- Karena histori Git lama pernah memuat credential, lakukan rotasi credential yang pernah terekspos walaupun file terbaru sudah bersih.
