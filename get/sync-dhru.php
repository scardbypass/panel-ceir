<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/providers/DhruClient.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
header('Content-Type: text/plain; charset=utf-8');
$provider=$conn->query("SELECT * FROM provider WHERE UPPER(code)='DHRU' AND status='active' LIMIT 1")->fetch_assoc();
if(!$provider)exit("DHRU provider belum dikonfigurasi atau sedang disabled.\n");
try{
 $client=new DhruClient((string)$provider['link'],(string)$provider['api_id'],(string)$provider['api_key']);$response=$client->getProducts();$groups=$response['SUCCESS'][0]['LIST']??$response['SUCCESS'][0]['IMEIServiceList']??[];$count=0;$new=0;
 foreach($groups as $group){$services=$group['SERVICES']??$group['services']??(isset($group['SERVICEID'])?[$group]:[]);$operator=trim((string)($group['GROUPNAME']??$group['groupname']??'DHRU'))?:'DHRU';foreach($services as $service){$sid=trim((string)($service['SERVICEID']??$service['serviceid']??''));$name=trim((string)($service['SERVICENAME']??$service['servicename']??''));if($sid===''||$name==='')continue;$price=max(0,(float)($service['CREDIT']??$service['credit']??0));$requires=json_encode($service['Requires']??$service['requires']??[],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);$s=$conn->prepare("SELECT id FROM layanan_digital WHERE provider='DHRU' AND provider_id=? LIMIT 1");$s->bind_param('s',$sid);$s->execute();$exists=$s->get_result()->fetch_assoc();$s->close();if($exists){$s=$conn->prepare("UPDATE layanan_digital SET layanan=?,operator=?,dhru_group=?,harga_api=?,requires_json=?,cost_updated_at=NOW(),status='Normal',updated_at=NOW() WHERE id=?");$s->bind_param('sssdsi',$name,$operator,$operator,$price,$requires,$exists['id']);}else{$sell=$price;$profit=0;$type='Digital';$note='';$status='Normal';$prov='DHRU';$s=$conn->prepare("INSERT INTO layanan_digital (service_id,provider_id,operator,dhru_group,layanan,harga,harga_api,profit,status,provider,tipe,catatan,public_visible,sort_order,requires_json,cost_updated_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?, 'Digital',?,?,0,0,?,NOW(),NOW())");$s->bind_param('ssssdddssss',$sid,$sid,$operator,$operator,$name,$sell,$price,$profit,$status,$prov,$note,$requires);$new++;}$s->execute();$s->close();$count++;}}
 echo "Sync DHRU selesai. $count layanan diproses, $new produk baru. Harga jual produk lama dipertahankan. Produk baru default HIDDEN.\n";
}catch(Throwable $e){http_response_code(500);echo "Sync gagal: {$e->getMessage()}\n";}
