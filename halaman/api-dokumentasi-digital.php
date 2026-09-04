<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_user.php';
require_once __DIR__ . '/../lib/header.php';
$base = rtrim($config['web']['url'], '/');
$dhruUrl = $base . '/api/dhru';
$legacyUrl = $base . '/api/produk-digital';
?>
<title>Dokumentasi API — <?=htmlspecialchars($data['short_title']??'Panel')?></title>
<style>
.api-doc{max-width:1180px;margin:18px auto 55px}.api-hero{position:relative;overflow:hidden;padding:30px;border-radius:28px;background:linear-gradient(135deg,#111827,#312e81,#4f46e5);color:#fff;box-shadow:0 22px 60px rgba(37,39,95,.18)}.api-hero:after{content:"";position:absolute;width:300px;height:300px;right:-110px;top:-170px;border-radius:50%;background:rgba(255,255,255,.09)}.api-k{font-size:11px;letter-spacing:.15em;text-transform:uppercase;font-weight:850;opacity:.65}.api-hero h1{font-weight:850;margin:7px 0}.api-hero p{opacity:.76;max-width:720px;margin:0}.api-tabs{display:flex;gap:8px;flex-wrap:wrap;margin:16px 0}.api-tab{padding:10px 15px;border-radius:13px;background:#fff;border:1px solid #e5e7eb;color:#334155;font-weight:800}.api-card{background:#fff;border:1px solid #e7eaf0;border-radius:20px;box-shadow:0 12px 38px rgba(15,23,42,.06);padding:20px;margin-bottom:16px}.api-card h3{font-weight:850}.endpoint{display:flex;gap:9px;align-items:center;flex-wrap:wrap;background:#f8fafc;border:1px solid #edf0f4;border-radius:14px;padding:11px 13px;margin:10px 0}.method{font-size:11px;padding:5px 8px;border-radius:8px;background:#111827;color:#fff;font-weight:850}.url{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;word-break:break-all}.code{background:#0f172a;color:#e2e8f0;border-radius:15px;padding:16px;overflow:auto;font-size:12px;line-height:1.6}.table-wrap{overflow:auto}.api-table{width:100%;min-width:620px}.api-table th{background:#f8fafc}.api-table th,.api-table td{padding:11px;border-bottom:1px solid #edf0f4;vertical-align:top}.badge-api{display:inline-block;border-radius:999px;padding:5px 9px;background:#eef2ff;color:#3730a3;font-size:11px;font-weight:800}.api-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.copy{cursor:pointer}@media(max-width:750px){.api-doc{margin:10px 7px 40px}.api-hero{padding:22px;border-radius:22px}.api-card{padding:16px}.api-grid{grid-template-columns:1fr}.api-hero h1{font-size:25px}}
</style>
<div class="api-doc">
 <section class="api-hero"><div class="api-k">Developer Center</div><h1>API Produk Digital</h1><p>Dokumentasi lengkap untuk integrasi reseller: API legacy Produk Digital dan <b>DHRU Fusion compatible API</b> Panel CEIR. Semua endpoint merespons JSON secara default.</p></section>
 <div class="api-tabs"><a class="api-tab" href="#dhru"><i class="mdi mdi-api"></i> DHRU API</a><a class="api-tab" href="#legacy"><i class="mdi mdi-code-json"></i> Legacy API</a><a class="api-tab" href="#examples"><i class="mdi mdi-code-tags"></i> Contoh Request</a><a class="api-tab" href="#security"><i class="mdi mdi-shield-lock-outline"></i> Keamanan</a></div>
 <section id="dhru" class="api-card"><h3><i class="mdi mdi-server-network"></i> DHRU Fusion API</h3><p class="text-muted">Endpoint ini dibuat untuk reseller/platform yang sudah memakai format DHRU Fusion. Username dan API Access Key mengikuti kredensial API reseller di panel.</p><div class="endpoint"><span class="method">POST</span><span class="url"><?=htmlspecialchars($dhruUrl)?></span></div><div class="table-wrap"><table class="api-table"><thead><tr><th>Action</th><th>Fungsi</th><th>Parameter utama</th></tr></thead><tbody><tr><td><span class="badge-api">accountinfo</span></td><td>Saldo dan informasi akun</td><td>username, apiaccesskey</td></tr><tr><td><span class="badge-api">imeiservicelist</span></td><td>Daftar service yang dipublish</td><td>username, apiaccesskey</td></tr><tr><td><span class="badge-api">placeimeiorder</span></td><td>Membuat order</td><td>parameters: ID, IMEI</td></tr><tr><td><span class="badge-api">placeimeiorderbulk</span></td><td>Order beberapa service</td><td>parameters array/JSON</td></tr><tr><td><span class="badge-api">getimeiorder</span></td><td>Cek status order</td><td>parameters: ID</td></tr><tr><td><span class="badge-api">getimeiorderbulk</span></td><td>Cek beberapa order</td><td>parameters array/JSON</td></tr></tbody></table></div><div class="alert alert-light border mt-3 mb-0"><b>Endpoint alternatif:</b> <span class="url"><?=htmlspecialchars($base.'/api/dhru/index.php')?></span></div></section>
 <div class="api-grid">
  <section class="api-card"><h3>1. Account Info</h3><div class="endpoint"><span class="method">POST</span><span class="url"><?=htmlspecialchars($dhruUrl)?></span></div><pre class="code">action=accountinfo
username=USERNAME_RESELLER
apiaccesskey=API_ACCESS_KEY
requestformat=JSON</pre><b>Response</b><pre class="code mt-2">{
  "SUCCESS": [{
    "MESSAGE": "Your Account Info",
    "AccountInfo": {
      "credit": "100000.00",
      "creditraw": 100000,
      "currency": "IDR",
      "username": "reseller"
    }
  }]
}</pre></section>
  <section class="api-card"><h3>2. Service List</h3><div class="endpoint"><span class="method">POST</span><span class="url"><?=htmlspecialchars($dhruUrl)?></span></div><pre class="code">action=imeiservicelist
username=USERNAME_RESELLER
apiaccesskey=API_ACCESS_KEY
requestformat=JSON</pre><p class="text-muted mb-0">Hanya produk berstatus <b>Normal</b> dan <b>Publish</b> yang dikirim ke reseller DHRU.</p></section>
 </div>
 <section id="examples" class="api-card"><h3>3. Place IMEI Order</h3><p class="text-muted">Gunakan Service ID dari response <b>imeiservicelist</b>. Parameter order dikirim dalam format DHRU.</p><pre class="code">action=placeimeiorder
username=USERNAME_RESELLER
apiaccesskey=API_ACCESS_KEY
requestformat=JSON
parameters={"ID":"SERVICE_ID","IMEI":"123456789012345"}</pre><h5 class="mt-4">Response berhasil</h5><pre class="code">{
  "SUCCESS": [{
    "MESSAGE": "Order received",
    "REFERENCEID": "123"
  }]
}</pre><h5 class="mt-4">Cek status</h5><pre class="code">action=getimeiorder
username=USERNAME_RESELLER
apiaccesskey=API_ACCESS_KEY
requestformat=JSON
parameters={"ID":"123"}</pre><p class="text-muted mb-0">Status code kompatibel: <b>0 Pending</b>, <b>1 Processing</b>, <b>3 Error</b>, <b>4 Success</b>.</p></section>
 <section id="legacy" class="api-card"><h3><i class="mdi mdi-history"></i> Legacy Produk Digital API</h3><p class="text-muted">Endpoint lama tetap dipertahankan untuk kompatibilitas aplikasi reseller yang sudah terhubung.</p><div class="endpoint"><span class="method">POST</span><span class="url"><?=htmlspecialchars($legacyUrl)?></span></div><div class="table-wrap"><table class="api-table"><thead><tr><th>Action</th><th>Parameter</th><th>Keterangan</th></tr></thead><tbody><tr><td>layanan</td><td>api_key</td><td>Mengambil katalog layanan.</td></tr><tr><td>pemesanan</td><td>api_key, layanan, target, no_meter</td><td>Membuat order digital.</td></tr><tr><td>status</td><td>api_key, id</td><td>Mengambil status order.</td></tr></tbody></table></div><pre class="code mt-3">{
  "status": true,
  "data": {
    "id": "ORDER_ID"
  }
}</pre></section>
 <section id="security" class="api-card"><h3><i class="mdi mdi-shield-check-outline"></i> Authentication & Security</h3><ul><li>Jangan taruh API key di frontend/browser.</li><li>Simpan credential di environment/server-side.</li><li>Gunakan HTTPS untuk semua request.</li><li>Jangan menampilkan API Access Key di screenshot atau log publik.</li><li>Jika key bocor, segera nonaktifkan/rotasi key reseller.</li></ul><div class="alert alert-warning mb-0"><b>Catatan:</b> API DHRU menggunakan kredensial reseller panel. Ini berbeda dengan credential DHRU upstream yang hanya disimpan di Admin Provider.</div></section>
</div>
<script>document.querySelectorAll('.endpoint').forEach(function(x){x.title='Klik untuk menyalin';x.classList.add('copy');x.addEventListener('click',function(){var u=x.querySelector('.url');if(u&&navigator.clipboard){navigator.clipboard.writeText(u.textContent.trim());var old=u.textContent;u.textContent='Disalin ✓';setTimeout(function(){u.textContent=old},900);}})});</script>
<?php require_once __DIR__ . '/../lib/footer.php'; ?>