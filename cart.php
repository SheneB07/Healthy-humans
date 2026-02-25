<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$itemCount = count($cart);

// if (!isset($_SESSION['cart'])) {
//     $_SESSION['cart'] = [];
// }

// $_SESSION['cart'][] = [
//     'name' => 'Morning Boost Açaí Bowl',
//     'category' => 'Bowls',
//     'description' => 'Spiced chickpeas, shredded carrots, crisp lettuce, and signature hummus in a whole-wheat wrap.',
//     'price' => 6.00,
//     'calories' => 350,
//     'quantity' => 1,
//     'image' => 'assets/img/Gemini_Generated_Image_l0r92pl0r92pl0r9.webp',
//     'ingredients' => [
//         'Açaí',
//         'Banana',
//         'Granola',
//         'Honey'
//     ]
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
                <div class="cartItem">
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
                                <button id="removeButton" data-item="<?= $item['name'] ?>">-</button>
                                <p><?= $item['quantity'] ?></p>
                                <button id="addButton" data-item="<?= $item['name'] ?>">+</button>
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
                <p>Total: €<?= number_format(array_sum(array_column($cart, 'price')), 2) ?></p>
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
</body>

</html>