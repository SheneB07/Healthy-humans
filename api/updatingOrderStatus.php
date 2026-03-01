<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';

// Read JSON body
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON body']);
    exit;
}

if (!isset($data['order_id'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing order_id or status']);
    exit;
}

$orderId = (int)$data['order_id'];
$status = (string)$data['status'];

// Whitelist allowed statuses to keep things simple and safe.
$allowedStatuses = ['PENDING', 'IN_PROGRESS', 'READY', 'COMPLETED', 'CANCELLED'];

if (!in_array($status, $allowedStatuses, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid status']);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
    $stmt->execute([
        ':status' => $status,
        ':id' => $orderId,
    ]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Order not found']);
        exit;
    }

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update order status']);
}

