// Show Navbar when small screen || Close Cart Items & Search Textbox
let navbar = document.querySelector('.navbar');

document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
    cartItem.classList.remove('active');
    searchForm.classList.remove('active');
}

// Show Cart Items || Close Navbar & Search Textbox
let cartItem = document.querySelector('.cart');

document.querySelector('#cart-btn').onclick = () => {
    cartItem.classList.toggle('active');
    navbar.classList.remove('active');
    searchForm.classList.remove('active');
}

// Show Search Textbox || Close Navbar & Cart Items
let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () => {
    searchForm.classList.toggle('active');
    navbar.classList.remove('active');
    cartItem.classList.remove('active');
}

// Remove Active Icons on Scroll and Close it
window.onscroll = () => {
    navbar.classList.remove('active');
    cartItem.classList.remove('active');
    searchForm.classList.remove('active');
}

// Toast utility
window.showBVToast = function(message, type) {
    var t = document.getElementById('bv-toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'bv-toast';
        t.className = 'bv-toast';
        t.innerHTML = '<div class="bv-icon">✓</div><div class="bv-message"></div><div class="bv-progress"><span></span></div>';
        document.body.appendChild(t);
    }
    var icon = t.querySelector('.bv-icon');
    var msg = t.querySelector('.bv-message');
    var bar = t.querySelector('.bv-progress > span');
    t.style.display = 'flex';
    t.classList.remove('bv-success','bv-error','bv-show');
    if (type === 'error') {
        t.classList.add('bv-error');
        icon.textContent = '✕';
    } else {
        t.classList.add('bv-success');
        icon.textContent = '✓';
    }
    msg.textContent = message || 'Action completed.';
    // reset progress
    bar.style.width = '100%';
    // force reflow
    void t.offsetWidth;
    t.classList.add('bv-show');
    var duration = 3200;
    var start = Date.now();
    clearInterval(window.__bvProgressTimer);
    window.__bvProgressTimer = setInterval(function(){
        var elapsed = Date.now() - start;
        var remaining = Math.max(0, 1 - (elapsed / duration));
        bar.style.width = (remaining * 100).toFixed(0) + '%';
        if (elapsed >= duration) {
            clearInterval(window.__bvProgressTimer);
            t.classList.remove('bv-show');
            setTimeout(function(){ t.style.display = 'none'; }, 250);
        }
    }, 30);
}


//Cart Working JS
if (document.readyState == 'loading') {
    document.addEventListener("DOMContentLoaded", ready);
} else {
    ready();
}

// FUNCTIONS FOR CART
function ready() {
    //Remove Items from Cart
    var removeCartButtons = document.getElementsByClassName('cart-remove');
    console.log(removeCartButtons);
    for (var i = 0; i < removeCartButtons.length; i++){
        var button = removeCartButtons[i];
        button.addEventListener('click', removeCartItem);
    }

    // When quantity changes
    var quantityInputs = document.getElementsByClassName("cart-quantity");
    for (var i = 0; i < quantityInputs.length; i++){
        var input = quantityInputs[i];
        input.addEventListener("change", quantityChanged);
    }

    // Add to Cart
    var addCart = document.getElementsByClassName('add-cart');
    for (var i = 0; i < addCart.length; i++){
        var button = addCart[i];
        button.addEventListener("click", addCartClicked);
    }

    // Buy Button Works
    document.getElementsByClassName("btn-buy")[0].addEventListener("click", buyButtonClicked);
}

