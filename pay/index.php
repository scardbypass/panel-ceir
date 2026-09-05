<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_user.php';
require_once __DIR__ . '/../lib/session_login.php';
require_once __DIR__ . '/../lib/header.php';
?>
<title>Deposit Saldo — Order Center</title>
<meta name="description" content="Pilih metode deposit saldo dengan cepat dan aman.">
<style>
.dep-page{max-width:1180px;margin:0 auto;padding:8px 0 90px}.dep-hero{padding:28px;border-radius:28px;background:linear-gradient(135deg,#0f172a,#1d4ed8);color:#fff;box-shadow:0 18px 50px rgba(15,23,42,.18);margin-bottom:22px}.dep-kicker{font-size:12px;text-transform:uppercase;letter-spacing:.14em;opacity:.72}.dep-hero h1{font-size:clamp(28px,5vw,42px);font-weight:800;margin:8px 0}.dep-hero p{margin:0;opacity:.82;max-width:650px}.dep-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.dep-card{display:block;color:inherit;text-decoration:none;background:rgba(255,255,255,.9);border:1px solid rgba(148,163,184,.2);border-radius:24px;padding:20px;min-height:190px;box-shadow:0 10px 30px rgba(15,23,42,.06);transition:.2s}.dep-card:hover{transform:translateY(-3px);box-shadow:0 18px 38px rgba(15,23,42,.11);color:inherit}.dep-icon{width:58px;height:58px;border-radius:18px;display:grid;place-items:center;background:#eff6ff;color:#2563eb;font-size:27px;margin-bottom:18px}.dep-card h3{font-size:18px;font-weight:750;margin:0 0 7px}.dep-card p{font-size:13px;color:#64748b;margin:0;line-height:1.55}.dep-tag{display:inline-flex;margin-top:16px;padding:5px 9px;border-radius:999px;background:#f1f5f9;color:#475569;font-size:11px;font-weight:700}.dep-disabled{opacity:.58;cursor:not-allowed}.dep-disabled:hover{transform:none}.dep-bottom{margin-top:20px;padding:16px 20px;border-radius:20px;background:#f8fafc;color:#64748b;font-size:13px}@media(max-width:800px){.dep-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.dep-page{padding-top:0}.dep-grid{grid-template-columns:1fr}.dep-hero{padding:22px;border-radius:22px}}
</style>
<div class="dep-page">
<section class="dep-hero"><div class="dep-kicker">Wallet Center</div><h1>Tambah Saldo</h1><p>Pilih metode pembayaran yang tersedia. Setelah pembayaran terverifikasi, saldo akan masuk ke akun Anda sesuai ketentuan metode yang dipilih.</p></section>
<div class="dep-grid">
<a class="dep-card" href="pulsa"><div class="dep-icon"><i class="mdi mdi-cellphone"></i></div><h3>Pulsa TSEL</h3><p>Deposit manual menggunakan pulsa Telkomsel.</p><span class="dep-tag">Manual</span></a>
<a class="dep-card" href="emoney-manual"><div class="dep-icon"><i class="mdi mdi-wallet-outline"></i></div><h3>E-Money Manual</h3><p>Gunakan metode e-money yang tersedia untuk konfirmasi manual.</p><span class="dep-tag">Manual</span></a>
<a class="dep-card" href="bank-manual"><div class="dep-icon"><i class="mdi mdi-bank-outline"></i></div><h3>Bank Manual</h3><p>Transfer ke rekening tujuan lalu kirim bukti pembayaran.</p><span class="dep-tag">Manual</span></a>
<a class="dep-card" href="epayment-manual"><div class="dep-icon"><i class="mdi mdi-credit-card-outline"></i></div><h3>E-Payment Global</h3><p>Pilihan pembayaran elektronik yang tersedia di panel.</p><span class="dep-tag">Manual</span></a>
<div class="dep-card dep-disabled" onclick="return false"><div class="dep-icon"><i class="mdi mdi-bitcoin"></i></div><h3>Cryptocurrency</h3><p>Metode ini sedang dalam maintenance.</p><span class="dep-tag">Maintenance</span></div>
<a class="dep-card" href="redeem"><div class="dep-icon"><i class="mdi mdi-ticket-confirmation-outline"></i></div><h3>Redeem Voucher</h3><p>Masukkan voucher deposit untuk menambah saldo secara cepat.</p><span class="dep-tag">Voucher</span></a>
<a class="dep-card" href="bca"><div class="dep-icon"><i class="mdi mdi-bank-transfer-in"></i></div><h3>BCA Auto Confirm</h3><p>Deposit otomatis melalui rekening BCA yang terhubung.</p><span class="dep-tag">Auto Confirm</span></a>
<a class="dep-card" href="bni"><div class="dep-icon"><i class="mdi mdi-bank-transfer-in"></i></div><h3>BNI Auto Confirm</h3><p>Deposit otomatis melalui rekening BNI yang terhubung.</p><span class="dep-tag">Auto Confirm</span></a>
</div>
<div class="dep-bottom"><i class="mdi mdi-shield-check-outline"></i> Jangan pernah memberikan password atau kode OTP akun kepada pihak lain.</div>
</div>
<nav class="p4-mobile-bottom"><a href="/"><i class="mdi mdi-home-outline"></i>Home</a><a href="/pemesanan"><i class="mdi mdi-storefront-outline"></i>Layanan</a><a href="/riwayat/pemesanan-digital"><i class="mdi mdi-receipt-text-outline"></i>Order</a><a class="active" href="/user/mutasi"><i class="mdi mdi-wallet-outline"></i>Saldo</a></nav>
<?php require_once __DIR__ . '/../lib/footer.php'; ?>