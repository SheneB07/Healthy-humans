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

if (!isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing name']);
    exit;
}

$name = (string)$data['name'];
$removeAll = isset($data['removeAll']) ? (bool)$data['removeAll'] : false;
$quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;

removeProductFromCartByName($name, $quantity, $removeAll);

$summary = getCartSummary();

echo json_encode([
    'success' => true,
    'cartItems' => $summary['items'],
    'itemCount' => $summary['itemCount'],
    'totalPrice' => $summary['totalPrice'],
]);

