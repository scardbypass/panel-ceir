<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
require_once __DIR__ . '/../lib/OrderCatalog.php';

if (($data_user['level'] ?? '') !== 'Developers') {
    http_response_code(403);
    exit('Akses ditolak.');
}

function post_string(string $key, string $default = ''): string { return trim((string)($_POST[$key] ?? $default)); }
function clean_json_schema(string $raw): string {
    $items = json_decode($raw, true);
    if (!is_array($items)) return '[]';
    $clean = [];
    foreach ($items as $item) {
        if (!is_array($item)) continue;
        $name = trim((string)($item['name'] ?? ''));
        if ($name === '' || !preg_match('/^[A-Za-z][A-Za-z0-9_-]*$/', $name)) continue;
        $type = strtolower((string)($item['type'] ?? 'text'));
        if (!in_array($type, ['text','number','tel','email','textarea','select'], true)) $type = 'text';
        $options = [];
        if (isset($item['options']) && is_array($item['options'])) foreach ($item['options'] as $o) if (trim((string)$o) !== '') $options[] = trim((string)$o);
        $clean[] = [
            'name' => $name,
            'label' => trim((string)($item['label'] ?? $name)),
            'type' => $type,
            'required' => !empty($item['required']),
            'placeholder' => trim((string)($item['placeholder'] ?? '')),
            'min' => isset($item['min']) && $item['min'] !== '' ? (int)$item['min'] : null,
            'max' => isset($item['max']) && $item['max'] !== '' ? (int)$item['max'] : null,
            'options' => $options,
        ];
    }
    return json_encode($clean, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = post_string('service_id');
    $action = post_string('action');
    if ($serviceId === '') {
        $_SESSION['hasil'] = ['alert'=>'danger','judul'=>'Gagal','pesan'=>'Produk tidak valid.'];
    } else {
        if ($action === 'save') {
            $label = post_string('label');
            $icon = post_string('icon', 'mdi mdi-cart-outline');
            $group = post_string('group_name', 'Menu Utama');
            $visible = isset($_POST['is_visible']) ? 1 : 0;
            $sort = max(0, (int)($_POST['sort_order'] ?? 0));
            $schema = clean_json_schema(post_string('order_form_json', '[]'));
            $image = post_string('image_url');
            $stmt = $conn->prepare("UPDATE layanan_digital SET order_form_json=?,menu_label=?,menu_icon=?,image_url=? WHERE provider_id=?");
            $stmt->bind_param('sssss', $schema, $label, $icon, $image, $serviceId); $stmt->execute(); $stmt->close();
            $stmt = $conn->prepare("INSERT INTO order_menu (service_id,label,icon,group_name,sort_order,is_visible) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE label=VALUES(label),icon=VALUES(icon),group_name=VALUES(group_name),sort_order=VALUES(sort_order),is_visible=VALUES(is_visible)");
            $stmt->bind_param('ssssii', $serviceId, $label, $icon, $group, $sort, $visible); $stmt->execute(); $stmt->close();
            $_SESSION['hasil'] = ['alert'=>'success','judul'=>'Produk disimpan','pesan'=>'Menu dan form order produk berhasil diperbarui.'];
        } elseif ($action === 'toggle') {
            $visible = isset($_POST['visible']) && $_POST['visible'] === '1' ? 1 : 0;
            $stmt = $conn->prepare("UPDATE order_menu SET is_visible=? WHERE service_id=?"); $stmt->bind_param('is',$visible,$serviceId); $stmt->execute(); $stmt->close();
            if ($stmt) {}
            $_SESSION['hasil'] = ['alert'=>'success','judul'=>'Menu diperbarui','pesan'=>$visible?'Produk ditampilkan di menu.':'Produk disembunyikan dari menu.'];
        }
    }
    header('Location: order-menu.php?service='.rawurlencode($serviceId)); exit;
}

$serviceId = post_string('service_id', (string)($_GET['service'] ?? ''));
$selected = $serviceId !== '' ? OrderCatalog::service($conn, $serviceId) : null;
$selectedSchema = $selected ? OrderCatalog::schema((string)($selected['order_form_json'] ?? '')) : [];
if ($selected && !$selectedSchema) $selectedSchema = OrderCatalog::defaultsForProduct((string)$selected['layanan'], (string)$selected['tipe']);
$menuMap = [];
$qm = $conn->query("SELECT * FROM order_menu");
if ($qm) while ($m=$qm->fetch_assoc()) $menuMap[$m['service_id']]=$m;
$q = $conn->query("SELECT provider_id,layanan,operator,harga,harga_api,status,provider,COALESCE(public_visible,1) public_visible,COALESCE(sort_order,0) sort_order,menu_label,menu_icon FROM layanan_digital ORDER BY operator ASC,sort_order ASC,layanan ASC");
$products=[]; if($q) while($r=$q->fetch_assoc()) $products[]=$r;
require_once __DIR__ . '/../lib/header_admin.php';
?>
<style>
.v3-wrap{max-width:1280px;margin:22px auto}.v3-hero{padding:28px;border-radius:24px;background:linear-gradient(135deg,#111827,#334155);color:#fff;box-shadow:0 18px 50px rgba(15,23,42,.18);margin-bottom:20px}.v3-hero h2{font-weight:800;margin:0 0 6px}.v3-hero p{margin:0;opacity:.78}.v3-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:18px}.v3-card{background:#fff;border:1px solid #e7eaf0;border-radius:20px;box-shadow:0 10px 35px rgba(15,23,42,.07);overflow:hidden}.v3-card-body{padding:20px}.v3-products{max-height:690px;overflow:auto}.v3-item{display:flex;gap:12px;align-items:center;padding:14px 16px;border-bottom:1px solid #eef0f4;text-decoration:none;color:#1f2937}.v3-item:hover{background:#f8fafc;text-decoration:none}.v3-item.active{background:#eff6ff}.v3-icon{width:42px;height:42px;border-radius:13px;background:#f1f5f9;display:grid;place-items:center;font-size:20px}.v3-name{flex:1;min-width:0}.v3-name strong{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.v3-meta{font-size:12px;color:#64748b}.v3-badge{font-size:11px;padding:5px 8px;border-radius:999px}.v3-on{background:#dcfce7;color:#166534}.v3-off{background:#fee2e2;color:#991b1b}.schema-row{border:1px solid #e5e7eb;border-radius:15px;padding:14px;margin-bottom:10px;background:#fafafa}.schema-grid{display:grid;grid-template-columns:1fr 1fr 110px 90px;gap:8px}.schema-options{margin-top:8px}.schema-grid input,.schema-grid select{height:40px;border-radius:10px}.v3-actions{display:flex;gap:8px;flex-wrap:wrap}@media(max-width:900px){.v3-grid{grid-template-columns:1fr}.v3-products{max-height:400px}.schema-grid{grid-template-columns:1fr 1fr}.schema-grid .wide{grid-column:1/-1}}
</style>
<div class="v3-wrap">
  <div class="v3-hero"><h2><i class="mdi mdi-view-dashboard-outline"></i> Order Catalog & Menu Builder</h2><p>Atur produk yang tampil di sidebar/member, urutan menu, nama menu, icon, dan form order per produk. Konsepnya seperti service catalog DHRU Fusion.</p></div>
  <div class="v3-grid">
    <div class="v3-card"><div class="v3-card-body"><div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="mb-1">Produk Digital</h4><small>Pilih produk untuk dikonfigurasi.</small></div><a href="layanan-digital" class="btn btn-light">Kelola Produk</a></div><div class="v3-products">
      <?php foreach($products as $p): $m=$menuMap[$p['provider_id']]??[]; $on=(int)($m['is_visible']??0)===1; ?>
      <a class="v3-item <?= $serviceId===$p['provider_id']?'active':'' ?>" href="?service=<?=rawurlencode($p['provider_id'])?>"><span class="v3-icon"><i class="<?=htmlspecialchars($p['menu_icon']?:'mdi mdi-cart-outline')?>"></i></span><span class="v3-name"><strong><?=htmlspecialchars($p['menu_label']?:$p['layanan'])?></strong><span class="v3-meta">ID <?=htmlspecialchars($p['provider_id'])?> · <?=htmlspecialchars($p['provider'])?></span></span><span class="v3-badge <?= $on?'v3-on':'v3-off' ?>"><?= $on?'MENU ON':'HIDDEN' ?></span></a>
      <?php endforeach; ?>
    </div></div></div>
    <div class="v3-card"><div class="v3-card-body">
      <?php if(!$selected): ?><div class="text-center py-5"><i class="mdi mdi-cursor-default-click-outline" style="font-size:48px"></i><h4 class="mt-3">Pilih produk</h4><p class="text-muted">Klik produk di sebelah kiri untuk mengatur menu dan form order.</p></div>
      <?php else: $m=$menuMap[$serviceId]??[]; ?>
      <h4 class="mb-1"><?=htmlspecialchars($selected['layanan'])?></h4><small class="text-muted">Service ID: <?=htmlspecialchars($selected['provider_id'])?> · Provider: <?=htmlspecialchars($selected['provider'])?></small><hr>
      <form method="post" id="catalogForm"><input type="hidden" name="action" value="save"><input type="hidden" name="service_id" value="<?=htmlspecialchars($selected['provider_id'])?>"><input type="hidden" name="order_form_json" id="order_form_json">
        <div class="form-group"><label>Nama di Menu</label><input class="form-control" name="label" value="<?=htmlspecialchars($m['label']??$selected['menu_label']??$selected['layanan'])?>" required></div>
        <div class="row"><div class="col-md-6"><div class="form-group"><label>Icon</label><input class="form-control" name="icon" value="<?=htmlspecialchars($m['icon']??$selected['menu_icon']??'mdi mdi-cart-outline')?>"><small class="text-muted">Contoh: mdi mdi-cellphone</small></div></div><div class="col-md-6"><div class="form-group"><label>Grup Menu</label><input class="form-control" name="group_name" value="<?=htmlspecialchars($m['group_name']??'Menu Utama')?>"></div></div></div>
        <div class="row"><div class="col-md-6"><div class="form-group"><label>Urutan</label><input type="number" min="0" class="form-control" name="sort_order" value="<?=htmlspecialchars((string)($m['sort_order']??$selected['sort_order']??0))?>"></div></div><div class="col-md-6"><div class="form-group"><label>URL otomatis</label><input class="form-control" value="/pemesanan/order?service=<?=htmlspecialchars($selected['provider_id'])?>" readonly></div></div></div>
        <div class="custom-control custom-switch mb-3"><input type="checkbox" class="custom-control-input" id="is_visible" name="is_visible" <?=!isset($m['is_visible'])||$m['is_visible']?'checked':''?>><label class="custom-control-label" for="is_visible">Tampilkan produk di menu member</label></div>
        <div class="d-flex justify-content-between align-items-center mb-2"><div><h5 class="mb-0">Form Order</h5><small class="text-muted">Field otomatis tampil saat produk dipilih. Untuk IMEI cukup satu field IMEI.</small></div><button type="button" class="btn btn-sm btn-info" id="addField"><i class="mdi mdi-plus"></i> Tambah Field</button></div>
        <div id="schemaBuilder"></div>
        <button class="btn btn-primary btn-block mt-3" type="submit"><i class="mdi mdi-content-save"></i> Simpan Produk & Menu</button>
      </form>
      <?php endif; ?>
    </div></div>
  </div>
</div>
<?php if($selected): ?><script>
const initialSchema=<?=json_encode($selectedSchema,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?>;const box=document.getElementById('schemaBuilder');
function esc(v){return String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
function row(f={},i=0){return `<div class="schema-row" data-index="${i}"><div class="schema-grid"><input class="form-control f-name" placeholder="name" value="${esc(f.name)}"><input class="form-control f-label" placeholder="Label" value="${esc(f.label)}"><select class="form-control f-type"><option value="text">Text</option><option value="tel">IMEI/Tel</option><option value="number">Number</option><option value="email">Email</option><option value="textarea">Textarea</option><option value="select">Select</option></select><label class="custom-control custom-checkbox pt-2"><input type="checkbox" class="custom-control-input f-required" ${f.required?'checked':''}><span class="custom-control-label">Wajib</span></label></div><div class="schema-grid mt-2"><input class="form-control f-placeholder wide" placeholder="Placeholder" value="${esc(f.placeholder)}"><input type="number" class="form-control f-min" placeholder="Min" value="${f.min??''}"><input type="number" class="form-control f-max" placeholder="Max" value="${f.max??''}"><button type="button" class="btn btn-outline-danger remove-field">Hapus</button></div><input class="form-control f-options schema-options" placeholder="Pilihan select, pisahkan dengan koma" value="${esc((f.options||[]).join(', '))}" ${f.type==='select'?'':'style="display:none"'}></div>`}
function render(){box.innerHTML=initialSchema.map((f,i)=>row(f,i)).join('');box.querySelectorAll('.schema-row').forEach(r=>{const f=initialSchema[+r.dataset.index]||{};r.querySelector('.f-type').value=f.type||'text';});}
function collect(){return [...box.querySelectorAll('.schema-row')].map(r=>({name:r.querySelector('.f-name').value.trim(),label:r.querySelector('.f-label').value.trim(),type:r.querySelector('.f-type').value,required:r.querySelector('.f-required').checked,placeholder:r.querySelector('.f-placeholder').value.trim(),min:r.querySelector('.f-min').value===''?null:Number(r.querySelector('.f-min').value),max:r.querySelector('.f-max').value===''?null:Number(r.querySelector('.f-max').value),options:r.querySelector('.f-options').value.split(',').map(x=>x.trim()).filter(Boolean)})).filter(x=>x.name);}
document.getElementById('addField').onclick=()=>{initialSchema.push({name:'field_'+(initialSchema.length+1),label:'Field Baru',type:'text',required:true,placeholder:'',min:null,max:null,options:[]});render();};box.addEventListener('click',e=>{if(e.target.closest('.remove-field')){e.target.closest('.schema-row').remove();}});box.addEventListener('change',e=>{if(e.target.classList.contains('f-type'))e.target.closest('.schema-row').querySelector('.f-options').style.display=e.target.value==='select'?'block':'none';});document.getElementById('catalogForm').addEventListener('submit',()=>document.getElementById('order_form_json').value=JSON.stringify(collect()));render();
</script><?php endif; ?>
<?php require_once __DIR__ . '/../lib/footer_admin.php'; ?>
