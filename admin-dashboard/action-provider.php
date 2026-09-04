<?php
declare(strict_types=1);
session_start();
require '../config.php';
require '../lib/session_login_admin.php';
require '../lib/providers/CeirGoClient.php';
require '../lib/header_admin.php';

$ceirgo = $conn->query("SELECT * FROM provider WHERE code = 'ceirgo' LIMIT 1")->fetch_assoc() ?: null;
$wagateway = $conn->query("SELECT * FROM provider WHERE code = 'WAGATEWAY' LIMIT 1")->fetch_assoc() ?: null;

$ceirgoProfile = null;
$ceirgoWallet = null;
$ceirgoError = null;

if ($ceirgo && trim((string)$ceirgo['api_key']) !== '') {
    try {
        $baseUrl = trim((string)($ceirgo['link'] ?? '')) ?: 'https://ceirgo.id';
        $client = new CeirGoClient((string)$ceirgo['api_key'], $baseUrl);
        $ceirgoProfile = $client->me();
        $ceirgoWallet = $client->wallet();
    } catch (Throwable $e) {
        $ceirgoError = $e->getMessage();
    }
}

$profileData = is_array($ceirgoProfile['data'] ?? null) ? $ceirgoProfile['data'] : ($ceirgoProfile ?: []);
$walletData = is_array($ceirgoWallet['data'] ?? null) ? $ceirgoWallet['data'] : ($ceirgoWallet ?: []);
$username = $profileData['username'] ?? $profileData['name'] ?? '-';
$email = $profileData['email'] ?? '-';
$balance = $walletData['balance'] ?? $walletData['saldo'] ?? 0;
$reserved = $walletData['reserved'] ?? 0;
?>

<div class="row">
    <div class="col-md-12">
        <br><h2 class="text-center">Manajemen Provider & Akun Server</h2><br>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title text-center">INFORMASI AKUN CEIRGO</h4><hr>
                <?php if ($ceirgoError): ?>
                    <div class="alert alert-danger"><b>Gagal mengambil data akun.</b><br><?= htmlspecialchars($ceirgoError) ?></div>
                <?php elseif (!$ceirgo): ?>
                    <div class="alert alert-warning">Provider <b>ceirgo</b> belum dikonfigurasi.</div>
                <?php else: ?>
                    <div class="btn btn-primary btn-block">ceirgo.id</div>
                    <div class="text-dark mt-3">
                        <div><b>Username:</b> <?= htmlspecialchars((string)$username) ?></div>
                        <div><b>Email:</b> <?= htmlspecialchars((string)$email) ?></div>
                        <div><b>Saldo:</b> Rp <?= number_format((float)$balance, 0, ',', '.') ?></div>
                        <div><b>Reserved:</b> Rp <?= number_format((float)$reserved, 0, ',', '.') ?></div>
                    </div>
                <?php endif; ?>

                <div class="btn btn-primary btn-block mt-3">WAGATEWAY</div>
                <div class="text-dark mt-2">
                    <?= $wagateway ? '<b>Provider terkonfigurasi.</b>' : '<b>Provider belum dikonfigurasi.</b>' ?>
                    <?php if ($wagateway && !empty($wagateway['link'])): ?>
                        <br><?= htmlspecialchars((string)$wagateway['link']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title text-center">UPDATE LAYANAN & KATEGORI</h4><hr>
                <div class="text-center">
                    <b>CEIRGO</b>
                    <div class="mt-2">
                        <a class="btn btn-info waves-effect" href="../get/upd-all-category-digital" target="_blank">Upd. Kategori</a>
                        <a class="btn btn-info waves-effect" href="../get/upd-all-layanan-digital" target="_blank">Upd. Layanan</a>
                    </div>
                    <div class="mt-2">
                        <a class="btn btn-success waves-effect" href="../cronsjob/status_digital" target="_blank">Status Order</a>
                    </div>
                    <hr>
                    <b>DHRU</b>
                    <div class="mt-2">
                        <a class="btn btn-info waves-effect" href="dhru-products">Kelola Produk DHRU</a>
                        <a class="btn btn-success waves-effect" href="../get/sync-dhru" target="_blank">Sync DHRU</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title text-center">HAPUS LAYANAN</h4><hr>
                <div class="text-center">
                    <b>CEIRGO</b>
                    <div class="mt-2"><a class="btn btn-warning waves-effect" href="../get/del-all-layanan-digital" target="_blank">Del. Digital</a></div>
                    <div class="mt-2"><a class="btn btn-danger waves-effect" href="../get/del-all-layanan" target="_blank">Delete All</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../lib/footer_admin.php'; ?>
