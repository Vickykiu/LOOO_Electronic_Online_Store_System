<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOOO - Payment</title>
    <link rel="stylesheet" href="site-pages.css">
    <style>
        body { background: #f8f9fa; }
        .checkout-page { max-width: 1200px; margin: 0 auto; padding: 52px 50px 80px; }
        .checkout-heading { margin-bottom: 30px; }
        .checkout-heading a { display: inline-block; margin-bottom: 14px; color: #007bff; text-decoration: none; font-weight: 700; }
        .checkout-heading h1 { margin-bottom: 8px; font-size: clamp(34px, 5vw, 48px); }
        .checkout-heading p { color: #666; }
        .checkout-layout { display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 30px; align-items: start; }
        .checkout-form, .order-summary, .confirmation { background: white; border: 1px solid #e8e8e8; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.05); }
        .checkout-form { padding: 30px; }
        .form-section + .form-section { margin-top: 32px; padding-top: 28px; border-top: 1px solid #eee; }
        .form-section h2 { margin-bottom: 6px; font-size: 24px; }
        .form-section > p { margin-bottom: 20px; color: #666; line-height: 1.55; }
        .payment-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .payment-option { padding: 17px; display: flex; gap: 10px; align-items: flex-start; border: 1px solid #d9d9d9; border-radius: 9px; cursor: pointer; }
        .payment-option:has(input:checked) { border-color: #007bff; background: rgba(0,123,255,.06); }
        .payment-option strong, .payment-option small { display: block; }
        .payment-option small { margin-top: 4px; color: #666; line-height: 1.35; }
        .card-fields { margin-top: 20px; }
        .card-fields.hidden { display: none; }
        .terms-row { margin-top: 22px; display: flex; gap: 10px; align-items: flex-start; color: #555; font-size: 14px; line-height: 1.5; }
        .voucher-row { margin-top: 20px; display: grid; grid-template-columns: 1fr auto; gap: 10px; }
        .voucher-row input { width: 100%; padding: 12px 14px; border: 1px solid #ccc; border-radius: 7px; font: inherit; text-transform: uppercase; }
        .voucher-row button { padding: 10px 18px; color: #007bff; background: #fff; border: 1px solid #007bff; border-radius: 7px; cursor: pointer; font-weight: 700; }
        .voucher-message { min-height: 20px; margin-top: 7px; font-size: 13px; font-weight: 600; }
        .pay-button { width: 100%; min-height: 50px; margin-top: 24px; color: white; background: #007bff; border: 0; border-radius: 25px; cursor: pointer; font-weight: 800; }
        .pay-button:hover { background: #0056b3; }
        .pay-button:disabled { background: #a9b7c6; cursor: not-allowed; }
        .order-summary { padding: 26px; position: sticky; top: 20px; }
        .order-summary h2 { margin-bottom: 20px; }
        .summary-items { max-height: 330px; overflow: auto; }
        .summary-item { padding: 12px 0; display: grid; grid-template-columns: 1fr auto; gap: 12px; border-bottom: 1px solid #eee; }
        .summary-item-name { font-weight: 700; line-height: 1.4; }
        .summary-item-meta { margin-top: 4px; color: #666; font-size: 13px; }
        .summary-item-price { font-weight: 700; white-space: nowrap; }
        .summary-row { padding: 9px 0; display: flex; justify-content: space-between; gap: 16px; color: #555; }
        .summary-row.first { margin-top: 13px; }
        .summary-row.total { margin-top: 8px; padding-top: 17px; color: #111; border-top: 1px solid #ddd; font-size: 20px; font-weight: 800; }
        .empty-order { padding: 22px 0; color: #666; text-align: center; line-height: 1.55; }
        .empty-order a { color: #007bff; font-weight: 700; }
        .confirmation { display: none; padding: 50px 30px; text-align: center; }
        .confirmation.show { display: block; }
        .confirmation-icon { width: 64px; height: 64px; margin: 0 auto 18px; display: flex; align-items: center; justify-content: center; color: white; background: #198754; border-radius: 50%; font-size: 30px; font-weight: 800; }
        .confirmation h1 { margin-bottom: 12px; font-size: 38px; }
        .confirmation p { max-width: 600px; margin: 0 auto 9px; color: #666; line-height: 1.6; }
        .order-number { margin: 22px auto; padding: 14px 18px; display: inline-block; background: #f8f9fa; border-radius: 8px; font-weight: 800; }
        .confirmation-actions { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
        @media (max-width: 900px) { .checkout-layout { grid-template-columns: 1fr; } .order-summary { position: static; } }
        @media (max-width: 768px) { .checkout-page { padding: 38px 20px 60px; } .checkout-form { padding: 24px 20px; } .payment-options { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <a class="site-logo" href="main page.php" aria-label="LOOO home"><img src="logo.png" alt="LOOO Logo"></a>
            <nav class="site-nav" aria-label="Main navigation">
                <div class="nav-item"><a href="Products.html">Products</a><div class="dropdown-menu"><a href="Products.html?brand=Acer">Acer</a><a href="Products.html?brand=Asus">Asus</a><a href="Products.html?brand=Dell">Dell</a><a href="Products.html?brand=HP">HP</a><a href="Products.html?brand=Lenovo">Lenovo</a><a href="Products.html?brand=MSI">MSI</a></div></div>
                <div class="nav-item"><a href="Customer Services.php">Services</a><div class="dropdown-menu"><a href="Customer Services.php">Customer Services</a><a href="Support Services.html">Support Services</a></div></div>
                <div class="nav-item"><a href="Extend Device Warranty.php">Support</a><div class="dropdown-menu"><a href="Extend Device Warranty.php">Extend Device Warranty</a><a href="Register Products Services.html">Register Products &amp; Services</a></div></div>
                <div class="nav-item"><a href="rewards.html">Deals</a><div class="dropdown-menu"><a href="rewards.html">My Rewards</a><a href="Student Discounts.html">Student Discounts</a></div></div>
            </nav>
            <div class="header-actions"><a class="login-link" href="Login.php">Login / Sign in</a><a class="icon-link" href="rewards.html" aria-label="Rewards">🎁</a><a class="icon-link" href="Cart.html" aria-label="Cart">🛒</a></div>
        </div>
        <div class="header-search"><a href="Products.html"><span>What are you looking for?</span><span aria-hidden="true">⌕</span></a></div>
    </header>

    <main class="checkout-page">
        <div id="checkoutContent">
            <div class="checkout-heading"><a href="Cart.html">← Return to cart</a><h1>Payment</h1><p>Complete your delivery and payment details.</p></div>
            <div class="checkout-layout">
                <form class="checkout-form" id="paymentForm">
                    <section class="form-section">
                        <h2>Contact information</h2><p>We will use these details for the order confirmation.</p>
                        <div class="form-grid">
                            <div class="form-group"><label for="fullName">Full name</label><input id="fullName" name="fullName" required autocomplete="name"></div>
                            <div class="form-group"><label for="email">Email address</label><input id="email" name="email" type="email" required autocomplete="email"></div>
                            <div class="form-group full"><label for="phone">Phone number</label><input id="phone" name="phone" type="tel" required autocomplete="tel" pattern="[0-9+() -]{8,20}"></div>
                        </div>
                    </section>

                    <section class="form-section">
                        <h2>Delivery address</h2><p>Enter the address where the order should be delivered.</p>
                        <div class="form-grid">
                            <div class="form-group full"><label for="address">Address</label><input id="address" name="address" required autocomplete="street-address"></div>
                            <div class="form-group"><label for="city">City</label><input id="city" name="city" required autocomplete="address-level2"></div>
                            <div class="form-group"><label for="state">State</label><select id="state" name="state" required autocomplete="address-level1"><option value="">Choose a state</option><option>Johor</option><option>Kedah</option><option>Kelantan</option><option>Melaka</option><option>Negeri Sembilan</option><option>Pahang</option><option>Penang</option><option>Perak</option><option>Perlis</option><option>Sabah</option><option>Sarawak</option><option>Selangor</option><option>Terengganu</option><option>Federal Territory of Kuala Lumpur</option><option>Federal Territory of Labuan</option><option>Federal Territory of Putrajaya</option></select></div>
                            <div class="form-group"><label for="postalCode">Postcode</label><input id="postalCode" name="postalCode" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" required autocomplete="postal-code"></div>
                            <div class="form-group"><label for="country">Country</label><input id="country" name="country" value="Malaysia" readonly></div>
                        </div>
                    </section>

                    <section class="form-section">
                        <h2>Payment method</h2><p>Select how you would like to complete this demonstration checkout.</p>
                        <div class="payment-options">
                            <label class="payment-option"><input type="radio" name="paymentMethod" value="card" checked><span><strong>Credit or debit card</strong><small>Enter card details below</small></span></label>
                            <label class="payment-option"><input type="radio" name="paymentMethod" value="bank"><span><strong>Online banking</strong><small>Confirm the order without card fields</small></span></label>
                        </div>
                        <div class="card-fields form-grid" id="cardFields">
                            <div class="form-group full"><label for="cardNumber">Card number</label><input id="cardNumber" name="cardNumber" inputmode="numeric" placeholder="1234 5678 9012 3456" maxlength="23" autocomplete="cc-number" required></div>
                            <div class="form-group"><label for="expiry">Expiry date</label><input id="expiry" name="expiry" inputmode="numeric" placeholder="MM/YY" maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" autocomplete="cc-exp" required></div>
                            <div class="form-group"><label for="cvv">Security code</label><input id="cvv" name="cvv" type="password" inputmode="numeric" placeholder="CVV" minlength="3" maxlength="4" pattern="[0-9]{3,4}" autocomplete="cc-csc" required></div>
                        </div>
                        <div class="voucher-row"><input id="voucherCode" name="voucherCode" placeholder="Voucher code"><button id="applyVoucher" type="button">Apply</button></div>
                        <div class="voucher-message" id="voucherMessage" role="status"></div>
                        <label class="terms-row"><input type="checkbox" required><span>I confirm the delivery and order details are correct. This project checkout demonstrates the flow and does not transmit a real payment.</span></label>
                        <button class="pay-button" id="payButton" type="submit">Confirm Payment</button>
                    </section>
                </form>

                <aside class="order-summary" aria-label="Order summary">
                    <h2>Your Order</h2>
                    <div class="summary-items" id="summaryItems"></div>
                    <div class="summary-row first"><span>Subtotal</span><strong id="summarySubtotal">RM 0.00</strong></div>
                    <div class="summary-row"><span>Delivery</span><strong id="summaryShipping">RM 0.00</strong></div>
                    <div class="summary-row" id="discountRow" style="display:none"><span>Voucher discount</span><strong id="summaryDiscount">RM 0.00</strong></div>
                    <div class="summary-row total"><span>Total</span><span id="summaryTotal">RM 0.00</span></div>
                </aside>
            </div>
        </div>

        <section class="confirmation" id="confirmation" aria-live="polite">
            <div class="confirmation-icon" aria-hidden="true">✓</div>
            <h1>Order confirmed</h1>
            <p>Thank you. Your demonstration order has been created and the cart has been cleared.</p>
            <div class="order-number" id="orderNumber"></div>
            <p>Keep this order number for reference.</p>
            <div class="confirmation-actions"><a class="primary-btn" href="main page.php">Return to Main Page</a><a class="secondary-btn" href="Products.html">Continue Shopping</a></div>
        </section>
    </main>

    <footer class="site-footer"><div class="social-icons"><a href="#" aria-label="Facebook"><img src="facebook.png" alt=""></a><a href="#" aria-label="YouTube"><img src="youtube.png" alt=""></a><a href="#" aria-label="WhatsApp"><img src="whatsapp.png" alt=""></a></div><p>LOOO Electronics &amp; Computers</p></footer>

    <script>
        const CART_KEY = 'loooCart';
        const CHECKOUT_KEY = 'loooCheckout';
        const ORDER_KEY = 'loooLastOrder';
        const paymentForm = document.getElementById('paymentForm');
        const payButton = document.getElementById('payButton');
        const cardFields = document.getElementById('cardFields');
        let checkout = loadCheckout();

        function loadCheckout() {
            try {
                const stored = JSON.parse(localStorage.getItem(CHECKOUT_KEY) || 'null');
                if (stored && Array.isArray(stored.items)) return stored;
                const cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
                if (!Array.isArray(cart) || !cart.length) return { items: [], subtotal: 0, shipping: 0, total: 0 };
                const subtotal = cart.reduce((sum, item) => sum + Number(item.price) * Number(item.quantity || 1), 0);
                const shipping = subtotal < 500 ? 25 : 0;
                return { items: cart, subtotal, shipping, total: subtotal + shipping };
            } catch (error) {
                return { items: [], subtotal: 0, shipping: 0, total: 0 };
            }
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-MY', { style: 'currency', currency: 'MYR' }).format(amount).replace('MYR', 'RM');
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[character]));
        }

        function displayOrder() {
            const summaryItems = document.getElementById('summaryItems');
            if (!checkout.items.length) {
                summaryItems.innerHTML = '<div class="empty-order">There are no items ready for payment.<br><a href="Products.html">Browse products</a></div>';
                payButton.disabled = true;
            } else {
                summaryItems.innerHTML = checkout.items.map((item) => `<div class="summary-item"><div><div class="summary-item-name">${escapeHtml(item.name)}</div><div class="summary-item-meta">Quantity: ${Number(item.quantity || 1)}</div></div><div class="summary-item-price">${formatCurrency(Number(item.price) * Number(item.quantity || 1))}</div></div>`).join('');
            }
            document.getElementById('summarySubtotal').textContent = formatCurrency(Number(checkout.subtotal || 0));
            document.getElementById('summaryShipping').textContent = Number(checkout.shipping || 0) ? formatCurrency(Number(checkout.shipping)) : 'Free';
            document.getElementById('summaryTotal').textContent = formatCurrency(Number(checkout.total || 0));
        }

        function updatePaymentMethod() {
            const isCard = paymentForm.elements.paymentMethod.value === 'card';
            cardFields.classList.toggle('hidden', !isCard);
            cardFields.querySelectorAll('input').forEach((input) => { input.required = isCard; });
        }

        document.querySelectorAll('input[name="paymentMethod"]').forEach((input) => input.addEventListener('change', updatePaymentMethod));
        document.getElementById('cardNumber').addEventListener('input', (event) => {
            const digits = event.target.value.replace(/\D/g, '').slice(0, 19);
            event.target.value = digits.replace(/(.{4})/g, '$1 ').trim();
        });
        document.getElementById('expiry').addEventListener('input', (event) => {
            const digits = event.target.value.replace(/\D/g, '').slice(0, 4);
            event.target.value = digits.length > 2 ? `${digits.slice(0, 2)}/${digits.slice(2)}` : digits;
        });

        paymentForm.addEventListener('submit', (event) => {
            event.preventDefault();
            if (!checkout.items.length || !paymentForm.reportValidity()) return;
            const orderId = `LOOO-${new Date().toISOString().slice(0, 10).replace(/-/g, '')}-${Math.random().toString(36).slice(2, 8).toUpperCase()}`;
            const order = { id: orderId, createdAt: new Date().toISOString(), total: checkout.total, items: checkout.items, customer: { name: paymentForm.elements.fullName.value, email: paymentForm.elements.email.value } };
            localStorage.setItem(ORDER_KEY, JSON.stringify(order));
            localStorage.removeItem(CART_KEY);
            localStorage.removeItem(CHECKOUT_KEY);
            document.getElementById('checkoutContent').style.display = 'none';
            document.getElementById('orderNumber').textContent = `Order ${orderId}`;
            document.getElementById('confirmation').classList.add('show');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.querySelectorAll('a[href="#"]').forEach((link) => link.addEventListener('click', (event) => event.preventDefault()));
        updatePaymentMethod();
        displayOrder();
    </script>
    <script>
        (() => {
            const form = document.getElementById('paymentForm');
            const payButton = document.getElementById('payButton');
            const voucherInput = document.getElementById('voucherCode');
            const voucherButton = document.getElementById('applyVoucher');
            const voucherMessage = document.getElementById('voucherMessage');
            let databaseOrder = { items: [], subtotal: 0, shipping: 0, discount: 0, total: 0 };
            const money = (amount) => new Intl.NumberFormat('en-MY', { style: 'currency', currency: 'MYR' }).format(amount).replace('MYR', 'RM');
            const clean = (value) => String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[character]));

            function renderDatabaseOrder() {
                const summaryItems = document.getElementById('summaryItems');
                if (!databaseOrder.items.length) { summaryItems.innerHTML = '<div class="empty-order">There are no items ready for payment.<br><a href="Products.html">Browse products</a></div>'; payButton.disabled = true; }
                else { summaryItems.innerHTML = databaseOrder.items.map((item) => `<div class="summary-item"><div><div class="summary-item-name">${clean(item.name)}</div><div class="summary-item-meta">Quantity: ${Number(item.quantity)}</div></div><div class="summary-item-price">${money(Number(item.price) * Number(item.quantity))}</div></div>`).join(''); payButton.disabled = false; }
                document.getElementById('summarySubtotal').textContent = money(Number(databaseOrder.subtotal));
                document.getElementById('summaryShipping').textContent = Number(databaseOrder.shipping) ? money(Number(databaseOrder.shipping)) : 'Free';
                document.getElementById('discountRow').style.display = databaseOrder.discount > 0 ? 'flex' : 'none';
                document.getElementById('summaryDiscount').textContent = `− ${money(Number(databaseOrder.discount))}`;
                document.getElementById('summaryTotal').textContent = money(Number(databaseOrder.total));
            }

            async function applyDatabaseVoucher() {
                const code = voucherInput.value.trim();
                if (!code) { databaseOrder.discount = 0; databaseOrder.total = Number(databaseOrder.subtotal) + Number(databaseOrder.shipping); voucherMessage.textContent = ''; renderDatabaseOrder(); return; }
                voucherButton.disabled = true;
                try {
                    const response = await fetch(`api.php?action=voucher_preview&code=${encodeURIComponent(code)}`, { credentials: 'same-origin' });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Voucher could not be applied.');
                    databaseOrder.discount = Number(result.discount);
                    databaseOrder.total = Math.max(0, Number(databaseOrder.subtotal) + Number(databaseOrder.shipping) - databaseOrder.discount);
                    voucherInput.value = result.code;
                    voucherMessage.textContent = `${result.code} applied successfully.`;
                    voucherMessage.style.color = '#0f5132';
                    renderDatabaseOrder();
                } catch (error) { databaseOrder.discount = 0; databaseOrder.total = Number(databaseOrder.subtotal) + Number(databaseOrder.shipping); voucherMessage.textContent = error.message; voucherMessage.style.color = '#842029'; renderDatabaseOrder(); }
                finally { voucherButton.disabled = false; }
            }

            async function loadDatabaseOrder() {
                const response = await fetch('api.php?action=cart', { credentials: 'same-origin' });
                const result = await response.json();
                if (result.login_required) { window.location.href = 'Login.php?return=Payment.php'; return; }
                if (!response.ok || !result.success) throw new Error(result.message || 'Unable to load the order.');
                databaseOrder = { ...result, discount: 0, total: Number(result.total) };
                renderDatabaseOrder();
                const profileResponse = await fetch('api.php?action=profile', { credentials: 'same-origin' });
                const profile = await profileResponse.json();
                if (profile.success && profile.user) {
                    const user = profile.user;
                    form.elements.fullName.value = `${user.first_name || ''} ${user.last_name || ''}`.trim();
                    form.elements.email.value = user.email || '';
                    form.elements.phone.value = user.phone || '';
                    form.elements.address.value = user.address || '';
                    form.elements.city.value = user.city || '';
                    form.elements.postalCode.value = user.postal_code || '';
                }
                const queryVoucher = new URLSearchParams(window.location.search).get('voucher');
                if (queryVoucher) { voucherInput.value = queryVoucher; await applyDatabaseVoucher(); }
            }

            voucherButton.addEventListener('click', applyDatabaseVoucher);
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                event.stopImmediatePropagation();
                if (!databaseOrder.items.length || !form.reportValidity()) return;
                payButton.disabled = true;
                payButton.textContent = 'Processing...';
                const payload = Object.fromEntries(new FormData(form));
                delete payload.cardNumber; delete payload.expiry; delete payload.cvv;
                const response = await fetch('api.php?action=checkout', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify(payload) });
                const result = await response.json();
                if (!response.ok || !result.success) { voucherMessage.textContent = result.message || 'The order could not be completed.'; voucherMessage.style.color = '#842029'; payButton.disabled = false; payButton.textContent = 'Confirm Payment'; return; }
                document.getElementById('checkoutContent').style.display = 'none';
                document.getElementById('orderNumber').textContent = `Order ${result.order_number}`;
                document.getElementById('confirmation').classList.add('show');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, true);
            loadDatabaseOrder().catch((error) => { document.getElementById('summaryItems').innerHTML = `<div class="empty-order">${clean(error.message)}</div>`; payButton.disabled = true; });
        })();
    </script>
</body>
</html>
