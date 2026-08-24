(function () {

window.foodHubCartModuleLoaded = true;

const getCart = () => window.foodHubCart || {};

const setCart = (nextCart) => {
    window.foodHubCart = nextCart || {};
    window.cart = window.foodHubCart;
};

const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

const money = (value) => Number(value).toLocaleString('en-PK', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const escapeHtml = (value) => {
    const element = document.createElement('div');
    element.textContent = value ?? '';
    return element.innerHTML;
};

function updateCartCount() {
    const count = Object.values(getCart()).reduce(
        (total, item) => total + Number(item.quantity || 0),
        0
    );
    const navCount = document.getElementById('navCartCount');
    const fabCount = document.getElementById('fabCartCount');
    if (navCount) navCount.textContent = count;
    if (fabCount) {
        fabCount.textContent = count;
        fabCount.style.display = count > 0 ? 'flex' : 'none';
    }
}

function renderCart() {
    const container = document.getElementById('sideCartItems');
    const totalElement = document.getElementById('sideCartTotal');
    if (!container || !totalElement) return;

    const items = Object.entries(getCart());
    if (!items.length) {
        container.innerHTML = '<div class="cart-empty"><div class="cart-empty-icon">🛒</div><h3>Your Cart is Empty</h3><p>Add some delicious food.</p></div>';
        totalElement.textContent = 'Rs. 0.00';
        updateCartCount();
        if (window.innerWidth > 900) closeCart();
        return;
    }

    let total = 0;
    container.innerHTML = items.map(([key, item]) => {
        const quantity = Number(item.quantity || 0);
        const subtotal = Number(item.price || 0) * quantity;
        total += subtotal;
        const itemId = JSON.stringify(String(key));
        const image = item.image ? `<img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}">` : '🍔';
        const included = item.is_deal && item.included_items
            ? `<small>Deal items: ${escapeHtml(item.included_items)}</small>`
            : '';
        return `<div class="side-cart-item">
            <div class="side-cart-image">${image}</div>
            <div class="side-cart-info">
                <h4>${escapeHtml(item.name)}</h4>
                ${included}
                <div class="side-cart-price">Rs. ${money(subtotal)}</div>
                <div class="quantity-controls">
                    <button class="qty-btn" onclick='changeQuantity(${itemId}, -1)'>−</button>
                    <span class="qty-number">${quantity}</span>
                    <button class="qty-btn" onclick='changeQuantity(${itemId}, 1)'>+</button>
                </div>
            </div>
            <button class="remove-item" onclick='removeFromCart(${itemId})' title="Remove">🗑️</button>
        </div>`;
    }).join('');
    totalElement.textContent = `Rs. ${money(total)}`;
    updateCartCount();
}

async function cartRequest(url, body) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
        },
        body: new URLSearchParams(body),
    });
    if (!response.ok) throw new Error(`Cart request failed (${response.status})`);
    return response.json();
}

function openCart() {
    const panel = document.getElementById('sideCart');
    if (!panel) return;
    if (!Object.keys(getCart()).length && window.innerWidth > 900) return;
    panel.classList.add('open');
    document.getElementById('cartOverlay')?.classList.add('show');
    renderCart();
}

function closeCart() {
    document.getElementById('sideCart')?.classList.remove('open');
    document.getElementById('cartOverlay')?.classList.remove('show');
}

async function changeQuantity(id, change) {
    const key = String(id);
    const item = getCart()[key];
    if (!item) return;
    const quantity = Number(item.quantity || 0) + Number(change);
    if (quantity <= 0) return removeFromCart(key);
    await updateQuantity(key, quantity);
}

async function updateQuantity(id, quantity) {
    try {
        const data = await cartRequest('/cart/update-json', { id: String(id), quantity });
        setCart(data.cart);
        renderCart();
    } catch (error) {
        console.error(error);
        window.showToast?.('Cart update failed. Please refresh and try again.');
    }
}

async function removeFromCart(id) {
    try {
        const data = await cartRequest('/cart/remove-json', { id: String(id) });
        setCart(data.cart);
        renderCart();
        window.showToast?.('Item removed');
    } catch (error) {
        console.error(error);
        window.showToast?.('Item remove failed. Please refresh and try again.');
    }
}

async function addDealToCart(announcementId) {
    try {
        const data = await cartRequest(`/cart/add-deal/${announcementId}`, {});
        setCart(data.cart);
        renderCart();
        openCart();
        window.showToast?.('Deal added to cart!');
    } catch (error) {
        console.error(error);
        window.showToast?.('Deal could not be added. Please refresh and try again.');
    }
}

async function addToCart(foodId, announcementId = null) {
    try {
        const data = await cartRequest(`/cart/add/${foodId}`, { announcement_id: announcementId });
        setCart(data.cart);
        renderCart();
        openCart();
        window.showToast?.('Food added to cart!');
    } catch (error) {
        console.error(error);
        window.showToast?.('Food could not be added. Please refresh and try again.');
    }
}

window.foodHubCart = window.foodHubCart || {};
window.cart = window.foodHubCart;
window.openCart = openCart;
window.closeCart = closeCart;
window.changeQuantity = changeQuantity;
window.updateQuantity = updateQuantity;
window.removeFromCart = removeFromCart;
window.addDealToCart = addDealToCart;
window.addToCart = addToCart;
window.renderCart = renderCart;
window.updateCartCount = updateCartCount;

document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    renderCart();
});

window.addEventListener('load', () => {
    setTimeout(renderCart, 0);
});

})();
