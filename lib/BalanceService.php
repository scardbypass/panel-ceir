<?php
declare(strict_types=1);

/** Central wallet. Every credit/debit/transfer is atomic and idempotent. */
final class BalanceService
{
    public function __construct(private mysqli $db) {}
    public function debitByUsername(string $username,int $amount,string $referenceKey,string $message):void{$this->mutate($username,$amount,'debit',$referenceKey,'Pengurangan Saldo',$message);}
    public function creditByUsername(string $username,int $amount,string $referenceKey,string $message):void{$this->mutate($username,$amount,'credit',$referenceKey,'Penambahan Saldo',$message);}
    public function refundByUsername(string $username,int $amount,string $referenceKey,string $message):void{$this->mutate($username,$amount,'credit','refund:'.$referenceKey,'Pengembalian Saldo',$message);}

    public function transfer(string $from,string $to,int $amount,string $referenceKey):void
    {
        if($amount<=0||$from===$to)throw new InvalidArgumentException('Transfer tidak valid.');
        $this->db->begin_transaction();
        try{
            $c=$this->db->prepare('SELECT id FROM wallet_ledger WHERE reference_key=? LIMIT 1');$c->bind_param('s',$referenceKey);$c->execute();if($c->get_result()->fetch_assoc()){$c->close();$this->db->commit();return;}$c->close();
            $first=min($from,$to);$second=max($from,$to);
            $s=$this->db->prepare("SELECT id,username,saldo FROM users WHERE username IN (?,?) AND status='Aktif' ORDER BY username FOR UPDATE");$s->bind_param('ss',$first,$second);$s->execute();$rs=$s->get_result();$users=[];while($r=$rs->fetch_assoc())$users[$r['username']]=$r;$s->close();
            if(!isset($users[$from],$users[$to]))throw new RuntimeException('User transfer tidak ditemukan atau tidak aktif.');if((int)$users[$from]['saldo']<$amount)throw new RuntimeException('Saldo Tidak Mencukupi');
            $fb=(int)$users[$from]['saldo'];$tb=(int)$users[$to]['saldo'];
            $u=$this->db->prepare('UPDATE users SET saldo=saldo-?,pemakaian_saldo=pemakaian_saldo+? WHERE id=? AND saldo>=?');$u->bind_param('iiii',$amount,$amount,$users[$from]['id'],$amount);$u->execute();if($u->affected_rows!==1){$u->close();throw new RuntimeException('Saldo pengirim berubah.');}$u->close();
            $u=$this->db->prepare('UPDATE users SET saldo=saldo+? WHERE id=?');$u->bind_param('ii',$amount,$users[$to]['id']);$u->execute();$u->close();
            $this->insertLedger($users[$from]['id'],$from,'debit',$amount,$fb,$fb-$amount,$referenceKey,'Pengurangan Saldo','Transfer ke '.$to);
            $this->insertLedger($users[$to]['id'],$to,'credit',$amount,$tb,$tb+$amount,$referenceKey.':credit','Penambahan Saldo','Transfer dari '.$from);
            $this->insertLegacyHistory($from,'Pengurangan Saldo',$amount,'Transfer Saldo Kepada '.$to.' Sejumlah '.$amount);
            $this->insertLegacyHistory($to,'Penambahan Saldo',$amount,'Mendapatkan Transfer Saldo Dari '.$from.' Sejumlah '.$amount);
            $this->db->commit();
        }catch(Throwable $e){$this->db->rollback();throw $e;}
    }

