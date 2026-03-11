<?php
include("connection.php");
require_once 'lang.php';

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

    $header_title = t('menu.category.full_menu', 'Full Menu'); 

if ($category_id) {
    $stmtCategory = $pdo->prepare("
        SELECT name 
        FROM categories 
        WHERE category_id = :category_id
    ");

    $stmtCategory->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmtCategory->execute();

    $category = $stmtCategory->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $header_title = $category['name'];
    }
}

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(getCurrentLanguage()); ?>">
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
                <h2><?= htmlspecialchars($header_title); ?></h2>
            </div>
        </div>
    </div>
    <div class="content-container">
        <div class="categories-box">

    <a href="menu.php" class="option-button">
        <img src="assets/img/highlights.png" id="category-icon">
        <h1><?= htmlspecialchars(t('menu.category.full_menu', 'Full menu')); ?></h1>
    </a>

    <a href="menu.php?category=1" class="option-button">
        <img src="assets/img/breakfast-icon.png" id="category-icon">
        <h1><?= htmlspecialchars(t('menu.category.breakfast', 'Breakfast')); ?></h1>
    </a>

    <a href="menu.php?category=2" class="option-button">
        <img src="assets/img/salad-icon.png" id="category-icon">
        <h1><?= htmlspecialchars(t('menu.category.lunch_dinner', 'Lunch & Dinner')); ?></h1>
    </a>

    <a href="menu.php?category=4" class="option-button">
        <img src="assets/img/sandwich-icon.png" id="category-icon">
        <h1><?= htmlspecialchars(t('menu.category.handhelds', 'Handhelds')); ?></h1>
    </a>

    <a href="menu.php?category=3" class="option-button">
        <img src="assets/img/sides-icon.png" id="category-icon">
        <h1><?= htmlspecialchars(t('menu.category.sides', 'Sides')); ?></h1>
    </a>

    <a href="menu.php?category=5" class="option-button">
        <img src="assets/img/dips-icon.png" id="category-icon">
        <h1><?= htmlspecialchars(t('menu.category.dips', 'Dips')); ?></h1>
    </a>

    <a href="menu.php?category=6" class="option-button">
        <img src="assets/img/drink-icon.png" id="category-icon">
        <h1><?= htmlspecialchars(t('menu.category.drinks', 'Drinks')); ?></h1>
    </a>


        </div>

        
<div class="menu-items">
<?php foreach($products as $product): ?>

<div class="menu-item">

<a href="product.php?id=<?= $product['product_id']; ?>" class="menu-link">

<?php if(!empty($product['filename'])): ?>
<img src="assets/img/<?= htmlspecialchars($product['filename']); ?>"
     class="product-image"
     alt="<?= htmlspecialchars(t('product.description.' . (string)$product['product_id'], $product['description'])); ?>">
<?php endif; ?>

<h3><?= htmlspecialchars(t('product.name.' . (string)$product['product_id'], $product['name'])); ?></h3>

</a>

<div class="productinfo-container">
<div class="left-row">
<h4>€ <?= htmlspecialchars($product['price']); ?></h4>
</div>

<div class="right-row">
<a href="cart_action.php?id=<?= $product['product_id']; ?>">
<img src="assets/img/add-icon.png" class="add-button">
</a>
</div>
</div>

</div>

<?php endforeach; ?>
</div>
        
        </div>
    </div>
    <div class="bottombar-container">
        <div id="pink-bar"></div>
        <div class="button-row">
        <a href="index.php" id="cancel-button">
            <img src="assets/img/home-icon.png" id="cancel-image">
        </a>
        
        <a href="cart.php" id="cart-button">
            <img src="assets/img/cart.png" id="cart-image">
        </a>
        </div>
        </div>
    </div>

    <div id="cancelPopup" class="popup">
    <div class="popup-content">
        <h5>Cancel Order?</h5>
        <p>Are you sure you want to cancel your order?</p>

        <div class="popup-buttons">
            <button id="closePopup">No</button>
            <button id="confirmCancel">Yes, Cancel</button>
        </div>
    </div>

    </div>



<script>

const cancelButton = document.getElementById("cancel-button");
const popup = document.getElementById("cancelPopup");
const closePopup = document.getElementById("closePopup");
const confirmCancel = document.getElementById("confirmCancel");

cancelButton.addEventListener("click", function(e) {
    e.preventDefault(); 
    popup.style.display = "flex";
});

closePopup.addEventListener("click", function() {
    popup.style.display = "none";
});

confirmCancel.addEventListener("click", function() {
    window.location.href = "index.php";
});

</script>

</body>
</html>