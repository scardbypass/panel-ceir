<?php
declare(strict_types=1);

/**
 * Public DHRU Fusion 6.1 compatible API.
 * Existing reseller panels can use this endpoint as their provider URL.
 */
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../lib/providers/DhruServer.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $server = new DhruServer($conn);
    echo $server->handle($_POST);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ERROR' => [['MESSAGE' => $e->getMessage()]]], JSON_UNESCAPED_UNICODE);
}
