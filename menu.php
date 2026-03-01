<?php
include("connection.php");

try {

    // Default: no category filter
    $category_id = $_GET['category'] ?? null;

    if ($category_id) {
        $stmt = $pdo->prepare("
            SELECT products.*, images.filename, images.description AS image_description
            FROM products
            LEFT JOIN images ON products.image_id = images.image_id
            WHERE products.category_id = :category_id
        ");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    } else {
        $stmt = $pdo->prepare("
            SELECT products.*, images.filename, images.description AS image_description
            FROM products
            LEFT JOIN images ON products.image_id = images.image_id
        ");
    }

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
    <link rel="stylesheet" href="assets/css/menu.css">
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

    <a href="menu.php" class="option-button">
        <img src="assets/img/highlights.png" id="category-icon">
        <h1>Full menu</h1>
    </a>

    <a href="menu.php?category=1" class="option-button">
        <img src="assets/img/breakfast-icon.png" id="category-icon">
        <h1>Breakfast</h1>
    </a>

    <a href="menu.php?category=2" class="option-button">
        <img src="assets/img/salad-icon.png" id="category-icon">
        <h1>Lunch & Dinner</h1>
    </a>

    <a href="menu.php?category=3" class="option-button">
        <img src="assets/img/sandwich-icon.png" id="category-icon">
        <h1>Handhelds</h1>
    </a>

    <a href="menu.php?category=4" class="option-button">
        <img src="assets/img/sides-icon.png" id="category-icon">
        <h1>Sides</h1>
    </a>

    <a href="menu.php?category=5" class="option-button">
        <img src="assets/img/dips-icon.png" id="category-icon">
        <h1>Dips</h1>
    </a>

    <a href="menu.php?category=6" class="option-button">
        <img src="assets/img/drink-icon.png" id="category-icon">
        <h1>Drinks</h1>
    </a>


        </div>

        
<div class="menu-items">
<?php foreach($products as $product): ?>
    <div class="menu-item">

        <?php if(!empty($product['filename'])): ?>
            <img src="assets/img/<?= htmlspecialchars($product['filename']); ?>" id="product-image"
                 alt="<?= htmlspecialchars($product['description']); ?>">
                 
        <?php endif; ?>

        <h3><?= htmlspecialchars($product['name']); ?></h3>
        
    <div class="productinfo-container">
        <div class="left-row">
        
        <h4>€ <?= htmlspecialchars($product['price']); ?></h4>
        </div>
        <div class="right-row">
        <img src="assets/img/add-icon.png" id="add-button">
        </div>
    </div>
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