    public function refundDigitalOrder(string $oid):bool
    {
        $this->db->begin_transaction();
        try{
            $s=$this->db->prepare('SELECT oid,user,harga,profit,refund,status FROM pembelian_digital WHERE oid=? LIMIT 1 FOR UPDATE');$s->bind_param('s',$oid);$s->execute();$o=$s->get_result()->fetch_assoc();$s->close();if(!$o||(int)$o['refund']===1){$this->db->commit();return false;}if(!in_array($o['status'],['Error','Failed','Rejected'],true))throw new RuntimeException('Order belum eligible refund.');
            $s=$this->db->prepare('SELECT id,username,saldo FROM users WHERE username=? LIMIT 1 FOR UPDATE');$s->bind_param('s',$o['user']);$s->execute();$u=$s->get_result()->fetch_assoc();$s->close();if(!$u)throw new RuntimeException('User refund tidak ditemukan.');
            $amount=(int)$o['harga'];$before=(int)$u['saldo'];$profit=(int)$o['profit'];$s=$this->db->prepare('UPDATE users SET saldo=saldo+? WHERE id=?');$s->bind_param('ii',$amount,$u['id']);$s->execute();$s->close();$s=$this->db->prepare('UPDATE pembelian_digital SET refund=1,profit=profit-? WHERE oid=? AND refund=0');$s->bind_param('is',$profit,$oid);$s->execute();if($s->affected_rows!==1){$s->close();throw new RuntimeException('Refund berubah.');}$s->close();$this->insertLedger($u['id'],$u['username'],'credit',$amount,$before,$before+$amount,'refund:order:'.$oid,'Pengembalian Saldo','Pengembalian Dana. Order ID '.$oid);$this->insertLegacyHistory($u['username'],'Pengembalian Saldo',$amount,'Pengembalian Dana. Order ID '.$oid);$this->db->commit();return true;
        }catch(Throwable $e){$this->db->rollback();throw $e;}
    }

    private function mutate(string $username,int $amount,string $direction,string $referenceKey,string $action,string $message):void
    {
        if($amount<=0||$referenceKey==='')throw new InvalidArgumentException('Invalid balance mutation.');$this->db->begin_transaction();
        try{
            $c=$this->db->prepare('SELECT id FROM wallet_ledger WHERE reference_key=? LIMIT 1');$c->bind_param('s',$referenceKey);$c->execute();if($c->get_result()->fetch_assoc()){$c->close();$this->db->commit();return;}$c->close();
            $s=$this->db->prepare("SELECT id,username,saldo FROM users WHERE username=? AND status='Aktif' LIMIT 1 FOR UPDATE");$s->bind_param('s',$username);$s->execute();$u=$s->get_result()->fetch_assoc();$s->close();if(!$u)throw new RuntimeException('User not found or inactive.');$before=(int)$u['saldo'];if($direction==='debit'&&$before<$amount)throw new RuntimeException('Saldo Tidak Mencukupi');$after=$direction==='debit'?$before-$amount:$before+$amount;
            if($direction==='debit'){$s=$this->db->prepare('UPDATE users SET saldo=saldo-?,pemakaian_saldo=pemakaian_saldo+? WHERE id=? AND saldo>=?');$s->bind_param('iiii',$amount,$amount,$u['id'],$amount);}else{$s=$this->db->prepare('UPDATE users SET saldo=saldo+? WHERE id=?');$s->bind_param('ii',$amount,$u['id']);}$s->execute();if($s->affected_rows!==1){$s->close();throw new RuntimeException('Balance mutation failed.');}$s->close();$this->insertLedger($u['id'],$u['username'],$direction,$amount,$before,$after,$referenceKey,$action,$message);$this->insertLegacyHistory($u['username'],$action,$amount,$message);$this->db->commit();
        }catch(Throwable $e){$this->db->rollback();throw $e;}
    }
    private function insertLedger(int $id,string $username,string $direction,int $amount,int $before,int $after,string $reference,string $action,string $message):void{$s=$this->db->prepare('INSERT INTO wallet_ledger (user_id,username,direction,amount,balance_before,balance_after,reference_key,action,message) VALUES (?,?,?,?,?,?,?,?,?)');$s->bind_param('issiiisss',$id,$username,$direction,$amount,$before,$after,$reference,$action,$message);$s->execute();$s->close();}
    private function insertLegacyHistory(string $username,string $action,int $amount,string $message):void{$s=$this->db->prepare('INSERT INTO history_saldo (username,aksi,nominal,pesan,date,time) VALUES (?,?,?,?,CURDATE(),CURTIME())');$s->bind_param('ssis',$username,$action,$amount,$message);$s->execute();$s->close();}
}
