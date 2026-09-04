<?php
declare(strict_types=1);

final class OrderService
{
    public function __construct(private mysqli $db) {}

    public function createPendingDigital(string $username, string $serviceProviderId, string $operator, string $target, string $noMeter = '', string $source = 'Website'): array
    {
        $target = trim($target);
        if ($target === '' || !preg_match('/^\d{14,16}$/', $target)) throw new InvalidArgumentException('Nomor IMEI tidak valid.');
        return $this->create($username, $serviceProviderId, $target, $noMeter, $source, '');
    }

    public function createPendingDigitalGeneric(string $username, string $serviceProviderId, string $target, string $noMeter = '', string $source = 'Website', string $orderData = ''): array
    {
        $target = trim($target);
        if ($target === '') throw new InvalidArgumentException('Target order wajib diisi.');
        if (strlen($target) > 500) throw new InvalidArgumentException('Target order terlalu panjang.');
        return $this->create($username, $serviceProviderId, $target, $noMeter, $source, $orderData);
    }

    private function one(string $sql): ?array
    {
        $q = $this->db->query($sql);
        if (!$q) throw new RuntimeException('Database query gagal.');
        $row = $q->fetch_assoc() ?: null;
        $q->free();
        return $row;
    }

    private function create(string $username, string $serviceProviderId, string $target, string $noMeter, string $source, string $orderData): array
    {
        $this->db->begin_transaction();
        try {
            $sid = $this->db->real_escape_string($serviceProviderId);
            $service = $this->one("SELECT id,provider_id,layanan,harga,harga_api,profit,provider FROM layanan_digital WHERE provider_id='{$sid}' AND status='Normal' LIMIT 1 FOR UPDATE");
            if (!$service) throw new RuntimeException('Produk tidak tersedia.');

            $price = (int)$service['harga'];
            $cost = (int)$service['harga_api'];
            if ($price <= 0) throw new RuntimeException('Harga produk tidak valid.');

            $userKey = $this->db->real_escape_string($username);
            $user = $this->one("SELECT id,username,saldo FROM users WHERE username='{$userKey}' AND status='Aktif' LIMIT 1 FOR UPDATE");
            if (!$user) throw new RuntimeException('Akun tidak aktif.');
            if ((int)$user['saldo'] < $price) throw new RuntimeException('Saldo Tidak Mencukupi');

            $targetKey = $this->db->real_escape_string($target);
            $providerKey = $this->db->real_escape_string((string)$service['provider']);
            $existing = $this->one("SELECT oid FROM pembelian_digital WHERE user='{$userKey}' AND target='{$targetKey}' AND provider='{$providerKey}' AND status IN ('Pending','Processing') LIMIT 1");
            if ($existing) throw new RuntimeException('Target masih memiliki order aktif: ' . $existing['oid']);

            $oid = 'O' . date('ymdHis') . random_int(1000, 9999);
            $profit = (int)$service['profit'];
            $source = $source !== '' ? substr($source, 0, 50) : 'Website';
            $orderData = substr($orderData, 0, 60000);

            $i = $this->db->prepare("INSERT INTO pembelian_digital (oid,provider_oid,user,layanan,harga,profit,target,no_meter,keterangan,status,date,time,place_from,provider,refund) VALUES (?,'',?,?,?,?,?,?,?,'Pending',CURDATE(),CURTIME(),?,?,0)");
            if (!$i) throw new RuntimeException('Gagal menyiapkan order.');
            $i->bind_param('sssiisssss', $oid, $username, $service['layanan'], $price, $profit, $target, $noMeter, $orderData, $source, $service['provider']);
            if (!$i->execute()) { $error = $i->error; $i->close(); throw new RuntimeException('Gagal membuat order: ' . $error); }
            $i->close();

            $p = $this->db->prepare("INSERT INTO provider_orders_v2 (local_order_id,provider,user_id,service_id,target,cost,sell_price,status) VALUES (?,?,?,?,?,?,?,'pending')");
            if (!$p) throw new RuntimeException('Gagal menyiapkan provider order.');
            $p->bind_param('ssissii', $oid, $service['provider'], $user['id'], $service['provider_id'], $target, $cost, $price);
            if (!$p->execute()) { $error = $p->error; $p->close(); throw new RuntimeException('Gagal membuat provider order: ' . $error); }
            $p->close();

            $this->db->commit();
            try {
                (new BalanceService($this->db))->debitByUsername($username, $price, 'order:' . $oid, 'Order ID ' . $oid . ' Produk Digital');
            } catch (Throwable $e) {
                $this->cancelUnfunded($oid, $e->getMessage());
                throw $e;
            }
            return ['oid'=>$oid, 'service'=>$service, 'price'=>$price];
        } catch (Throwable $e) {
            if ($this->db->in_transaction) $this->db->rollback();
            throw $e;
        }
    }

    private function cancelUnfunded(string $oid, string $message): void
    {
        $s = $this->db->prepare("UPDATE pembelian_digital SET status='Error',keterangan=? WHERE oid=? AND status='Pending' AND refund=0");
        if ($s) { $s->bind_param('ss', $message, $oid); $s->execute(); $s->close(); }
        $s = $this->db->prepare("UPDATE provider_orders_v2 SET status='failed',response_message=? WHERE local_order_id=?");
        if ($s) { $s->bind_param('ss', $message, $oid); $s->execute(); $s->close(); }
    }

    public function markProviderAccepted(string $oid, string $providerOid, string $message = ''): void
    {
        $s = $this->db->prepare("UPDATE pembelian_digital SET provider_oid=?,status='Processing',keterangan=? WHERE oid=? AND status='Pending'");
        if (!$s) throw new RuntimeException('Gagal memperbarui order.');
        $s->bind_param('sss', $providerOid, $message, $oid); $s->execute(); $changed = $s->affected_rows === 1; $s->close();
        if (!$changed) throw new RuntimeException('Order sudah berubah status atau tidak ditemukan.');

        $s = $this->db->prepare("UPDATE provider_orders_v2 SET provider_order_id=?,status='processing',response_message=? WHERE local_order_id=?");
        if ($s) { $s->bind_param('sss', $providerOid, $message, $oid); $s->execute(); $s->close(); }
    }

    public function markFailed(string $oid, string $message): void
    {
        $key = $this->db->real_escape_string($oid);
        $row = $this->one("SELECT status FROM pembelian_digital WHERE oid='{$key}' LIMIT 1");
        if (!$row || in_array($row['status'], ['Success','Error'], true)) return;

        $s = $this->db->prepare("UPDATE pembelian_digital SET status='Error',keterangan=? WHERE oid=? AND refund=0");
        if (!$s) throw new RuntimeException('Gagal memperbarui status order.');
        $s->bind_param('ss', $message, $oid); $s->execute(); $changed = $s->affected_rows === 1; $s->close();
        if (!$changed) return;
        (new BalanceService($this->db))->refundDigitalOrder($oid);
    }
}
