<?php
declare(strict_types=1);

/** Central wallet. Every credit/debit is atomic and idempotent. */
final class BalanceService
{
    public function __construct(private mysqli $db) {}

    public function debitByUsername(string $username, int $amount, string $referenceKey, string $message): void
    {
        $this->mutate($username, $amount, 'debit', $referenceKey, 'Pengurangan Saldo', $message);
    }

    public function creditByUsername(string $username, int $amount, string $referenceKey, string $message): void
    {
        $this->mutate($username, $amount, 'credit', $referenceKey, 'Penambahan Saldo', $message);
    }

    public function refundByUsername(string $username, int $amount, string $referenceKey, string $message): void
    {
        $this->mutate($username, $amount, 'credit', 'refund:' . $referenceKey, 'Pengembalian Saldo', $message);
    }

    private function mutate(string $username, int $amount, string $direction, string $referenceKey, string $action, string $message): void
    {
        if ($amount <= 0) throw new InvalidArgumentException('Invalid balance amount.');
        if ($referenceKey === '') throw new InvalidArgumentException('Balance reference is required.');

        $this->db->begin_transaction();
        try {
            $check = $this->db->prepare('SELECT id FROM wallet_ledger WHERE reference_key = ? LIMIT 1');
            $check->bind_param('s', $referenceKey); $check->execute();
            if ($check->get_result()->fetch_assoc()) { $check->close(); $this->db->commit(); return; }
            $check->close();

            $stmt = $this->db->prepare("SELECT id, username, saldo, pemakaian_saldo FROM users WHERE username = ? AND status = 'Aktif' LIMIT 1 FOR UPDATE");
            if (!$stmt) throw new RuntimeException('Wallet query failed.');
            $stmt->bind_param('s', $username); $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
            if (!$user) throw new RuntimeException('User not found or inactive.');

            $before = (int)$user['saldo'];
            if ($direction === 'debit' && $before < $amount) throw new RuntimeException('Saldo Tidak Mencukupi');
            $after = $direction === 'debit' ? $before - $amount : $before + $amount;

            $upd = $this->db->prepare($direction === 'debit'
                ? 'UPDATE users SET saldo = saldo - ?, pemakaian_saldo = pemakaian_saldo + ? WHERE id = ? AND saldo >= ?'
                : 'UPDATE users SET saldo = saldo + ?, pemakaian_saldo = GREATEST(pemakaian_saldo - ?, 0) WHERE id = ?');
            if ($direction === 'debit') $upd->bind_param('iiii', $amount, $amount, $user['id'], $amount);
            else $upd->bind_param('iii', $amount, $amount, $user['id']);
            $upd->execute();
            if ($upd->affected_rows !== 1) { $upd->close(); throw new RuntimeException('Balance mutation failed.'); }
            $upd->close();

            $ledger = $this->db->prepare('INSERT INTO wallet_ledger (user_id,username,direction,amount,balance_before,balance_after,reference_key,action,message) VALUES (?,?,?,?,?,?,?,?,?)');
            $ledger->bind_param('issiiisss', $user['id'], $user['username'], $direction, $amount, $before, $after, $referenceKey, $action, $message);
            $ledger->execute(); $ledger->close();

            $legacy = $this->db->prepare('INSERT INTO history_saldo (username,aksi,nominal,pesan,date,time) VALUES (?,?,?,?,CURDATE(),CURTIME())');
            $legacy->bind_param('ssis', $user['username'], $action, $amount, $message); $legacy->execute(); $legacy->close();
            $this->db->commit();
        } catch (Throwable $e) { $this->db->rollback(); throw $e; }
    }
}
