<?php
session_start();
require_once 'connection.php';
require_once 'cart_functions.php';
require_once 'lang.php';

if (!isset($_GET['id'])) {
    header("Location: menu.php");
    exit;
}

$product_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT p.*, p.kcal AS calories, i.filename AS image
    FROM products p
    LEFT JOIN images i ON p.image_id = i.image_id
    WHERE p.product_id = :id
");

$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    addProductToCart($product);

    $categoryId = (int)($product['category_id'] ?? 0);
    if ($categoryId === 3) {
        $_SESSION['hh_show_dip_recs'] = true;
        $id = (string)($product['product_id'] ?? '');
        $fallbackName = (string)($product['name'] ?? '');
        $_SESSION['hh_last_added_name'] = t('product.name.' . $id, $fallbackName);
    }
}

$redirect = 'menu.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $ref = parse_url($_SERVER['HTTP_REFERER']);
    $path = $ref['path'] ?? '';
    $query = isset($ref['query']) ? ('?' . $ref['query']) : '';

    if ($path && str_ends_with(strtolower($path), 'menu.php')) {
        $redirect = 'menu.php' . $query;
    }
}

header("Location: " . $redirect);
exit;