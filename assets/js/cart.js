(function () {
    const cartItemsContainer = document.getElementById('cartItems');
    const totalAmountEl = document.getElementById('cartTotalAmount');
    const changeOrderButton = document.getElementById('changeOrderButton');
    const checkoutButton = document.getElementById('checkoutButton');
    const totalCaloriesEl = document.getElementById('cartTotalCalories');

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

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        return response.json();
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
                alert(data.error || 'Something went wrong');
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
        }).catch(() => {
            alert('Failed to update cart. Please try again.');
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
                alert(data.error || 'Something went wrong');
                return;
            }

            updateTotalsFromResponse(data);
            cartItemEl.remove();
        }).catch(() => {
            alert('Failed to remove item. Please try again.');
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
                callApi('api/creatingOrder.php', {})
                    .then(data => {
                        if (!data.success) {
                            alert(data.error || 'Failed to create order. Please try again.');
                            return;
                        }

                        window.location.href = 'checkout.php';
                    })
                    .catch(() => {
                        alert('Failed to create order. Please try again.');
                    });
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initCartControls);
})();