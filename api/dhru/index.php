<?php
declare(strict_types=1);

/**
 * Public DHRU Fusion 6.1 compatible API.
 *
 * POST /api/dhru
 * POST /api/dhru/index.php
 * POST /api/index.php
 */
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../lib/BalanceService.php';
require_once __DIR__ . '/../../lib/providers/DhruServer.php';

$format = strtoupper(trim((string)($_POST['requestformat'] ?? 'JSON')));
header('Content-Type: ' . ($format === 'XML' ? 'application/xml' : 'application/json') . '; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');

try {
    $server = new DhruServer($conn);
    echo $server->handle($_POST);
} catch (Throwable $e) {
    http_response_code(400);
    if ($format === 'XML') {
        $xml = new SimpleXMLElement('<RESPONSE/>');
        $node = $xml->addChild('ERROR');
        $node->addChild('MESSAGE', htmlspecialchars($e->getMessage(), ENT_XML1));
        echo $xml->asXML();
    } else {
        echo json_encode(['ERROR' => [['MESSAGE' => $e->getMessage()]]], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
