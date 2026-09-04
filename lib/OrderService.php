<?php
declare(strict_types=1);

final class OrderService
{
    public function __construct(private mysqli $db) {}

    public function createPendingDigital(string $username, string $serviceProviderId, string $operator, string $target, string $noMeter = '', string $source = 'Website'): array
    {
        $target = trim($target);
        if ($target === '') throw new InvalidArgumentException('Target wajib diisi.');
        if (!preg_match('/^\d{14,16}$/', $target)) throw new InvalidArgumentException('IMEI tidak valid.');

        $this->db->begin_transaction();
        try {
            $serviceStmt = $this->db->prepare("SELECT id,provider_id,layanan,harga,profit,provider FROM layanan_digital WHERE provider_id = ? AND status = 'Normal' LIMIT 1 FOR UPDATE");
            $serviceStmt->bind_param('s', $serviceProviderId); $serviceStmt->execute();
            $service = $serviceStmt->get_result()->fetch_assoc(); $serviceStmt->close();
            if (!$service) throw new RuntimeException('Produk tidak tersedia.');
            $price = (int)$service['harga'];
            if ($price <= 0) throw new RuntimeException('Harga produk tidak valid.');

            $userStmt = $this->db->prepare("SELECT id,username,saldo FROM users WHERE username = ? AND status = 'Aktif' LIMIT 1 FOR UPDATE");
            $userStmt->bind_param('s', $username); $userStmt->execute();
            $user = $userStmt->get_result()->fetch_assoc(); $userStmt->close();
            if (!$user) throw new RuntimeException('Akun tidak aktif.');
            if ((int)$user['saldo'] < $price) throw new RuntimeException('Saldo Tidak Mencukupi');

            $dup = $this->db->prepare("SELECT oid FROM pembelian_digital WHERE user = ? AND target = ? AND provider = ? AND status IN ('Pending','Processing') LIMIT 1");
            $dup->bind_param('sss', $username, $target, $service['provider']); $dup->execute();
            $existing = $dup->get_result()->fetch_assoc(); $dup->close();
            if ($existing) throw new RuntimeException('Target masih memiliki order aktif: ' . $existing['oid']);

            $oid = 'O' . date('ymdHis') . random_int(1000, 9999);
            $insert = $this->db->prepare("INSERT INTO pembelian_digital (oid,provider_oid,user,layanan,harga,profit,target,no_meter,keterangan,status,date,time,place_from,provider,refund) VALUES (?, '', ?, ?, ?, ?, ?, ?, '', 'Pending', CURDATE(), CURTIME(), ?, ?, 0)");
            $profit = (int)$service['profit'];
            $insert->bind_param('sssiissss', $oid, $username, $service['layanan'], $price, $profit, $target, $noMeter, $source, $service['provider']);
            $insert->execute(); $insert->close();

            $providerOrder = $this->db->prepare("INSERT INTO provider_orders_v2 (local_order_id,provider,user_id,service_id,target,cost,sell_price,status) VALUES (?,?,?,?,?,?,?,'pending')");
            $providerOrder->bind_param('ssissii', $oid, $service['provider'], $user['id'], $service['provider_id'], $target, $price, $price);
            $providerOrder->execute(); $providerOrder->close();
            $this->db->commit();

            try {
                $balance = new BalanceService($this->db);
                $balance->debitByUsername($username, $price, 'order:' . $oid, 'Order ID ' . $oid . ' Produk Digital');
            } catch (Throwable $e) {
                $this->cancelUnfunded($oid, $e->getMessage());
                throw $e;
            }
            return ['oid' => $oid, 'service' => $service, 'price' => $price];
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    private function cancelUnfunded(string $oid, string $message): void
    {
        $stmt = $this->db->prepare("UPDATE pembelian_digital SET status='Error', keterangan=? WHERE oid=? AND status='Pending' AND refund=0");
        $stmt->bind_param('ss', $message, $oid); $stmt->execute(); $stmt->close();
        $stmt = $this->db->prepare("UPDATE provider_orders_v2 SET status='failed', response_message=? WHERE local_order_id=?");
        $stmt->bind_param('ss', $message, $oid); $stmt->execute(); $stmt->close();
    }

    public function markProviderAccepted(string $oid, string $providerOid, string $message = ''): void
    {
        $stmt = $this->db->prepare("UPDATE pembelian_digital SET provider_oid=?, status='Processing', keterangan=? WHERE oid=? AND status='Pending'");
        $stmt->bind_param('sss', $providerOid, $message, $oid); $stmt->execute(); $stmt->close();
        $stmt = $this->db->prepare("UPDATE provider_orders_v2 SET provider_order_id=?, status='processing', response_message=? WHERE local_order_id=?");
        $stmt->bind_param('sss', $providerOid, $message, $oid); $stmt->execute(); $stmt->close();
    }

    public function markFailed(string $oid, string $message): void
    {
        $stmt = $this->db->prepare("SELECT user,harga,status,refund FROM pembelian_digital WHERE oid=? LIMIT 1");
        $stmt->bind_param('s', $oid); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$row || in_array($row['status'], ['Success','Error'], true)) return;
        $stmt = $this->db->prepare("UPDATE pembelian_digital SET status='Error',keterangan=?,refund=1 WHERE oid=? AND refund=0");
        $stmt->bind_param('ss', $message, $oid); $stmt->execute(); $changed = $stmt->affected_rows === 1; $stmt->close();
        if (!$changed) return;
        (new BalanceService($this->db))->refundByUsername($row['user'], (int)$row['harga'], 'order:' . $oid, 'Refund Order ID ' . $oid);
        $stmt = $this->db->prepare("UPDATE provider_orders_v2 SET status='refunded',response_message=? WHERE local_order_id=?");
        $stmt->bind_param('ss', $message, $oid); $stmt->execute(); $stmt->close();
    }
}
