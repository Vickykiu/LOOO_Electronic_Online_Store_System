<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOOO - Customer Services</title>
    <link rel="stylesheet" href="site-pages.css">
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <a class="site-logo" href="main page.php" aria-label="LOOO home"><img src="logo.png" alt="LOOO Logo"></a>
            <nav class="site-nav" aria-label="Main navigation">
                <div class="nav-item"><a href="Products.html">Products</a><div class="dropdown-menu"><a href="Products.html?brand=Acer">Acer</a><a href="Products.html?brand=Asus">Asus</a><a href="Products.html?brand=Dell">Dell</a><a href="Products.html?brand=HP">HP</a><a href="Products.html?brand=Lenovo">Lenovo</a><a href="Products.html?brand=MSI">MSI</a></div></div>
                <div class="nav-item"><a class="active" href="Customer Services.php" aria-current="page">Services</a><div class="dropdown-menu"><a href="Customer Services.php">Customer Services</a><a href="Support Services.html">Support Services</a></div></div>
                <div class="nav-item"><a href="Extend Device Warranty.php">Support</a><div class="dropdown-menu"><a href="Extend Device Warranty.php">Extend Device Warranty</a><a href="Register Products Services.html">Register Products &amp; Services</a></div></div>
                <div class="nav-item"><a href="rewards.html">Deals</a><div class="dropdown-menu"><a href="rewards.html">My Rewards</a><a href="Student Discounts.html">Student Discounts</a></div></div>
            </nav>
            <div class="header-actions"><a class="login-link" href="Login.php">Login / Sign in</a><a class="icon-link" href="rewards.html" aria-label="Rewards">🎁</a><a class="icon-link" href="Cart.html" aria-label="Cart">🛒</a></div>
        </div>
        <div class="header-search"><a href="Products.html"><span>What are you looking for?</span><span aria-hidden="true">⌕</span></a></div>
    </header>

    <main>
        <section class="page-hero">
            <div class="eyebrow">Customer Services</div>
            <h1>How can we help today?</h1>
            <p>Get help with an order, delivery, return or account question from the LOOO customer care team.</p>
            <div class="hero-actions"><a class="primary-btn" href="#contactForm">Send a request</a><a class="secondary-btn" href="#frequentQuestions">View common questions</a></div>
        </section>

        <section class="page-section">
            <div class="section-heading"><h2>Choose the help you need</h2><p>Start with the option that best matches your question.</p></div>
            <div class="card-grid">
                <article class="info-card"><div class="card-icon" aria-hidden="true">01</div><h3>Orders &amp; delivery</h3><p>Check delivery expectations, update an order before dispatch, or ask about a delayed parcel.</p></article>
                <article class="info-card"><div class="card-icon" aria-hidden="true">02</div><h3>Returns &amp; refunds</h3><p>Understand return eligibility and get help preparing a product for return or exchange.</p></article>
                <article class="info-card"><div class="card-icon" aria-hidden="true">03</div><h3>Account &amp; rewards</h3><p>Get support with your profile, sign-in, reward vouchers, or purchase history.</p></article>
            </div>
        </section>

        <section class="page-section alt" id="contactForm">
            <div class="section-inner split-layout">
                <div class="split-copy">
                    <div class="eyebrow">Contact our team</div>
                    <h2>Tell us what happened</h2>
                    <p>Share the key details and the customer care team will have the context needed to help.</p>
                    <ul class="check-list"><li>Include your order number when available</li><li>Describe the result you would like</li><li>Keep product and delivery details ready</li></ul>
                </div>
                <form class="form-card" id="customerServiceForm">
                    <h2>Customer service request</h2>
                    <p class="form-intro">All fields marked required must be completed.</p>
                    <div class="form-grid">
                        <div class="form-group"><label for="customerName">Full name</label><input id="customerName" name="customerName" required autocomplete="name"></div>
                        <div class="form-group"><label for="customerEmail">Email address</label><input id="customerEmail" name="customerEmail" type="email" required autocomplete="email"></div>
                        <div class="form-group"><label for="orderNumber">Order number</label><input id="orderNumber" name="orderNumber" placeholder="Optional"></div>
                        <div class="form-group"><label for="helpTopic">Help topic</label><select id="helpTopic" name="helpTopic" required><option value="">Choose a topic</option><option>Order or delivery</option><option>Return or refund</option><option>Account or rewards</option><option>Other question</option></select></div>
                        <div class="form-group full"><label for="customerMessage">How can we help?</label><textarea id="customerMessage" name="customerMessage" required placeholder="Describe your question or issue"></textarea></div>
                    </div>
                    <div class="form-actions"><button class="primary-btn" type="submit">Submit request</button><a class="secondary-btn" href="main page.php">Return home</a></div>
                    <div class="success-message" id="customerSuccess" role="status">Your request has been recorded. A customer care representative will follow up using the email address provided.</div>
                </form>
            </div>
        </section>

        <section class="page-section" id="frequentQuestions">
            <div class="section-heading"><h2>Frequently asked questions</h2><p>Quick answers for common customer service requests.</p></div>
            <div class="faq-list">
                <details><summary>How long does standard delivery take?</summary><p>Standard delivery normally takes two to three business days after an order is dispatched. Timing can vary by location and stock availability.</p></details>
                <details><summary>Can I return an unused product?</summary><p>Unused products in their original packaging may be eligible for return within 14 days. Keep the invoice and all included accessories.</p></details>
                <details><summary>Where can I find my order number?</summary><p>Your order number appears in the purchase confirmation and in the order summary shown after checkout.</p></details>
            </div>
        </section>
    </main>

    <footer class="site-footer"><div class="social-icons"><a href="#" aria-label="Facebook"><img src="facebook.png" alt=""></a><a href="#" aria-label="YouTube"><img src="youtube.png" alt=""></a><a href="#" aria-label="WhatsApp"><img src="whatsapp.png" alt=""></a></div><p>LOOO Electronics &amp; Computers</p></footer>
    <script>
        document.querySelectorAll('a[href="#"]').forEach((link) => link.addEventListener('click', (event) => event.preventDefault()));
    </script>
    <script>
        document.getElementById('customerServiceForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;
            const response = await fetch('api.php?action=contact', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify(Object.fromEntries(new FormData(form))) });
            const result = await response.json();
            if (result.success) { const message = document.getElementById('customerSuccess'); message.textContent = result.message; message.classList.add('show'); form.reset(); }
            else alert(result.message || 'The request could not be submitted.');
            button.disabled = false;
        });
    </script>
</body>
</html>
