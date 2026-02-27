<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$itemCount = count($cart);

// // TEMP: seed cart with one Iced Matcha Latte for testing
// $_SESSION['cart'][] = [
//     'product_id'  => 26,                 // FK to products.product_id
//     'category_id' => 6,
//     'image_id'    => 26,
//     'name'        => 'Iced Matcha Latte',
//     'category'    => 'Drinks',          // or whatever label you use
//     'description' => 'Lightly sweetened matcha green tea with almond milk.',
//     'price'       => 3.00,
//     'calories'    => 90,                // from kcal
//     'quantity'    => 1,
//     'image'       => 'assets/img/your_matcha_image.webp', // adjust path if needed
//     'ingredients' => [],                // or fill in if you want
// ]; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1080, height=1920, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <title>Cart</title>
</head>

<body>
    <main class="cartPage">
        <img id="cartLogo" src="assets/img/logo_big_happy_herbivore_transparent.png" alt="cart logo">
        <h1>Your Order</h1>
        <?php if ($itemCount > 0): ?>
            <div id="cartItems" class="<?= $itemCount === 1 ? 'single-item' : 'multiple-items' ?>">
            <?php foreach ($cart as $item) { ?>
                <div class="cartItem" data-name="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>">
                    <div class="itemInfo">
                        <div class="itemName">
                            <img src="<?= $item['image'] ?>" alt="cart item image">
                            <p><?= $item['quantity'] ?>X <?= $item['name'] ?></p>
                        </div>
                        <div class="itemCaloriesAndPrice">
                            <p><?= $item['calories'] ?> Cal</p>
                            <p>€<?= number_format($item['price'], 2) ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="editingCartItem">
                        <div class="leftBottom">
                            <!-- <p class="ingredientList">
                                <?= implode(', ', $item['ingredients']) ?>
                            </p> -->
                            <p class="ingredientList"><?=$item['description'] ?>
                            <div class="addingFunction">
                                <button class="removeButton" data-item="<?= $item['name'] ?>">-</button>
                                <p class="itemQuantity"><?= $item['quantity'] ?></p>
                                <button class="addButton" data-item="<?= $item['name'] ?>">+</button>
                            </div>
                        </div>
                        <div class="editAndDeleteFunction">
                            <img src="assets/img/editIcon.png" class="editIcon" alt="Edit Item">
                            <img src="assets/img/trashIcon.png" class="trashIcon" alt="Remove Item">
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div id="cartTotal" class="center-total">
                <p>Total: €<span id="cartTotalAmount"><?= number_format(
                    array_sum(array_map(
                        fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                        $cart
                    )),
                    2
                ) ?></span></p>
            </div>

        <?php else: ?>
            <div id="cartItems" class="empty-cart">
                <p class="emptyMessage">Your cart is empty.</p>
            </div>
        <?php endif; ?>

        <div id="bottomButtons">
            <button id="changeOrderButton">
                <img src="assets/img/changeOrderImg.png">
                <p>Change Order</p>
            </button>

            <button id="checkoutButton">
                <img src="assets/img/checkoutImg.png">
                <p>Checkout</p>
            </button>
        </div>
    </main>

    <script src="assets/js/cart.js"></script>

</body>

</html>