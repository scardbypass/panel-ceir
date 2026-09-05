<?php
require '../config.php';
require '../lib/BalanceService.php';

$q=$conn->query("SELECT * FROM deposit WHERE status='Pending' ORDER BY id ASC LIMIT 100");
if(!$q||$q->num_rows===0)exit("Deposit pending tidak ditemukan.\n");
$p=$conn->prepare("SELECT api_key,secret_key FROM provider WHERE code='DPEDIA' LIMIT 1");$p->execute();$provider=$p->get_result()->fetch_assoc();$p->close();
if(!$provider||empty($provider['api_key'])||empty($provider['secret_key']))exit("Provider deposit belum dikonfigurasi.\n");
$wallet=new BalanceService($conn);
while($d=$q->fetch_assoc()){
  $id=(string)$d['kode_deposit'];
  try{
    $ch=curl_init('https://d-pedia.co.id/api/v1/deposit/status');curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>http_build_query(['key'=>$provider['api_key'],'secret'=>$provider['secret_key'],'id'=>$id]),CURLOPT_RETURNTRANSFER=>true,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2,CURLOPT_TIMEOUT=>20]);$raw=curl_exec($ch);$err=curl_error($ch);curl_close($ch);if($err)throw new RuntimeException($err);
    $r=json_decode((string)$raw,true);if(!is_array($r)||($r['status']??'')!=='ok')throw new RuntimeException((string)($r['message']??'Deposit belum ditemukan.'));
    $state=(string)($r['data']['status']??'pending');
    if($state==='confirmed'){
      $amount=(int)($r['data']['get_amount']??0);if($amount<=0)throw new RuntimeException('Nominal saldo deposit tidak valid.');
      $wallet->creditByUsername((string)$d['username'],$amount,'deposit:'.$id,'Penambahan Saldo. Deposit ID '.$id);
      $s=$conn->prepare("UPDATE deposit SET status='Success',get_saldo=? WHERE kode_deposit=? AND status='Pending'");$amountText=(string)$amount;$s->bind_param('ss',$amountText,$id);$s->execute();$s->close();echo "$id => Success Rp ".number_format($amount,0,',','.')."\n";
    }elseif($state==='expired'){$s=$conn->prepare("UPDATE deposit SET status='Error' WHERE kode_deposit=? AND status='Pending'");$s->bind_param('s',$id);$s->execute();$s->close();echo "$id => Expired\n";}
    else echo "$id => Pending\n";
  }catch(Throwable $e){error_log('Deposit '.$id.': '.$e->getMessage());echo "$id => ERROR: {$e->getMessage()}\n";}
}
