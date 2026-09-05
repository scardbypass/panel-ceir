# DHRU Integration — Panel CEIR V2

Dokumentasi ini menjelaskan **dua sisi DHRU** pada Panel CEIR:

1. **DHRU Upstream Provider** — akun provider DHRU milik panel, tempat order diteruskan.
2. **DHRU Server/API** — endpoint Panel CEIR yang dipakai reseller eksternal.

Jangan mencampur kredensial kedua sisi tersebut.

---

## 1. DHRU Upstream Provider

Admin → **Provider Center → Setting DHRU**.

Isi:

```text
API URL   = https://provider-dhru.example
Username  = username provider
API Key   = API access key provider
Status    = Active
```

Panel menggunakan DHRU Fusion API untuk:

- `accountinfo`
- `imeiservicelist`
- `placeimeiorder`
- `getimeiorder`

Tombol **Test Connection & Account Info** harus digunakan sebelum sync produk.

### Sync produk

Admin → **Produk DHRU → Sync**.

Sync mengambil service dari upstream dan menyimpannya ke `layanan_digital`.

Perilaku sync:

- produk baru dibuat `public_visible=0`
- harga provider disimpan di `harga_api`
- harga jual produk lama tidak ditimpa
- group/kategori provider disimpan
- `Requires` disimpan untuk kebutuhan form order
- perubahan harga provider memperbarui `harga_api`
- produk yang tidak lagi dikirim upstream tidak otomatis dihapus

---

## 2. Kelola Produk

Admin → **Produk DHRU**.

Setiap produk mempunyai:

| Field | Fungsi |
|---|---|
| Service ID | ID service DHRU upstream |
| Group | kategori/group DHRU |
| Nama | nama produk yang tampil |
| Harga Provider | modal dari upstream |
| Harga Jual | harga yang dibayar reseller |
| Profit | Harga Jual - Harga Provider |
| Status | Normal / Gangguan |
| Publish | boleh tampil ke reseller atau tidak |
| Catatan | informasi tambahan |

Produk dapat ditambahkan manual jika diperlukan.

**Publish tidak sama dengan aktif provider.** Produk hanya diberikan ke reseller jika:

```text
status = Normal
public_visible = 1
```

---

## 3. DHRU Server untuk Reseller

Reseller memakai:

```text
POST https://DOMAIN-ANDA/api/dhru
```

Alias kompatibilitas:

```text
POST https://DOMAIN-ANDA/api/dhru/index.php
POST https://DOMAIN-ANDA/api/index.php
```

Content-Type:

```text
application/x-www-form-urlencoded
```

atau:

```text
multipart/form-data
```

Authentication:

```text
username=USERNAME_RESELLER
apiaccesskey=API_KEY_RESELLER
requestformat=JSON
```

Alias `apiaccesskey` yang diterima:

```text
key
api_key
apikey
accesskey
```

---

## 4. Account Info

Request:

```bash
curl -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=accountinfo'
```

Response:

```json
{
  "SUCCESS": [
    {
      "MESSAGE": "Your Account Info",
      "AccountInfo": {
        "credit": "150000.00",
        "creditraw": 150000,
        "currency": "IDR",
        "username": "USERNAME_RESELLER"
      }
    }
  ]
}
```

`creditraw` adalah saldo numerik dalam Rupiah.

---

## 5. Service List

Action:

```text
imeiservicelist
```

Alias:

```text
servicelist
```

Contoh:

```bash
curl -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=imeiservicelist'
```

Hanya produk yang `Normal + public_visible=1` yang dikirim.

Response:

```json
{
  "SUCCESS": [
    {
      "MESSAGE": "IMEI Service List",
      "LIST": [
        {
          "GROUPNAME": "Cekimei",
          "GROUPTYPE": "IMEI",
          "SERVICES": [
            {
              "SERVICEID": "123",
              "SERVICETYPE": "IMEI",
              "SERVICENAME": "Cek Status IMEI",
              "CREDIT": 1500,
              "MINQNT": 1,
              "MAXQNT": 1,
              "Requires": {
                "Custom": [
                  {
                    "fieldname": "IMEI",
                    "fieldtype": "text",
                    "required": "on"
                  }
                ]
              }
            }
          ]
        }
      ]
    }
  ]
}
```

