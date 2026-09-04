<?php
declare(strict_types=1);

/**
 * Single wallet service for every money movement.
 * Rules: lock the user row, never allow negative balance, and write the ledger
 * in the same DB transaction as the balance mutation.
 */
final class BalanceService
{
    public function __construct(private mysqli $db) {}

    public function debitByUsername(string $username, float $amount, string $message): void
    {
        if ($amount <= 0) throw new InvalidArgumentException('Invalid debit amount.');
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare('SELECT id, saldo, pemakaian_saldo FROM users WHERE username = ? FOR UPDATE');
            $stmt->bind_param('s', $username); $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
            if (!$user) throw new RuntimeException('User not found.');
            if ((float)$user['saldo'] < $amount) throw new RuntimeException('Saldo Tidak Mencukupi');

            $upd = $this->db->prepare('UPDATE users SET saldo = saldo - ?, pemakaian_saldo = pemakaian_saldo + ? WHERE id = ? AND saldo >= ?');
            $upd->bind_param('ddid', $amount, $amount, $user['id'], $amount); $upd->execute();
            if ($upd->affected_rows !== 1) { $upd->close(); throw new RuntimeException('Saldo berubah, silakan ulangi order.'); }
            $upd->close();
            $this->ledger($username, 'Pengurangan Saldo', $amount, $message);
            $this->db->commit();
        } catch (Throwable $e) { $this->db->rollback(); throw $e; }
    }

    public function refundByUsername(string $username, float $amount, string $message): void
    {
        if ($amount <= 0) return;
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare('SELECT id FROM users WHERE username = ? FOR UPDATE');
            $stmt->bind_param('s', $username); $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
            if (!$user) throw new RuntimeException('User not found.');

            $upd = $this->db->prepare('UPDATE users SET saldo = saldo + ?, pemakaian_saldo = GREATEST(pemakaian_saldo - ?, 0) WHERE id = ?');
            $upd->bind_param('ddi', $amount, $amount, $user['id']); $upd->execute(); $upd->close();
            $this->ledger($username, 'Pengembalian Saldo', $amount, $message);
            $this->db->commit();
        } catch (Throwable $e) { $this->db->rollback(); throw $e; }
    }

    private function ledger(string $username, string $action, float $amount, string $message): void
    {
        global $date, $time;
        $d = $date ?? date('Y-m-d'); $t = $time ?? date('H:i:s');
        $stmt = $this->db->prepare('INSERT INTO history_saldo (username, aksi, nominal, pesan, date, time) VALUES (?, ?, ?, ?, ?, ?)');
        if (!$stmt) throw new RuntimeException('Gagal menyiapkan mutasi saldo.');
        $stmt->bind_param('ssdsss', $username, $action, $amount, $message, $d, $t); $stmt->execute(); $stmt->close();
    }
}
