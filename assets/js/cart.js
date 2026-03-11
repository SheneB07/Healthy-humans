(function () {
    const cartItemsContainer = document.getElementById('cartItems');
    const totalAmountEl = document.getElementById('cartTotalAmount');
    const changeOrderButton = document.getElementById('changeOrderButton');
    const checkoutButton = document.getElementById('checkoutButton');
    const totalCaloriesEl = document.getElementById('cartTotalCalories');

    const popupEl = document.getElementById('cartPopup');
    const popupMessageEl = document.getElementById('cartPopupMessage');
    const popupCloseBtn = document.getElementById('cartPopupClose');
    const popupBackdrop = popupEl ? popupEl.querySelector('.cart-popup-backdrop') : null;

    function showCartPopup(message) {
        if (!popupEl || !popupMessageEl) {
            window.alert(message);
            return;
        }

        popupMessageEl.textContent = message;
        popupEl.classList.remove('cart-popup-hidden');
    }

    function hideCartPopup() {
        if (!popupEl) return;
        popupEl.classList.add('cart-popup-hidden');
    }

    if (popupCloseBtn) {
        popupCloseBtn.addEventListener('click', hideCartPopup);
    }

    if (popupBackdrop) {
        popupBackdrop.addEventListener('click', hideCartPopup);
    }

    function updateTotalsFromResponse(data) {
        if (!data || !data.success) return;

        if (totalAmountEl) {
            totalAmountEl.textContent = (data.totalPrice ?? 0).toFixed(2);
        }

        if (totalCaloriesEl) {
            const totalKcal = data.totalCalories ?? 0;
            totalCaloriesEl.textContent = totalKcal.toString();
        }

        if (!cartItemsContainer) return;

        if (!data.cartItems || data.cartItems.length === 0) {
            const emptyText = (window.CART_EMPTY_MESSAGE || 'Your cart is empty.');
            cartItemsContainer.innerHTML = '<p class="emptyMessage">' + emptyText + '</p>';
            cartItemsContainer.classList.remove('single-item', 'multiple-items');
            cartItemsContainer.classList.add('empty-cart');
        } else {
            const itemCount = data.cartItems.length;
            cartItemsContainer.classList.remove('single-item', 'multiple-items', 'empty-cart');
            cartItemsContainer.classList.add(itemCount === 1 ? 'single-item' : 'multiple-items');
        }
    }

    async function callApi(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        let data = null;
        try {
            data = await response.json();
        } catch (_) {
            data = null;
        }

        if (!response.ok) {
            const msg = (data && data.error) || window.CART_GENERIC_ERROR || 'Something went wrong. Please try again.';
            throw new Error(msg);
        }

        return data;
    }

    function handleQuantityChange(button, isIncrement) {
        const name = button.getAttribute('data-item');
        if (!name) return;

        const cartItemEl = button.closest('.cartItem');
        const quantityEl = cartItemEl ? cartItemEl.querySelector('.itemQuantity') : null;

        callApi(
            isIncrement ? 'api/addingProductToCart.php' : 'api/removingProductFromCart.php',
            { name: name }
        ).then(data => {
            if (!data.success) {
                showCartPopup(data.error || window.CART_GENERIC_ERROR || 'Something went wrong. Please try again.');
                return;
            }

            updateTotalsFromResponse(data);

            if (!cartItemEl || !quantityEl) {
                return;
            }

            const updatedItem = (data.cartItems || []).find(item => item.name === name);

            if (!updatedItem) {
                cartItemEl.remove();
            } else {
                quantityEl.textContent = updatedItem.quantity;
                const nameLabel = cartItemEl.querySelector('.itemName p');
                if (nameLabel) {
                    nameLabel.textContent = updatedItem.quantity + 'X ' + updatedItem.name;
                }
            }
        }).catch((err) => {
            const msg = err && err.message ? err.message : (window.CART_NETWORK_ERROR || 'Failed to update cart. Please try again.');
            showCartPopup(msg);
        });
    }

    function handleRemoveAll(trashIcon) {
        const cartItemEl = trashIcon.closest('.cartItem');
        if (!cartItemEl) return;

        const name = cartItemEl.getAttribute('data-name');
        if (!name) return;

        callApi('api/removingProductFromCart.php', {
            name: name,
            removeAll: true
        }).then(data => {
            if (!data.success) {
                showCartPopup(data.error || window.CART_GENERIC_ERROR || 'Something went wrong. Please try again.');
                return;
            }

            updateTotalsFromResponse(data);
            cartItemEl.remove();
        }).catch((err) => {
            const msg = err && err.message ? err.message : (window.CART_NETWORK_ERROR || 'Failed to remove item. Please try again.');
            showCartPopup(msg);
        });
    }

    function initCartControls() {
        const addButtons = document.querySelectorAll('.addButton');
        const removeButtons = document.querySelectorAll('.removeButton');
        const trashIcons = document.querySelectorAll('.trashIcon');

        addButtons.forEach(button => {
            button.addEventListener('click', () => handleQuantityChange(button, true));
        });

        removeButtons.forEach(button => {
            button.addEventListener('click', () => handleQuantityChange(button, false));
        });

        trashIcons.forEach(icon => {
            icon.addEventListener('click', () => handleRemoveAll(icon));
        });

        if (changeOrderButton) {
            changeOrderButton.addEventListener('click', () => {
                window.location.href = 'menu.php';
            });
        }

        if (checkoutButton) {
            checkoutButton.addEventListener('click', () => {
                const hasItems = cartItemsContainer && cartItemsContainer.querySelector('.cartItem');
                if (!hasItems) {
                    const emptyText = window.CART_EMPTY_MESSAGE || 'Your cart is empty.';
                    showCartPopup(emptyText);
                    return;
                }

                callApi('api/creatingOrder.php', {})
                    .then(data => {
                        if (!data.success) {
                            showCartPopup(data.error || window.CART_GENERIC_ERROR || 'Failed to create order. Please try again.');
                            return;
                        }

                        window.location.href = 'checkout.php';
                    })
                    .catch((err) => {
                        const msg = err && err.message ? err.message : (window.CART_NETWORK_ERROR || 'Failed to create order. Please try again.');
                        showCartPopup(msg);
                    });
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initCartControls);
})();