<?php

session_start();

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/**
 * Find the index of a cart item by its name.
 */
function findCartItemIndexByName(string $name): ?int
{
    foreach ($_SESSION['cart'] as $index => $item) {
        if (($item['name'] ?? null) === $name) {
            return $index;
        }
    }

    return null;
}

/**
 * Add a product to the cart or increment its quantity.
 *
 * Expects $product with at least: name, price, calories, image, description.
 * Optional: ingredients (array), quantity (int).
 */
function addProductToCart(array $product, int $quantity = 1): void
{
    if ($quantity < 1) {
        $quantity = 1;
    }

    $name = $product['name'] ?? null;
    if ($name === null) {
        return;
    }

    $index = findCartItemIndexByName($name);

    if ($index !== null) {
        $currentQty = (int)($_SESSION['cart'][$index]['quantity'] ?? 1);
        $_SESSION['cart'][$index]['quantity'] = $currentQty + $quantity;
    } else {
        $item = [
            'product_id'  => $product['product_id'] ?? null,
            'name'        => $product['name'],
            'category_id' => $product['category_id'] ?? null,
            'image_id'    => $product['image_id'] ?? null,
            'category'    => $product['category'] ?? null,
            'description' => $product['description'] ?? '',
            'price'       => (float)($product['price'] ?? 0),
            'calories'    => (int)($product['calories'] ?? 0),
            'quantity'    => (int)($product['quantity'] ?? $quantity),
            'image'       => $product['image'] ?? '',
            'ingredients' => $product['ingredients'] ?? [],
        ];

        $_SESSION['cart'][] = $item;
    }
}

/**
 * Decrement quantity of an item or remove it completely.
 *
 * If $removeAll is true, remove item regardless of quantity.
 * Otherwise, reduce quantity by $quantity and remove when it reaches 0.
 */
function removeProductFromCartByName(string $name, int $quantity = 1, bool $removeAll = false): void
{
    $index = findCartItemIndexByName($name);

    if ($index === null) {
        return;
    }

    if ($removeAll) {
        unset($_SESSION['cart'][$index]);
    } else {
        $currentQty = (int)($_SESSION['cart'][$index]['quantity'] ?? 1);
        $newQty = $currentQty - $quantity;

        if ($newQty <= 0) {
            unset($_SESSION['cart'][$index]);
        } else {
            $_SESSION['cart'][$index]['quantity'] = $newQty;
        }
    }

    // reindex array to keep JSON encoding tidy
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

/**
 * Get cart items and a simple summary (itemCount, totalPrice).
 */
function getCartSummary(): array
{
    $items = $_SESSION['cart'];

    $itemCount = 0;
    $totalPrice = 0.0;

    foreach ($items as $item) {
        $qty = (int)($item['quantity'] ?? 1);
        $price = (float)($item['price'] ?? 0);

        $itemCount += $qty;
        $totalPrice += $price * $qty;
    }

    return [
        'items' => $items,
        'itemCount' => $itemCount,
        'totalPrice' => $totalPrice,
    ];
}

