<?php
session_start();
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

    // One-time dip recommendations after adding a side.
    $showDipRecs = !empty($_SESSION['hh_show_dip_recs']);
    $lastAddedName = isset($_SESSION['hh_last_added_name']) ? (string)$_SESSION['hh_last_added_name'] : '';
    if ($showDipRecs) {
        unset($_SESSION['hh_show_dip_recs'], $_SESSION['hh_last_added_name']);

        $stmtDips = $pdo->prepare("
            SELECT p.product_id, p.name, p.description, p.price, i.filename
            FROM products p
            LEFT JOIN images i ON p.image_id = i.image_id
            WHERE p.category_id = 5
            ORDER BY p.product_id ASC
            LIMIT 3
        ");
        $stmtDips->execute();
        $dipRecommendations = $stmtDips->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $dipRecommendations = [];
    }

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
            <img src="assets/img/<?= htmlspecialchars($product['filename']); ?>" id="product-image"
                 alt="<?= htmlspecialchars(t('product.description.' . (string)$product['product_id'], $product['description'])); ?>">
                
        <?php endif; ?>

        <h3><?= htmlspecialchars(t('product.name.' . (string)$product['product_id'], $product['name'])); ?></h3>
        
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
    </a>
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
        <h5><?= htmlspecialchars(t('menu.cancel.title', 'Cancel order?')); ?></h5>
        <p><?= htmlspecialchars(t('menu.cancel.confirm', 'Are you sure you want to cancel your order?')); ?></p>

        <div class="popup-buttons">
            <button id="closePopup"><?= htmlspecialchars(t('menu.cancel.no', 'No')); ?></button>
            <button id="confirmCancel"><?= htmlspecialchars(t('menu.cancel.yes', 'Yes, cancel')); ?></button>
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

<?php if (!empty($showDipRecs) && !empty($dipRecommendations)): ?>
<div id="dipRecsPopup" class="popup" style="display:flex;">
    <div class="popup-content dip-recs">
        <h5><?= htmlspecialchars(t('menu.dip_recs.title', 'Try a dip with that?')); ?></h5>
        <?php if (!empty($lastAddedName)): ?>
            <p><?= htmlspecialchars(t('menu.dip_recs.subtitle', 'Great with:')) ?> <strong><?= htmlspecialchars($lastAddedName); ?></strong></p>
        <?php else: ?>
            <p><?= htmlspecialchars(t('menu.dip_recs.subtitle_generic', 'These go great with your side:')); ?></p>
        <?php endif; ?>

        <div class="dip-recs-list">
            <?php foreach ($dipRecommendations as $dip): ?>
                <a class="dip-recs-item" href="cart_action.php?id=<?= (int)$dip['product_id']; ?>">
                    <?php if (!empty($dip['filename'])): ?>
                        <img src="assets/img/<?= htmlspecialchars($dip['filename']); ?>" alt="">
                    <?php endif; ?>
                    <div class="dip-recs-meta">
                        <div class="dip-recs-name"><?= htmlspecialchars(t('product.name.' . (string)$dip['product_id'], $dip['name'])); ?></div>
                        <div class="dip-recs-price">€ <?= htmlspecialchars($dip['price']); ?></div>
                    </div>
                    <img src="assets/img/add-icon.png" class="dip-recs-add" alt="">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="popup-buttons">
            <button id="closeDipRecs"><?= htmlspecialchars(t('menu.dip_recs.close', 'No thanks')); ?></button>
            <a id="goToCartFromRecs" href="cart.php" style="flex:1; text-decoration:none;">
                <button type="button" style="width:100%; height:55px; cursor:pointer; border-radius:12px; border:none; font-size:18px; font-weight:600; background:#8CD003; color:#333; transition:0.2s;">
                    <?= htmlspecialchars(t('menu.dip_recs.go_to_cart', 'Go to cart')); ?>
                </button>
            </a>
        </div>
    </div>
</div>

<script>
    (function () {
        const popup = document.getElementById('dipRecsPopup');
        const closeBtn = document.getElementById('closeDipRecs');
        if (!popup || !closeBtn) return;
        closeBtn.addEventListener('click', function () {
            popup.style.display = 'none';
        });
    })();
</script>
<?php endif; ?>

<script src="assets/js/fullscreen.js"></script>

</body>
</html>