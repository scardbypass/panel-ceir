<?php
declare(strict_types=1);

/** Central wallet. Every credit/debit/transfer is atomic and idempotent. */
final class BalanceService
{
    public function __construct(private mysqli $db) {}

    private function one(string $sql): ?array
    {
        $q = $this->db->query($sql);
        if (!$q) throw new RuntimeException('Database query gagal.');
        $row = $q->fetch_assoc() ?: null;
        $q->free();
        return $row;
    }

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

    public function transfer(string $from, string $to, int $amount, string $referenceKey): void
    {
        if ($amount <= 0 || $from === $to) throw new InvalidArgumentException('Transfer tidak valid.');
        $this->db->begin_transaction();
        try {
            $ref = $this->db->real_escape_string($referenceKey);
            if ($this->one("SELECT id FROM wallet_ledger WHERE reference_key='{$ref}' LIMIT 1")) {
                $this->db->commit();
                return;
            }

            $first = $this->db->real_escape_string(min($from, $to));
            $second = $this->db->real_escape_string(max($from, $to));
            $users = [];
            $q = $this->db->query("SELECT id,username,saldo FROM users WHERE username IN ('{$first}','{$second}') AND status='Aktif' ORDER BY username FOR UPDATE");
            if (!$q) throw new RuntimeException('User transfer query gagal.');
            while ($r = $q->fetch_assoc()) $users[$r['username']] = $r;
            $q->free();

            if (!isset($users[$from], $users[$to])) throw new RuntimeException('User transfer tidak ditemukan atau tidak aktif.');
            if ((int)$users[$from]['saldo'] < $amount) throw new RuntimeException('Saldo Tidak Mencukupi');

            $fb = (int)$users[$from]['saldo'];
            $tb = (int)$users[$to]['saldo'];
            $s = $this->db->prepare('UPDATE users SET saldo=saldo-?,pemakaian_saldo=pemakaian_saldo+? WHERE id=? AND saldo>=?');
            if (!$s) throw new RuntimeException('Gagal menyiapkan pengurangan saldo.');
            $s->bind_param('iiii', $amount, $amount, $users[$from]['id'], $amount);
            $s->execute();
            if ($s->affected_rows !== 1) { $s->close(); throw new RuntimeException('Saldo pengirim berubah.'); }
            $s->close();

            $s = $this->db->prepare('UPDATE users SET saldo=saldo+? WHERE id=?');
            if (!$s) throw new RuntimeException('Gagal menyiapkan penambahan saldo.');
            $s->bind_param('ii', $amount, $users[$to]['id']); $s->execute(); $s->close();

            $this->insertLedger($users[$from]['id'], $from, 'debit', $amount, $fb, $fb - $amount, $referenceKey, 'Pengurangan Saldo', 'Transfer ke ' . $to);
            $this->insertLedger($users[$to]['id'], $to, 'credit', $amount, $tb, $tb + $amount, $referenceKey . ':credit', 'Penambahan Saldo', 'Transfer dari ' . $from);
            $this->insertLegacyHistory($from, 'Pengurangan Saldo', $amount, 'Transfer Saldo Kepada ' . $to . ' Sejumlah ' . $amount);
            $this->insertLegacyHistory($to, 'Penambahan Saldo', $amount, 'Mendapatkan Transfer Saldo Dari ' . $from . ' Sejumlah ' . $amount);
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->in_transaction) $this->db->rollback();
            throw $e;
        }
    }

    public function refundDigitalOrder(string $oid): bool
    {
        $this->db->begin_transaction();
        try {
            $key = $this->db->real_escape_string($oid);
            $o = $this->one("SELECT oid,user,harga,profit,refund,status FROM pembelian_digital WHERE oid='{$key}' LIMIT 1 FOR UPDATE");
            if (!$o || (int)$o['refund'] === 1) { $this->db->commit(); return false; }
            if (!in_array($o['status'], ['Error','Failed','Rejected'], true)) throw new RuntimeException('Order belum eligible refund.');

            $userKey = $this->db->real_escape_string((string)$o['user']);
            $u = $this->one("SELECT id,username,saldo FROM users WHERE username='{$userKey}' LIMIT 1 FOR UPDATE");
            if (!$u) throw new RuntimeException('User refund tidak ditemukan.');

            $amount = (int)$o['harga'];
            $before = (int)$u['saldo'];
            $profit = (int)$o['profit'];
            $s = $this->db->prepare('UPDATE users SET saldo=saldo+? WHERE id=?');
            if (!$s) throw new RuntimeException('Gagal menyiapkan refund.');
            $s->bind_param('ii', $amount, $u['id']); $s->execute(); $s->close();

            $s = $this->db->prepare('UPDATE pembelian_digital SET refund=1,profit=profit-? WHERE oid=? AND refund=0');
            if (!$s) throw new RuntimeException('Gagal menyiapkan status refund.');
            $s->bind_param('is', $profit, $oid); $s->execute();
            if ($s->affected_rows !== 1) { $s->close(); throw new RuntimeException('Refund berubah.'); }
            $s->close();

            $this->insertLedger($u['id'], $u['username'], 'credit', $amount, $before, $before + $amount, 'refund:order:' . $oid, 'Pengembalian Saldo', 'Pengembalian Dana. Order ID ' . $oid);
            $this->insertLegacyHistory($u['username'], 'Pengembalian Saldo', $amount, 'Pengembalian Dana. Order ID ' . $oid);
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->db->in_transaction) $this->db->rollback();
            throw $e;
        }
    }

    private function mutate(string $username, int $amount, string $direction, string $referenceKey, string $action, string $message): void
    {
        if ($amount <= 0 || $referenceKey === '') throw new InvalidArgumentException('Invalid balance mutation.');
        $this->db->begin_transaction();
        try {
            $ref = $this->db->real_escape_string($referenceKey);
            if ($this->one("SELECT id FROM wallet_ledger WHERE reference_key='{$ref}' LIMIT 1")) {
                $this->db->commit();
                return;
            }

            $userKey = $this->db->real_escape_string($username);
            $u = $this->one("SELECT id,username,saldo FROM users WHERE username='{$userKey}' AND status='Aktif' LIMIT 1 FOR UPDATE");
            if (!$u) throw new RuntimeException('User not found or inactive.');

            $before = (int)$u['saldo'];
            if ($direction === 'debit' && $before < $amount) throw new RuntimeException('Saldo Tidak Mencukupi');
            $after = $direction === 'debit' ? $before - $amount : $before + $amount;

            if ($direction === 'debit') {
                $s = $this->db->prepare('UPDATE users SET saldo=saldo-?,pemakaian_saldo=pemakaian_saldo+? WHERE id=? AND saldo>=?');
                if (!$s) throw new RuntimeException('Gagal menyiapkan debit saldo.');
                $s->bind_param('iiii', $amount, $amount, $u['id'], $amount);
            } else {
                $s = $this->db->prepare('UPDATE users SET saldo=saldo+? WHERE id=?');
                if (!$s) throw new RuntimeException('Gagal menyiapkan credit saldo.');
                $s->bind_param('ii', $amount, $u['id']);
            }
            $s->execute();
            if ($s->affected_rows !== 1) { $s->close(); throw new RuntimeException('Balance mutation failed.'); }
            $s->close();

            $this->insertLedger($u['id'], $u['username'], $direction, $amount, $before, $after, $referenceKey, $action, $message);
            $this->insertLegacyHistory($u['username'], $action, $amount, $message);
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->in_transaction) $this->db->rollback();
            throw $e;
        }
    }

    private function insertLedger(int $id, string $username, string $direction, int $amount, int $before, int $after, string $reference, string $action, string $message): void
    {
        $s = $this->db->prepare('INSERT INTO wallet_ledger (user_id,username,direction,amount,balance_before,balance_after,reference_key,action,message) VALUES (?,?,?,?,?,?,?,?,?)');
        if (!$s) throw new RuntimeException('Gagal menyiapkan ledger saldo.');
        $s->bind_param('issiiisss', $id, $username, $direction, $amount, $before, $after, $reference, $action, $message);
        if (!$s->execute()) { $error = $s->error; $s->close(); throw new RuntimeException('Gagal menyimpan ledger: ' . $error); }
        $s->close();
    }

    private function insertLegacyHistory(string $username, string $action, int $amount, string $message): void
    {
        $s = $this->db->prepare('INSERT INTO history_saldo (username,aksi,nominal,pesan,date,time) VALUES (?,?,?,?,CURDATE(),CURTIME())');
        if (!$s) throw new RuntimeException('Gagal menyiapkan history saldo.');
        $s->bind_param('ssis', $username, $action, $amount, $message);
        if (!$s->execute()) { $error = $s->error; $s->close(); throw new RuntimeException('Gagal menyimpan history saldo: ' . $error); }
        $s->close();
    }
}