`SERVICEID` adalah ID yang digunakan saat order.

---

## 6. Place IMEI Order

Action:

```text
placeimeiorder
```

Request:

```bash
curl -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=placeimeiorder' \
  -d 'parameters={"ID":"123","IMEI":"356938035643809"}'
```

Response diterima reseller:

```json
{
  "SUCCESS": [
    {
      "MESSAGE": "Order received",
      "REFERENCEID": 1001
    }
  ]
}
```

`REFERENCEID` adalah ID order lokal Panel CEIR.

---

## 7. Order Flow

```text
Reseller
   │
   ▼
DHRU API Panel CEIR
   │
   ├── authenticate
   ├── validate service
   ├── validate target
   ├── create local order
   │
   ▼
Atomic wallet debit
   │
   ▼
DHRU Upstream
   │
   ▼
Provider Order ID
   │
   ▼
Status reconciliation
   │
   ├── Processing
   ├── Success
   └── Error → Refund
```

Timeout provider **tidak otomatis dianggap gagal**, karena provider bisa saja sudah menerima order tetapi response hilang.

---

## 8. Get Order Status

Action:

```text
getimeiorder
```

Request:

```bash
curl -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=getimeiorder' \
  -d 'parameters={"ID":"1001"}'
```

Status kompatibilitas:

| STATUS | Arti |
|---:|---|
| 0 | Pending |
| 1 | Processing |
| 3 | Error / rejected |
| 4 | Success |

---

## 9. Bulk API

Compatibility action:

```text
placeimeiorderbulk
getimeiorderbulk
```

Alias lama juga diterima:

```text
placeorderbulk
getorderbulk
orderstatusbulk
```

Untuk integrasi baru, satu order/request lebih mudah direkonsiliasi.

---

## 10. API Key Reseller

V2 menggunakan tabel:

```text
api_clients
```

Raw key tidak perlu disimpan.

Generate hash:

```bash
php -r 'echo hash("sha256", "API_KEY_ASLI"), PHP_EOL;'
```

Simpan hash ke `api_clients.api_key_hash` untuk user reseller terkait.

Untuk kompatibilitas reseller lama, `users.api_key` masih dapat diterima sampai migrasi selesai.

---

## 11. Database

Migration utama:

```text
database/migrations/2026_09_04_v2.sql
database/migrations/2026_09_04_dhru_admin.sql
```

Field tambahan katalog:

```text
public_visible
sort_order
image_url
updated_at
dhru_group
service_type
requires_json
cost_updated_at
```

Tabel order/provider:

```text
provider_orders_v2
```

Tabel API reseller:

```text
api_clients
```

Wallet:

```text
wallet_ledger
```

---

## 12. Admin URLs

```text
/admin-dashboard/action-provider
```

Provider Center.

```text
/admin-dashboard/dhru-settings
```

Setting DHRU upstream.

```text
/admin-dashboard/dhru-products
```

Sync, tambah, edit, pricing dan publish produk.

```text
/admin-dashboard/dhru-orders
```

Monitoring order DHRU.

```text
/api/dhru
```

DHRU Server API reseller.

---

## 13. Security

- Gunakan HTTPS.
- Jangan commit API key.
- Jangan tampilkan API key pada UI publik.
- Jangan expose `harga_api` pada public catalog.
- Gunakan API key hash untuk reseller baru.
- Batasi endpoint DHRU dengan rate limit pada deployment production.
- Backup database sebelum migration.
- Rotasi credential provider jika pernah bocor.

## 14. Deployment

Setelah pull:

```bash
git pull origin feat/ceirgo-dhru-qris-rework
```

Jalankan migration database:

```text
database/migrations/2026_09_04_v2.sql
database/migrations/2026_09_04_dhru_admin.sql
```

Kemudian:

1. Buka Provider Center.
2. Buka Setting DHRU.
3. Simpan credential upstream.
4. Test Connection.
5. Sync Produk.
6. Atur harga jual.
7. Publish produk yang siap.
8. Test `accountinfo` dari panel reseller.
9. Test `imeiservicelist`.
10. Gunakan order test yang memang diizinkan provider.
