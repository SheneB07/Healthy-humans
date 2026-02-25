<?php
include("connection.php");

try {
    $stmt = $pdo->prepare("
        SELECT products.*, images.filename, images.description AS image_description
        FROM products
        LEFT JOIN images ON products.image_id = images.image_id
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="topbar-container">
        <div class="top-left">
            <img src="assets/img/logo_big_happy_herbivore_transparent.png" id="menu-logo">
        </div>
        <div class="top-right">
            <div id="menu-topbox">
                <h2>Breakfast</h2>
            </div>
        </div>
    </div>
    <div class="content-container">
        <div class="categories-box">
            <div id="option-button">
                <img src="assets/img/highlights.png" id="category-icon">
                <h1>Full menu</h1>
            </div>
            <div id="option-button">
                <img src="assets/img/breakfast-icon.png" id="category-icon">
                <h1>Breakfast</h1>
            </div>
            <div id="option-button">
                <img src="assets/img/salad-icon.png" id="category-icon">
                <h1>Lunch & Diner</h1>
            </div>
            <div id="option-button">
                <img src="assets/img/sandwich-icon.png" id="category-icon">
                <h1>Handhelds</h1>
            </div>
            <div id="option-button">
                <img src="assets/img/sides-icon.png" id="category-icon">
                <h1>Sides</h1>
            </div>
            <div id="option-button">
                <img src="assets/img/dips-icon.png" id="category-icon">
                <h1>Dips</h1>
            </div>
            <div id="option-button">
                <img src="assets/img/drink-icon.png" id="category-icon">
                <h1>Drinks</h1>
            </div>
        </div>

        
<div class="menu-items">
<?php foreach($products as $product): ?>
    <div class="menu-item">

        <?php if(!empty($product['filename'])): ?>
            <img src="assets/img/<?= htmlspecialchars($product['filename']); ?>" id="product-image"
                 alt="<?= htmlspecialchars($product['description']); ?>">
        <?php endif; ?>

        <h3><?= htmlspecialchars($product['name']); ?></h3>

    </div>
<?php endforeach; ?>
</div>
        
        </div>
    </div>
    <div class="bottombar-container">
        <div id="pink-bar"></div>
        <!-- <div id="cart-button">
            <img src="assets/img/cart.png" id="cart-image">
        </div> -->
    </div>

</body>
</html>