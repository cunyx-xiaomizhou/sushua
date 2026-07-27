<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/bootstrap.php';

use XiaoMiSlop\Core\Config;
use XiaoMiSlop\Services\OrderService;

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This script must be run from CLI.\n");
    exit(2);
}
if (!Config::isInstalled()) {
    fwrite(STDERR, "The application is not installed.\n");
    exit(3);
}

try {
    $result = (new OrderService())->syncPendingOrders();
    fwrite(STDOUT, json_encode(['success' => true, 'task' => 'sync_orders', 'result' => $result, 'time' => date(DATE_ATOM)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
} catch (Throwable $e) {
    fwrite(STDERR, json_encode(['success' => false, 'task' => 'sync_orders', 'message' => $e->getMessage(), 'time' => date(DATE_ATOM)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
    exit(1);
}
