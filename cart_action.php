<?php
session_start();
require_once 'connection.php';
require_once 'cart_functions.php';

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
}

header("Location: menu.php");
exit;