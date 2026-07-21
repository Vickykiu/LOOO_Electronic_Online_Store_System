<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOOO - Extend Device Warranty</title>
    <link rel="stylesheet" href="site-pages.css">
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <a class="site-logo" href="main page.php" aria-label="LOOO home"><img src="logo.png" alt="LOOO Logo"></a>
            <nav class="site-nav" aria-label="Main navigation">
                <div class="nav-item"><a href="Products.html">Products</a><div class="dropdown-menu"><a href="Products.html?brand=Acer">Acer</a><a href="Products.html?brand=Asus">Asus</a><a href="Products.html?brand=Dell">Dell</a><a href="Products.html?brand=HP">HP</a><a href="Products.html?brand=Lenovo">Lenovo</a><a href="Products.html?brand=MSI">MSI</a></div></div>
                <div class="nav-item"><a href="Customer Services.php">Services</a><div class="dropdown-menu"><a href="Customer Services.php">Customer Services</a><a href="Support Services.html">Support Services</a></div></div>
                <div class="nav-item"><a class="active" href="Extend Device Warranty.php" aria-current="page">Support</a><div class="dropdown-menu"><a href="Extend Device Warranty.php">Extend Device Warranty</a><a href="Register Products Services.html">Register Products &amp; Services</a></div></div>
                <div class="nav-item"><a href="rewards.html">Deals</a><div class="dropdown-menu"><a href="rewards.html">My Rewards</a><a href="Student Discounts.html">Student Discounts</a></div></div>
            </nav>
            <div class="header-actions"><a class="login-link" href="Login.php">Login / Sign in</a><a class="icon-link" href="rewards.html" aria-label="Rewards">🎁</a><a class="icon-link" href="Cart.html" aria-label="Cart">🛒</a></div>
        </div>
        <div class="header-search"><a href="Products.html"><span>What are you looking for?</span><span aria-hidden="true">⌕</span></a></div>
    </header>

    <main>
        <section class="page-hero">
            <div class="eyebrow">Extended Warranty</div>
            <h1>Extra protection for the device you depend on</h1>
            <p>Check your device details and request an extended warranty option that continues beyond the standard coverage period.</p>
            <div class="hero-actions"><a class="primary-btn" href="#warrantyForm">Check eligibility</a><a class="secondary-btn" href="Register Products Services.html">Register your product</a></div>
        </section>

        <section class="page-section">
            <div class="section-heading"><h2>Plan with more confidence</h2><p>Extended coverage can make unexpected device issues easier to manage.</p></div>
            <div class="card-grid">
                <article class="info-card"><div class="card-icon" aria-hidden="true">01</div><h3>Longer coverage</h3><p>Continue eligible hardware protection after the original warranty period ends.</p></article>
                <article class="info-card"><div class="card-icon" aria-hidden="true">02</div><h3>Clear support route</h3><p>Use your registered device and plan information when requesting covered service.</p></article>
                <article class="info-card"><div class="card-icon" aria-hidden="true">03</div><h3>Flexible duration</h3><p>Request the coverage period that best fits how long you expect to use the device.</p></article>
            </div>
        </section>

        <section class="page-section alt" id="warrantyForm">
            <div class="section-inner split-layout">
                <div class="split-copy"><div class="eyebrow">Eligibility request</div><h2>Start with your device details</h2><p>Eligibility and final plan terms depend on the product, purchase date, condition and existing manufacturer coverage.</p><ul class="check-list"><li>Use the serial number exactly as shown</li><li>Provide the original purchase date</li><li>Keep the invoice available for verification</li><li>Review final exclusions before purchase</li></ul></div>
                <form class="form-card" id="warrantyRequestForm">
                    <h2>Request a warranty extension</h2><p class="form-intro">Submit the device details for an eligibility review.</p>
                    <div class="form-grid">
                        <div class="form-group"><label for="warrantyName">Full name</label><input id="warrantyName" name="warrantyName" required autocomplete="name"></div>
                        <div class="form-group"><label for="warrantyEmail">Email address</label><input id="warrantyEmail" name="warrantyEmail" type="email" required autocomplete="email"></div>
                        <div class="form-group"><label for="warrantyPhone">Phone number</label><input id="warrantyPhone" name="warrantyPhone" type="tel" autocomplete="tel"></div>
                        <div class="form-group"><label for="deviceType">Device type</label><select id="deviceType" name="deviceType" required><option value="Laptop">Laptop</option><option value="Desktop">Desktop</option></select></div>
                        <div class="form-group"><label for="warrantyBrand">Brand</label><select id="warrantyBrand" name="warrantyBrand" required><option value="">Choose a brand</option><option>Acer</option><option>Asus</option><option>Dell</option><option>HP</option><option>Lenovo</option><option>MSI</option><option>Other</option></select></div>
                        <div class="form-group"><label for="warrantyModel">Model</label><input id="warrantyModel" name="warrantyModel" required></div>
                        <div class="form-group"><label for="warrantySerial">Serial number</label><input id="warrantySerial" name="warrantySerial" required></div>
                        <div class="form-group"><label for="warrantyPurchaseDate">Purchase date</label><input id="warrantyPurchaseDate" name="warrantyPurchaseDate" type="date" required></div>
                        <div class="form-group"><label for="currentWarranty">Current warranty</label><select id="currentWarranty" name="currentWarranty" required><option>Standard (1 Year)</option><option>Extended (2 Years)</option><option>Premium (3 Years)</option><option>Expired</option></select></div>
                        <div class="form-group full"><span class="fieldset-label">Preferred extension</span><div class="radio-grid"><label class="choice-card"><input type="radio" name="warrantyTerm" value="1 year" required><span><strong>1 year</strong><small>Short-term additional coverage</small></span></label><label class="choice-card"><input type="radio" name="warrantyTerm" value="2 years"><span><strong>2 years</strong><small>Balanced ongoing protection</small></span></label><label class="choice-card"><input type="radio" name="warrantyTerm" value="3 years"><span><strong>3 years</strong><small>Longest available request</small></span></label></div></div>
                    </div>
                    <div class="form-actions"><button class="primary-btn" type="submit">Check eligibility</button><a class="secondary-btn" href="Support Services.html">Support services</a></div>
                    <div class="success-message" id="warrantySuccess" role="status">Your eligibility request has been recorded. Coverage availability and final terms must be confirmed before an extended warranty is activated.</div>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer"><div class="social-icons"><a href="#" aria-label="Facebook"><img src="facebook.png" alt=""></a><a href="#" aria-label="YouTube"><img src="youtube.png" alt=""></a><a href="#" aria-label="WhatsApp"><img src="whatsapp.png" alt=""></a></div><p>LOOO Electronics &amp; Computers</p></footer>
    <script>
        document.getElementById('warrantyPurchaseDate').max = new Date().toISOString().split('T')[0];
        document.querySelectorAll('a[href="#"]').forEach((link) => link.addEventListener('click', (event) => event.preventDefault()));
    </script>
    <script>
        document.getElementById('warrantyRequestForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;
            const response = await fetch('api.php?action=warranty_extension', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify(Object.fromEntries(new FormData(form))) });
            const result = await response.json();
            if (result.success) { const message = document.getElementById('warrantySuccess'); message.textContent = result.message; message.classList.add('show'); form.reset(); }
            else alert(result.message || 'The warranty request could not be submitted.');
            button.disabled = false;
        });
    </script>
</body>
</html>
