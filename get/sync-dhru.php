<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/providers/DhruClient.php';
require_once __DIR__ . '/../lib/session_login_admin.php';

header('Content-Type: text/plain; charset=utf-8');
$provider = $conn->query("SELECT * FROM provider WHERE code = 'DHRU' LIMIT 1")->fetch_assoc();
if (!$provider) exit("DHRU provider belum dibuat.\n");

try {
    $client = new DhruClient($provider['link'], $provider['api_id'] ?: $provider['code'], $provider['api_key']);
    $response = $client->getProducts();
    $groups = $response['SUCCESS'][0]['LIST'] ?? $response['SUCCESS'][0]['IMEIServiceList'] ?? [];
    $count = 0;
    foreach ($groups as $group) {
        $services = $group['SERVICES'] ?? $group['services'] ?? (isset($group['SERVICEID']) ? [$group] : []);
        foreach ($services as $service) {
            $sid = (string)($service['SERVICEID'] ?? $service['serviceid'] ?? '');
            $name = trim((string)($service['SERVICENAME'] ?? $service['servicename'] ?? ''));
            if ($sid === '' || $name === '') continue;
            $price = (float)($service['CREDIT'] ?? $service['credit'] ?? 0);
            $operator = trim((string)($group['GROUPNAME'] ?? $group['groupname'] ?? 'DHRU')) ?: 'DHRU';
            $status = 'Normal';
            $stmt = $conn->prepare("SELECT id FROM layanan_digital WHERE provider='DHRU' AND provider_id=? LIMIT 1");
            $stmt->bind_param('s', $sid); $stmt->execute(); $exists = $stmt->get_result()->fetch_assoc(); $stmt->close();
            if ($exists) {
                $stmt = $conn->prepare("UPDATE layanan_digital SET layanan=?, operator=?, harga_api=?, status=?, updated_at=NOW() WHERE id=?");
                $stmt->bind_param('ssdsi', $name, $operator, $price, $status, $exists['id']);
            } else {
                $stmt = $conn->prepare("INSERT INTO layanan_digital (service_id,provider_id,operator,layanan,harga,harga_api,profit,status,provider,tipe,catatan,public_visible,sort_order,updated_at) VALUES (?,?,?,?,?, ?, '0',?,'DHRU','Digital','',0,0,NOW())");
                $sell = $price; $stmt->bind_param('ssssdds', $sid, $sid, $operator, $name, $sell, $price, $status);
            }
            $stmt->execute(); $stmt->close(); $count++;
        }
    }
    echo "Sync DHRU selesai. $count layanan diproses. Produk baru default TIDAK tampil di depan.\n";
} catch (Throwable $e) {
    http_response_code(500); echo "Sync gagal: {$e->getMessage()}\n";
}
