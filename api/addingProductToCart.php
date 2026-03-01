<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../cart_functions.php';

// Read JSON body
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON body']);
    exit;
}

// Two usage modes:
// 1) Menu page sends full product object in "product"
// 2) Cart page sends only "name" to increment an existing item

if (isset($data['product']) && is_array($data['product'])) {
    $product = $data['product'];
    $quantity = isset($data['quantity']) ? (int)$data['quantity'] : (int)($product['quantity'] ?? 1);
    addProductToCart($product, $quantity);
} elseif (isset($data['name'])) {
    $name = (string)$data['name'];
    $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;

    $index = findCartItemIndexByName($name);
    if ($index === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Item not found in cart']);
        exit;
    }

    addProductToCart($_SESSION['cart'][$index], $quantity);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing product or name']);
    exit;
}

$summary = getCartSummary();

echo json_encode([
    'success' => true,
    'cartItems' => $summary['items'],
    'itemCount' => $summary['itemCount'],
    'totalPrice' => $summary['totalPrice'],
]);

