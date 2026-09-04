<?php
declare(strict_types=1);

/** Central wallet. Every credit/debit is atomic and idempotent. */
final class BalanceService
{
    public function __construct(private mysqli $db) {}

    public function debitByUsername(string $username, int $amount, string $referenceKey, string $message): void
    { $this->mutate($username, $amount, 'debit', $referenceKey, 'Pengurangan Saldo', $message); }

    public function creditByUsername(string $username, int $amount, string $referenceKey, string $message): void
    { $this->mutate($username, $amount, 'credit', $referenceKey, 'Penambahan Saldo', $message); }

    public function refundByUsername(string $username, int $amount, string $referenceKey, string $message): void
    { $this->mutate($username, $amount, 'credit', 'refund:' . $referenceKey, 'Pengembalian Saldo', $message); }

    /** Refund a digital order exactly once, including order flag and wallet ledger in one transaction. */
    public function refundDigitalOrder(string $oid): bool
    {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare('SELECT oid,user,harga,profit,refund,status FROM pembelian_digital WHERE oid=? LIMIT 1 FOR UPDATE');
            $stmt->bind_param('s', $oid); $stmt->execute(); $order = $stmt->get_result()->fetch_assoc(); $stmt->close();
            if (!$order || (int)$order['refund'] === 1) { $this->db->commit(); return false; }
            if (!in_array($order['status'], ['Error','Failed','Rejected'], true)) throw new RuntimeException('Order belum eligible refund.');

            $userStmt = $this->db->prepare('SELECT id,username,saldo,pemakaian_saldo FROM users WHERE username=? LIMIT 1 FOR UPDATE');
            $userStmt->bind_param('s', $order['user']); $userStmt->execute(); $user = $userStmt->get_result()->fetch_assoc(); $userStmt->close();
            if (!$user) throw new RuntimeException('User refund tidak ditemukan.');

            $amount = (int)$order['harga']; $before = (int)$user['saldo']; $after = $before + $amount;
            $upd = $this->db->prepare('UPDATE users SET saldo=saldo+?, pemakaian_saldo=GREATEST(pemakaian_saldo-?,0) WHERE id=?');
            $upd->bind_param('iii', $amount, $amount, $user['id']); $upd->execute(); $upd->close();
            $profit = (int)$order['profit'];
            $orderUpd = $this->db->prepare('UPDATE pembelian_digital SET refund=1,profit=profit-? WHERE oid=? AND refund=0');
            $orderUpd->bind_param('is', $profit, $oid); $orderUpd->execute();
            if ($orderUpd->affected_rows !== 1) { $orderUpd->close(); throw new RuntimeException('Refund order berubah, dibatalkan.'); }
            $orderUpd->close();
            $this->insertLedger($user['id'], $user['username'], 'credit', $amount, $before, $after, 'refund:order:' . $oid, 'Pengembalian Saldo', 'Pengembalian Dana. Order ID ' . $oid);
            $this->insertLegacyHistory($user['username'], 'Pengembalian Saldo', $amount, 'Pengembalian Dana. Order ID ' . $oid);
            $this->db->commit(); return true;
        } catch (Throwable $e) { $this->db->rollback(); throw $e; }
    }

    private function mutate(string $username, int $amount, string $direction, string $referenceKey, string $action, string $message): void
    {
        if ($amount <= 0) throw new InvalidArgumentException('Invalid balance amount.');
        if ($referenceKey === '') throw new InvalidArgumentException('Balance reference is required.');
        $this->db->begin_transaction();
        try {
            $check = $this->db->prepare('SELECT id FROM wallet_ledger WHERE reference_key=? LIMIT 1');
            $check->bind_param('s', $referenceKey); $check->execute();
            if ($check->get_result()->fetch_assoc()) { $check->close(); $this->db->commit(); return; }
            $check->close();
            $stmt = $this->db->prepare("SELECT id,username,saldo,pemakaian_saldo FROM users WHERE username=? AND status='Aktif' LIMIT 1 FOR UPDATE");
            $stmt->bind_param('s', $username); $stmt->execute(); $user=$stmt->get_result()->fetch_assoc(); $stmt->close();
            if (!$user) throw new RuntimeException('User not found or inactive.');
            $before=(int)$user['saldo'];
            if ($direction==='debit' && $before<$amount) throw new RuntimeException('Saldo Tidak Mencukupi');
            $after=$direction==='debit'?$before-$amount:$before+$amount;
            $upd=$this->db->prepare($direction==='debit' ? 'UPDATE users SET saldo=saldo-?,pemakaian_saldo=pemakaian_saldo+? WHERE id=? AND saldo>=?' : 'UPDATE users SET saldo=saldo+? WHERE id=?');
            if($direction==='debit') $upd->bind_param('iiii',$amount,$amount,$user['id'],$amount); else $upd->bind_param('ii',$amount,$user['id']);
            $upd->execute(); if($upd->affected_rows!==1){$upd->close();throw new RuntimeException('Balance mutation failed.');}$upd->close();
            if($direction==='credit' && $action==='Penambahan Saldo'){ /* no usage adjustment for normal deposits */ }
            $this->insertLedger($user['id'],$user['username'],$direction,$amount,$before,$after,$referenceKey,$action,$message);
            $this->insertLegacyHistory($user['username'],$action,$amount,$message);
            $this->db->commit();
        } catch(Throwable $e){$this->db->rollback();throw $e;}
    }

    private function insertLedger(int $userId,string $username,string $direction,int $amount,int $before,int $after,string $referenceKey,string $action,string $message):void
    {
        $stmt=$this->db->prepare('INSERT INTO wallet_ledger (user_id,username,direction,amount,balance_before,balance_after,reference_key,action,message) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('issiiisss',$userId,$username,$direction,$amount,$before,$after,$referenceKey,$action,$message);$stmt->execute();$stmt->close();
    }

    private function insertLegacyHistory(string $username,string $action,int $amount,string $message):void
    {
        $stmt=$this->db->prepare('INSERT INTO history_saldo (username,aksi,nominal,pesan,date,time) VALUES (?,?,?,?,CURDATE(),CURTIME())');
        $stmt->bind_param('ssis',$username,$action,$amount,$message);$stmt->execute();$stmt->close();
    }
}