// Function for "Buy Button Works"
function buyButtonClicked() {
    var cartContent = document.getElementsByClassName("cart-content")[0];
    if (!cartContent) { return; }
    var cartBoxes = cartContent.getElementsByClassName("cart-box");
    if (!cartBoxes.length) {
        window.showBVToast && window.showBVToast('Your cart is empty.', 'error');
        return;
    }

    var orderDetails = [];
    var invoiceNumber = generateInvoiceNumber();
    for (var i = 0; i < cartBoxes.length; i++) {
        var cartBox = cartBoxes[i];
        var title = cartBox.getElementsByClassName("cart-product-title")[0].innerText.trim();
        var price = cartBox.getElementsByClassName("cart-price")[0].innerText;
        var quantityRaw = cartBox.getElementsByClassName("cart-quantity")[0].value;
        var priceValue = parseFloat(String(price).replace(/[^0-9.]/g, ''));
        var quantity = parseInt(quantityRaw, 10);
        if (!title || isNaN(priceValue) || priceValue < 0 || !quantity || quantity < 1 || quantity > 100) {
            window.showBVToast && window.showBVToast('Invalid item in cart. Please review.', 'error');
            return;
        }
        var subtotalAmount = priceValue * quantity;
        orderDetails.push({ title: title, price: priceValue, quantity: quantity, subtotal_amount: subtotalAmount, invoice_number: invoiceNumber });
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_database.php", true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                window.showBVToast && window.showBVToast('Your order is placed! Enjoy your coffee!', 'success');
                cartItem.classList.remove('active');
                while (cartContent.hasChildNodes()) { cartContent.removeChild(cartContent.firstChild); }
                updateTotal();
            } else {
                window.showBVToast && window.showBVToast('Failed to place order. Try again.', 'error');
            }
        }
    };
    xhr.send(JSON.stringify(orderDetails));
}

// Function to generate invoice number
function generateInvoiceNumber() {
    // Implement your logic to generate an invoice number here
    return "INV-" + Math.floor(Math.random() * 1000000);
}

// Function for "Remove Items from Cart"
function removeCartItem(event) {
    var buttonClicked = event.target;
    buttonClicked.parentElement.remove();
    updateTotal();
}

// Function for "When quantity changes"
function quantityChanged(event) {
    var input = event.target;
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1;
    }
    updateTotal();
}

//Add to Cart
function addCartClicked(event) {
    var button = event.target;
    var shopProducts = button.parentElement;
    var title = shopProducts.getElementsByClassName("product-title")[0].innerText;
    var price = shopProducts.getElementsByClassName("price")[0].innerText;
    var productImg = shopProducts.getElementsByClassName("product-img")[0].src;
    addProductToCart(title, price, productImg);
    updateTotal();
}

function addProductToCart(title, price, productImg) {
    var cartShopBox = document.createElement("div");
    cartShopBox.classList.add("cart-box");
    var cartItems = document.getElementsByClassName("cart-content")[0];
    var cartItemsNames = cartItems.getElementsByClassName("cart-product-title");
    for (var i = 0; i < cartItemsNames.length; i++) {
        if (cartItemsNames[i].innerText == title) {
            alert("You have already added this item to cart!")
            return;
        }
    }
    var cartBoxContent = `
                    <img src="${productImg}" alt="" class="cart-img">
                    <div class="detail-box">
                        <div class="cart-product-title">${title}</div>
                        <div class="cart-price">${price}</div>
                        <input type="number" value="1" min="1" class="cart-quantity">
                    </div>
                    <!-- REMOVE BUTTON -->
                    <i class="fas fa-trash cart-remove"></i>`;
    cartShopBox.innerHTML = cartBoxContent;
    cartItems.append(cartShopBox);
    cartShopBox
        .getElementsByClassName("cart-remove")[0]
        .addEventListener('click', removeCartItem);
    cartShopBox
        .getElementsByClassName("cart-quantity")[0]
        .addEventListener('change', quantityChanged);

}

// Update Total
function updateTotal() {
    var cartContent = document.getElementsByClassName("cart-content")[0];
    var cartBoxes = cartContent.getElementsByClassName("cart-box");
    var total = 0;
    for (var i = 0; i < cartBoxes.length; i++) {
        var cartBox = cartBoxes[i];
        var priceElement = cartBox.getElementsByClassName("cart-price")[0];
        var quantityElement = cartBox.getElementsByClassName("cart-quantity")[0];
        var price = parseFloat(priceElement.innerText.replace("₱", ""));
        var quantity = quantityElement.value;
        total = total + (price * quantity);
    }
        total = Math.round(total * 100) / 100;
        
        document.getElementsByClassName("total-price")[0].innerText = "₱" + total;
}