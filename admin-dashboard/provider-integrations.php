<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php';

function esc($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function json_ok($v): string { return json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); }

$providers = [];
$q = $conn->query("SELECT id, provider_code, name, base_url, username, is_active, last_sync_at, last_error FROM provider_accounts ORDER BY provider_code, name");
if ($q) while ($row = $q->fetch_assoc()) $providers[] = $row;
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Provider Integrations</title>
<style>body{font-family:system-ui;background:#f4f7fb;margin:0;padding:24px;color:#172033}.wrap{max-width:1100px;margin:auto}.card{background:#fff;border:1px solid #e6eaf0;border-radius:16px;padding:20px;margin-bottom:16px;box-shadow:0 8px 30px #1720330a}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px}.pill{display:inline-block;padding:5px 9px;border-radius:99px;background:#edf6ff}.err{color:#b42318}.ok{color:#067647}button{padding:10px 14px;border:0;border-radius:10px;cursor:pointer;background:#172033;color:white}</style></head><body><div class="wrap">
<div class="card"><h1>Provider Integrations</h1><p>Kelola koneksi provider tanpa menaruh API key di source code.</p></div>
<div class="grid">
<?php foreach ($providers as $p): ?><div class="card"><h2><?=esc(strtoupper($p['provider_code']))?></h2><div>Nama: <b><?=esc($p['name'])?></b></div><div>Endpoint: <?=esc($p['base_url'])?></div><div>Status: <span class="pill"><?= $p['is_active'] ? 'Active' : 'Disabled' ?></span></div><div>Last sync: <?=esc($p['last_sync_at'] ?: '-')?></div><?php if($p['last_error']): ?><div class="err">Error: <?=esc($p['last_error'])?></div><?php endif; ?></div><?php endforeach; ?>
</div>
<div class="card"><h2>Integrasi yang disiapkan</h2><ul><li><b>CeirGo</b>: koneksi akun, sinkron produk, order, status, dan riwayat melalui adapter.</li><li><b>Dhru</b>: koneksi akun, get service list, order, dan cek status melalui adapter Dhru Fusion.</li><li><b>QRIS MARC</b>: transaksi dibuat server-side dan callback wajib diverifikasi sebelum saldo ditambahkan.</li></ul><p>Jangan masukkan credential provider langsung ke file PHP. Simpan di environment/secret store.</p></div>
</div></body></html>
