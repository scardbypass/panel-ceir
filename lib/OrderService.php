<?php
declare(strict_types=1);

final class OrderService
{
    public function __construct(private mysqli $db) {}

    public function createPendingDigital(string $username, string $serviceProviderId, string $operator, string $target, string $noMeter = '', string $source = 'Website'): array
    {
        $target=trim($target); if($target===''||!preg_match('/^\d{14,16}$/',$target)) throw new InvalidArgumentException('Nomor IMEI tidak valid.');
        $this->db->begin_transaction();
        try{
            $s=$this->db->prepare("SELECT id,provider_id,layanan,harga,profit,provider FROM layanan_digital WHERE provider_id=? AND status='Normal' LIMIT 1 FOR UPDATE");$s->bind_param('s',$serviceProviderId);$s->execute();$service=$s->get_result()->fetch_assoc();$s->close();
            if(!$service)throw new RuntimeException('Produk tidak tersedia.');$price=(int)$service['harga'];if($price<=0)throw new RuntimeException('Harga produk tidak valid.');
            $u=$this->db->prepare("SELECT id,username,saldo FROM users WHERE username=? AND status='Aktif' LIMIT 1 FOR UPDATE");$u->bind_param('s',$username);$u->execute();$user=$u->get_result()->fetch_assoc();$u->close();
            if(!$user)throw new RuntimeException('Akun tidak aktif.');if((int)$user['saldo']<$price)throw new RuntimeException('Saldo Tidak Mencukupi');
            $d=$this->db->prepare("SELECT oid FROM pembelian_digital WHERE user=? AND target=? AND provider=? AND status IN ('Pending','Processing') LIMIT 1");$d->bind_param('sss',$username,$target,$service['provider']);$d->execute();$existing=$d->get_result()->fetch_assoc();$d->close();if($existing)throw new RuntimeException('Target masih memiliki order aktif: '.$existing['oid']);
            $oid='O'.date('ymdHis').random_int(1000,9999);$profit=(int)$service['profit'];
            $i=$this->db->prepare("INSERT INTO pembelian_digital (oid,provider_oid,user,layanan,harga,profit,target,no_meter,keterangan,status,date,time,place_from,provider,refund) VALUES (?,'',?,?,?,?,?,'','Pending',CURDATE(),CURTIME(),?,?,0)");
            $i->bind_param('sssiiiss',$oid,$username,$service['layanan'],$price,$profit,$target,$noMeter,$source,$service['provider']);$i->execute();$i->close();
            $p=$this->db->prepare("INSERT INTO provider_orders_v2 (local_order_id,provider,user_id,service_id,target,cost,sell_price,status) VALUES (?,?,?,?,?,?,?,'pending')");$p->bind_param('ssissii',$oid,$service['provider'],$user['id'],$service['provider_id'],$target,$price,$price);$p->execute();$p->close();$this->db->commit();
            try{(new BalanceService($this->db))->debitByUsername($username,$price,'order:'.$oid,'Order ID '.$oid.' Produk Digital');}catch(Throwable $e){$this->cancelUnfunded($oid,$e->getMessage());throw $e;}
            return ['oid'=>$oid,'service'=>$service,'price'=>$price];
        }catch(Throwable $e){$this->db->rollback();throw $e;}
    }

    private function cancelUnfunded(string $oid,string $message):void{$s=$this->db->prepare("UPDATE pembelian_digital SET status='Error',keterangan=? WHERE oid=? AND status='Pending' AND refund=0");$s->bind_param('ss',$message,$oid);$s->execute();$s->close();$s=$this->db->prepare("UPDATE provider_orders_v2 SET status='failed',response_message=? WHERE local_order_id=?");$s->bind_param('ss',$message,$oid);$s->execute();$s->close();}

    public function markProviderAccepted(string $oid,string $providerOid,string $message=''):void{$s=$this->db->prepare("UPDATE pembelian_digital SET provider_oid=?,status='Processing',keterangan=? WHERE oid=? AND status='Pending'");$s->bind_param('sss',$providerOid,$message,$oid);$s->execute();$s->close();$s=$this->db->prepare("UPDATE provider_orders_v2 SET provider_order_id=?,status='processing',response_message=? WHERE local_order_id=?");$s->bind_param('sss',$providerOid,$message,$oid);$s->execute();$s->close();}

    public function markFailed(string $oid,string $message):void{$s=$this->db->prepare("SELECT status FROM pembelian_digital WHERE oid=? LIMIT 1");$s->bind_param('s',$oid);$s->execute();$row=$s->get_result()->fetch_assoc();$s->close();if(!$row||$row['status']==='Success'||$row['status']==='Error')return;$s=$this->db->prepare("UPDATE pembelian_digital SET status='Error',keterangan=? WHERE oid=? AND refund=0");$s->bind_param('ss',$message,$oid);$s->execute();$changed=$s->affected_rows===1;$s->close();if(!$changed)return;(new BalanceService($this->db))->refundDigitalOrder($oid);}
}
