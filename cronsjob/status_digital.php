<?php
require '../config.php';
require '../lib/providers/CeirGoClient.php';
require '../lib/OrderService.php';

$q=$conn->query("SELECT oid,provider_oid FROM pembelian_digital WHERE provider='ceirgo' AND status IN ('Pending','Processing') AND provider_oid<>'' ORDER BY id ASC LIMIT 100");
if(!$q||$q->num_rows===0)exit("Tidak ada order CEIRGo yang perlu disinkronkan.\n");
$p=$conn->prepare("SELECT api_key,link FROM provider WHERE code='ceirgo' LIMIT 1");$p->execute();$provider=$p->get_result()->fetch_assoc();$p->close();
if(!$provider||empty($provider['api_key']))exit("Provider CEIRGo belum dikonfigurasi.\n");
$client=new CeirGoClient((string)$provider['api_key'],trim((string)($provider['link']??''))?:'https://ceirgo.id');$orders=new OrderService($conn);
while($row=$q->fetch_assoc()){
  try{
    $r=$client->order((int)$row['provider_oid']);$status=(string)($r['status']??$r['data']['status']??'');$note=(string)($r['message']??$r['data']['message']??$r['data']['catatan']??'');
    if(in_array(strtolower($status),['success','completed','complete'],true)){$s=$conn->prepare("UPDATE pembelian_digital SET status='Success',keterangan=? WHERE oid=? AND status<>'Success'");$s->bind_param('ss',$note,$row['oid']);$s->execute();$s->close();$s=$conn->prepare("UPDATE provider_orders_v2 SET status='success',response_message=? WHERE local_order_id=?");$s->bind_param('ss',$note,$row['oid']);$s->execute();$s->close();}
    elseif(in_array(strtolower($status),['failed','error','cancelled','canceled','rejected'],true)){$orders->markFailed($row['oid'],$note?:'Order provider gagal.');}
    elseif($status!==''){$s=$conn->prepare("UPDATE pembelian_digital SET status='Processing',keterangan=? WHERE oid=?");$s->bind_param('ss',$note,$row['oid']);$s->execute();$s->close();}
    echo $row['oid'].' => '.($status?:'unknown')."\n";
  }catch(Throwable $e){error_log('CEIRGo status '.$row['oid'].': '.$e->getMessage());echo $row['oid'].' => ERROR: '.$e->getMessage()."\n";}
}
