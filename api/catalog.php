<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60');

$category = trim((string)($_GET['category'] ?? ''));
$search = trim((string)($_GET['search'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = min(100, max(1, (int)($_GET['limit'] ?? 30)));
$offset = ($page - 1) * $limit;

$where = ["status = 'Normal'", 'public_visible = 1'];
$params = [];
$types = '';
if ($category !== '') { $where[] = 'operator = ?'; $params[] = $category; $types .= 's'; }
if ($search !== '') { $where[] = '(layanan LIKE ? OR operator LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; $types .= 'ss'; }

$sql = 'SELECT id, service_id, operator, layanan, harga, harga_api, catatan, image_url FROM layanan_digital WHERE '.implode(' AND ', $where).' ORDER BY sort_order ASC, id ASC LIMIT ? OFFSET ?';
$params[] = $limit; $params[] = $offset; $types .= 'ii';
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    unset($row['harga_api']);
    $row['harga'] = (int)$row['harga'];
    $data[] = $row;
}
$stmt->close();

echo json_encode(['success'=>true,'data'=>$data,'page'=>$page,'limit'=>$limit], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
