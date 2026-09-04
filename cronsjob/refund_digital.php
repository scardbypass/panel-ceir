<?php
require '../config.php';
require '../lib/BalanceService.php';

$service = new BalanceService($conn);
$q = $conn->query("SELECT oid FROM pembelian_digital WHERE status='Error' AND refund=0 ORDER BY id ASC LIMIT 100");
if (!$q || $q->num_rows === 0) { exit("Tidak ada order yang perlu refund.\n"); }

while ($row = $q->fetch_assoc()) {
    try {
        if ($service->refundDigitalOrder((string)$row['oid'])) {
            echo "Refund berhasil: {$row['oid']}\n";
        }
    } catch (Throwable $e) {
        error_log('Refund digital '.$row['oid'].': '.$e->getMessage());
        echo "Refund gagal: {$row['oid']} - {$e->getMessage()}\n";
    }
}
