# DHRU Fusion API — Panel CEIR

Dokumentasi endpoint DHRU Fusion yang disediakan Panel CEIR untuk reseller eksternal.

## Endpoint

Semua endpoint berikut mengarah ke handler yang sama:

```text
POST https://DOMAIN-ANDA/api/dhru
POST https://DOMAIN-ANDA/api/dhru/index.php
POST https://DOMAIN-ANDA/api/index.php
```

Gunakan `POST` dengan `application/x-www-form-urlencoded` atau `multipart/form-data`.

## Authentication

Setiap request wajib mengirim:

```text
username=USERNAME_RESELLER
apiaccesskey=API_KEY_RESELLER
requestformat=JSON
```

Alias yang kompatibel:

- `username`: `api_username` atau `user`
- `apiaccesskey`: `key`, `api_key`, `apikey`, atau `accesskey`

API key V2 disimpan sebagai SHA-256 hash di tabel `api_clients`. Sistem juga masih menerima `users.api_key` untuk kompatibilitas reseller lama.

> Jangan menaruh API key di frontend, JavaScript, screenshot, log, atau repository Git.

## Response format

Default adalah JSON. Untuk XML kirim:

```text
requestformat=XML
```

Success:

```json
{
  "SUCCESS": [
    {
      "MESSAGE": "..."
    }
  ]
}
```

Error:

```json
{
  "ERROR": [
    {
      "MESSAGE": "..."
    }
  ]
}
```

## 1. Account Info

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
        "mail": "reseller@example.com",
        "currency": "IDR",
        "username": "USERNAME_RESELLER"
      }
    }
  ]
}
```

`creditraw` adalah saldo numerik reseller dalam Rupiah.

## 2. Service List

Action:

```text
imeiservicelist
```

Alias:

```text
servicelist
```

Request:

```bash
curl -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=imeiservicelist'
```

Hanya produk yang:

- `status = Normal`
- `public_visible = 1`

yang dikirim ke reseller. Jadi admin dapat menyembunyikan produk tanpa menghapus katalog.

Response inti:

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

Gunakan `SERVICEID` dari response ketika membuat order.

## 3. Place IMEI Order

Action:

```text
placeimeiorder
```

Alias:

```text
placeorder
```

`parameters` menerima JSON atau XML.

### JSON

```bash
curl -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=placeimeiorder' \
  -d 'parameters={"ID":"123","IMEI":"356938035643809"}'
```

### XML

```text
<PARAMETERS>
  <ID>123</ID>
  <QNT>1</QNT>
  <IMEI>356938035643809</IMEI>
</PARAMETERS>
```

Response:

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

`REFERENCEID` adalah ID order lokal Panel CEIR. ID tersebut digunakan untuk polling status.

### Alur pembayaran order

1. Panel memvalidasi reseller dan service.
2. Panel membuat order lokal.
3. Saldo reseller didebit secara atomic melalui wallet ledger V2.
4. Order diteruskan ke provider produk (`ceirgo`, `DHRU`, atau `manual`).
5. Jika provider menolak secara jelas, saldo direfund.
6. Jika koneksi provider timeout, order tidak langsung direfund agar tidak terjadi double-spend setelah provider sebenarnya menerima order.
7. Status dapat direkonsiliasi melalui `getimeiorder`.

## 4. Get IMEI Order

Action:

```text
getimeiorder
```

Alias:

```text
getorder
orderstatus
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

Status:

| STATUS | Arti |
|---:|---|
| `0` | Pending / waiting |
| `1` | Processing |
| `3` | Error / rejected |
| `4` | Success |

Status `partial` dikembalikan sebagai processing (`1`) agar kompatibel dengan client DHRU lama.

## 5. Bulk compatibility

Didukung:

```text
placeimeiorderbulk
placeorderbulk
getimeiorderbulk
getorderbulk
orderstatusbulk
```

Format bulk mengikuti array JSON parameter. Untuk integrasi baru, satu order per request lebih mudah dilacak dan direkonsiliasi.

## API key reseller V2

Migration:

```text
database/migrations/2026_09_04_v2.sql
```

Tabel:

```text
api_clients
```

Kolom utama:

```text
id
user_id
api_key_hash
label
status
last_used_at
created_at
```

Generate hash untuk key yang akan disimpan:

```bash
php -r 'echo hash("sha256", "API_KEY_ASLI"), PHP_EOL;'
```

Lalu masukkan hash ke `api_clients` untuk `user_id` reseller terkait. Raw API key diberikan hanya kepada reseller.

## Contoh pengujian

### Cek saldo

```bash
curl -s -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=accountinfo'
```

### Cek produk

```bash
curl -s -X POST 'https://DOMAIN-ANDA/api/dhru' \
  -d 'username=USERNAME_RESELLER' \
  -d 'apiaccesskey=API_KEY_RESELLER' \
  -d 'requestformat=JSON' \
  -d 'action=imeiservicelist'
```

### Test order

Gunakan service ID dan IMEI test yang memang diizinkan provider. Jangan menjalankan order berbayar hanya untuk menguji koneksi.

## Integrasi panel reseller

Pada panel DHRU Fusion, isi:

```text
API URL  : https://DOMAIN-ANDA/api/dhru
Username : USERNAME_RESELLER
API Key  : API_KEY_RESELLER
```

Jika client otomatis menambahkan `/api/index.php`, gunakan base URL/domain sesuai petunjuk client tersebut.

## Catatan keamanan

- HTTPS wajib digunakan.
- API key tidak boleh dicetak ke response atau halaman admin.
- Produk yang belum siap dijual harus `public_visible=0`.
- Jangan expose `harga_api` provider upstream melalui public catalog website.
- Backup database sebelum menjalankan migration.
- Setelah migrasi key selesai, legacy `users.api_key` dapat dinonaktifkan dalam cleanup berikutnya.
