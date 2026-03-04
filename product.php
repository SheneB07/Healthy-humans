<?php
include("connection.php");

if (!isset($_GET['id'])) {
    die("Product not found.");
}

$product_id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("
        SELECT products.*, images.filename, images.description AS image_description
        FROM products
        LEFT JOIN images ON products.image_id = images.image_id
        WHERE products.product_id = :id
    ");
    
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body>
 
<div class="product-detail-container">
   <div class="logo-container">
     <img src="assets/img/logo_big_happy_herbivore_transparent.png" id="logo-image">
    </div>
       
    <div id="info-box">
        <div class="info-container">
    <?php if(!empty($product['filename'])): ?>
        <img src="assets/img/<?= htmlspecialchars($product['filename']); ?>" 
             alt="<?= htmlspecialchars($product['image_description']); ?>"
             class="product-detail-image">
    <?php endif; ?>

    <h1><?= htmlspecialchars($product['name']); ?></h1>

    <p><?= htmlspecialchars($product['description']); ?></p>
    <p>(<?= htmlspecialchars($product['kcal']); ?> kcal)</p>

    <h2>€ <?= htmlspecialchars($product['price']); ?></h2>
    
    </div>
    <div class="buttons-container">
    <div id="back-button">
    <a href="menu.php">Back to menu</a>
    </div>

    <br><br>
    <button>Add to Cart</button>
    
    </div>
    </div>

</div>

</body>
</html>