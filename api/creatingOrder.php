<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../cart_functions.php';

$summary = getCartSummary();

if ($summary['itemCount'] === 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Cart is empty']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Determine next pickup number (same pickup number groups items of one order)
    $pickupQuery = $pdo->query('SELECT IFNULL(MAX(pickup_number), 0) + 1 AS next_num FROM orders');
    $pickupNumber = (int)$pickupQuery->fetchColumn();

    // You can adjust these to match your desired defaults
    $orderStatusId = 1; // e.g. 1 = PENDING in order_status table
    $dineChoice = 'takeaway'; // or 'dine-in' depending on your UI

    // Prepare insert: one row per product, all sharing same pickup_number
    $stmt = $pdo->prepare(
        'INSERT INTO orders (order_status_id, pickup_number, price, datetime, ordered_product, dineChoice, quantity)
         VALUES (:order_status_id, :pickup_number, :price, NOW(), :ordered_product, :dineChoice, :quantity)'
    );

    $lastOrderId = null;

    foreach ($summary['items'] as $item) {
        $qty       = (int)($item['quantity'] ?? 1);
        $unitPrice = (float)($item['price'] ?? 0);
        $linePrice = $unitPrice * $qty;
        $productId = $item['product_id'] ?? null;

        if ($productId === null) {
            // Skip items that don't have a product_id (they can't satisfy the FK)
            continue;
        }

        $stmt->execute([
            ':order_status_id' => $orderStatusId,
            ':pickup_number' => $pickupNumber,
            ':price' => $linePrice,
            ':ordered_product' => $productId, // FK to products.product_id
            ':dineChoice' => $dineChoice,
            ':quantity' => $qty,
        ]);

        $lastOrderId = (int)$pdo->lastInsertId();
    }

    $pdo->commit();

    // Optionally clear the cart after creating an order.
    $_SESSION['pickupNumber'] = $pickupNumber;
    $_SESSION['cart'] = [];

    echo json_encode([
        'success' => true,
        'orderId' => $lastOrderId,
        'pickupNumber' => $pickupNumber,
        'status' => 'PENDING',
    ]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to create order']);
}

