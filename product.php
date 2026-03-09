<?php
include("connection.php");
include("cart_functions.php");
require_once 'lang.php';

if (!isset($_GET['id'])) {
    die("Product not found.");
}

$product_id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("
    SELECT products.*, products.diet_type, images.filename, images.description AS image_description
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

    $translatedName = t('product.name.' . (string)$product['product_id'], $product['name']);
    $translatedDescription = t('product.description.' . (string)$product['product_id'], $product['description']);

    $productForJs = [
        'product_id'  => (int)($product['product_id'] ?? 0),
        'name'        => $translatedName ?? '',
        'description' => $translatedDescription ?? '',
        'price'       => isset($product['price']) ? (float)$product['price'] : 0.0,
        'calories'    => isset($product['kcal']) ? (int)$product['kcal'] : 0,
        'image'       => !empty($product['filename'])
            ? 'assets/img/' . $product['filename']
            : '',
        'category_id' => $product['category_id'] ?? null,
        'image_id'    => $product['image_id'] ?? null,
    ];

    $dietIcons = [
    'vegan' => 'vegan-icon.png',
    'vegetarian' => 'vegetarian-icon.PNG'
];

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<?php
// Handle Add To Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Use database product data (secure)
    $cartProduct = [
        'product_id'  => $product['product_id'],
        'name'        => $translatedName,
        'price'       => $product['price'],
        'calories'    => $product['kcal'],
        'image'       => $product['filename'],
        'description' => $translatedDescription,
    ];

    addProductToCart($cartProduct, $quantity);

    // Redirect to cart page
    header("Location: cart.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="<?= htmlspecialchars(getCurrentLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($translatedName); ?></title>
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

    <h1><?= htmlspecialchars($translatedName); ?></h1>

    <?php if (!empty($product['diet_type']) && isset($dietIcons[$product['diet_type']])): ?>
    <div class="diet-type">
        <img src="assets/img/<?= $dietIcons[$product['diet_type']] ?>"
             alt="<?= htmlspecialchars($product['diet_type']) ?>"
             class="diet-icon">
        <span><?= htmlspecialchars(ucfirst($product['diet_type'])) ?></span>
    </div>
<?php endif; ?>

    <p><?= htmlspecialchars($translatedDescription); ?></p>
    <p>(<?= htmlspecialchars($product['kcal']); ?> <?= htmlspecialchars(t('product.kcal_suffix', 'kcal')); ?>)</p>

    <h2>€ <?= htmlspecialchars($product['price']); ?></h2>
    
    </div>
    <div class="buttons-container">
    <div id="back-button">
    <a href="menu.php"><?= htmlspecialchars(t('product.back_to_menu', 'Back to menu')); ?></a>
    </div>

    <br><br>
    <form method="POST" class="cart-form">
    <button type="submit" name="add_to_cart" class="add-button">
        <?= htmlspecialchars(t('product.add_to_cart', 'Add to Cart')); ?>
    </button>

</form>
    
    </div>
    </div>

</div>

<script>
    (function () {
        const productData = <?= json_encode($productForJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const addButton = document.getElementById('add-to-cart-button');

        if (!addButton || !productData) {
            return;
        }

        addButton.addEventListener('click', function () {
            fetch('api/addingProductToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product: productData,
                    quantity: 1
                })
            })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function (data) {
                if (!data || !data.success) {
                    alert(data && data.error ? data.error : 'Failed to add to cart. Please try again.');
                    return;
                }

                window.location.href = 'cart.php';
            })
            .catch(function () {
                alert('Failed to add to cart. Please try again.');
            });
        });
    })();
</script>

</body>
</html>