<?php
declare(strict_types=1);
require_once 'config.php';

$productAliases = [
    'asus zenbook 14' => 10022,
    'acer swift go 14' => 10032,
    'dell inspiron 14' => 10042,
    'lenovo ideapad pro 5i' => 10000,
    'lenovo ideapad 5 pro' => 10000,
    'lenovo yoga slim 7i' => 10010,
    'hp probook 460' => 10013,
    'asus vivobook s14 m5406' => 10029,
    'asus vivobook s14 (m5406)' => 10029,
    'hp pavilion plus' => 10018,
    'lenovo thinkpad t14 gen 5 intel' => 10004,
    'lenovo thinkpad t14 gen 5 (14" intel)' => 10004,
    'lenovo thinkpad t14 gen 5' => 10004,
    'acer expertcenter p400 aio' => 10035,
    'asus expertcenter p400 aio' => 10035,
    'acer aspire s27-1755' => 10036,
    'lenovo ideapad aio i gen 9' => 10011,
    'lenovo ideacentre aio i (24", gen 9)' => 10011,
    'acer s1386wh dlp projector' => 10037,
    'lenovo thinkpad universal usb-c dock' => 10047,
    'prism+ x340 pro evo' => 10046,
    'asus rog strix 18' => 10028,
];

$productId = (int)($_GET['id'] ?? 0);
$requestedName = strtolower(trim((string)($_GET['product'] ?? '')));
if ($productId < 1 && isset($productAliases[$requestedName])) {
    $productId = $productAliases[$requestedName];
}

$product = null;
if ($productId > 0) {
    $statement = $pdo->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
    $statement->execute([$productId]);
    $product = $statement->fetch() ?: null;
} elseif ($requestedName !== '') {
    $statement = $pdo->prepare('SELECT * FROM products WHERE LOWER(name) = ? LIMIT 1');
    $statement->execute([$requestedName]);
    $product = $statement->fetch() ?: null;
}

$reviews = [];
$displayImage = 'logo.png';
if ($product) {
    $displayImage = (string)($product['image_url'] ?: 'logo.png');
    $imageAliases = [
        "lenovo thinkpad t14 gen 5 (14'' intel).png" => 'lenovo thinkpad t14 Gen 5 (14_ Intel).png',
    ];
    $displayImage = $imageAliases[strtolower($displayImage)] ?? $displayImage;
    if (!is_file(__DIR__ . DIRECTORY_SEPARATOR . $displayImage)) {
        $displayImage = 'logo.png';
    }
    $reviewStatement = $pdo->prepare('SELECT r.rating, r.comment, r.media_url, r.verified_purchase, r.created_at, CONCAT(u.first_name, \' \', u.last_name) AS reviewer FROM product_reviews r INNER JOIN users u ON u.id = r.user_id WHERE r.product_id = ? AND r.status = \'active\' ORDER BY r.created_at DESC');
    $reviewStatement->execute([$product['id']]);
    $reviews = $reviewStatement->fetchAll();
}

$pageTitle = $product ? $product['name'] . ' - LOOO' : 'Product Not Found - LOOO';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --blue: #007bff; --blue-dark: #0056b3; --ink: #111; --muted: #666; --line: #e9ecef; --surface: #f8f9fa; }
        body { min-height: 100vh; color: var(--ink); background: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        a { color: inherit; }
        .header { padding: 20px 50px; background: #fff; }
        .header-top { position: relative; display: flex; align-items: center; justify-content: center; min-height: 40px; margin-bottom: 22px; }
        .header-top::after { position: absolute; bottom: -20px; left: 0; width: 100%; height: 2px; content: ''; background: linear-gradient(90deg, #007bff, #00d98b, #ff6b6b, #007bff); }
        .logo { position: absolute; left: 0; display: flex; align-items: center; }
        .logo img { display: block; width: auto; height: 40px; }
        .nav-links { display: flex; gap: 30px; align-items: center; }
        .nav-item { position: relative; }
        .nav-item > a { display: inline-block; padding: 8px 0; color: #111; text-decoration: none; }
        .nav-item > a::after { margin-left: 5px; font-size: 10px; content: '\25BC'; }
        .dropdown-menu { position: absolute; z-index: 20; top: 100%; left: 0; min-width: 220px; margin-top: 4px; overflow: hidden; visibility: hidden; background: #fff; border: 1px solid var(--line); border-radius: 8px; box-shadow: 0 8px 22px rgba(0,0,0,.1); opacity: 0; transform: translateY(5px); transition: .2s ease; }
        .nav-item:hover .dropdown-menu, .nav-item:focus-within .dropdown-menu { visibility: visible; opacity: 1; transform: translateY(0); }
        .dropdown-menu a { display: block; padding: 12px 18px; color: #333; text-decoration: none; border-bottom: 1px solid #f3f3f3; }
        .dropdown-menu a:last-child { border-bottom: 0; }
        .dropdown-menu a:hover { color: var(--blue); background: var(--surface); }
        .header-icons { position: absolute; right: 0; display: flex; gap: 15px; align-items: center; }
        .login-btn { padding: 9px 16px; color: #fff; background: var(--blue); border-radius: 5px; text-decoration: none; font-weight: 600; }
        .icon-link { color: #222; text-decoration: none; font-size: 20px; }
        .page { width: min(1180px, calc(100% - 40px)); margin: 0 auto; padding: 52px 0 70px; }
        .breadcrumb { display: flex; gap: 9px; align-items: center; margin-bottom: 28px; color: var(--muted); font-size: 14px; }
        .breadcrumb a { color: var(--blue); text-decoration: none; }
        .detail-card { display: grid; grid-template-columns: minmax(320px, 1fr) minmax(320px, 1fr); gap: 64px; align-items: center; padding: 48px; background: #fff; border: 1px solid var(--line); border-radius: 20px; box-shadow: 0 14px 40px rgba(20,40,80,.08); }
        .product-image { display: flex; align-items: center; justify-content: center; min-height: 430px; padding: 36px; background: var(--surface); border-radius: 16px; }
        .product-image img { width: 100%; max-width: 470px; max-height: 380px; object-fit: contain; }
        .eyebrow { margin-bottom: 12px; color: var(--blue); font-size: 14px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        h1 { margin-bottom: 18px; font-size: clamp(34px, 4vw, 52px); line-height: 1.08; }
        .price { margin-bottom: 24px; color: var(--blue); font-size: 32px; font-weight: 800; }
        .description { margin-bottom: 24px; color: #555; font-size: 17px; line-height: 1.75; }
        .features { display: grid; gap: 10px; margin: 0 0 30px; padding: 0; list-style: none; color: #333; }
        .features li::before { margin-right: 10px; color: #00a66a; content: '\2713'; font-weight: 800; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .primary-btn, .secondary-btn { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; padding: 12px 24px; border-radius: 7px; font: inherit; font-weight: 700; text-decoration: none; cursor: pointer; }
        .primary-btn { color: #fff; background: var(--blue); border: 1px solid var(--blue); }
        .primary-btn:hover { background: var(--blue-dark); }
        .secondary-btn { color: #222; background: #fff; border: 1px solid #ccc; }
        .secondary-btn:hover { background: var(--surface); }
        .not-found { padding: 80px 30px; text-align: center; background: var(--surface); border-radius: 18px; }
        .not-found h1 { margin-bottom: 14px; }
        .not-found p { margin-bottom: 28px; color: var(--muted); }
        .reviews-section { margin-top: 42px; padding: 36px; background: var(--surface); border-radius: 18px; }
        .reviews-heading { display: flex; justify-content: space-between; gap: 20px; align-items: end; margin-bottom: 24px; }
        .reviews-heading h2 { margin-bottom: 6px; font-size: 30px; }
        .reviews-heading p { color: var(--muted); }
        .reviews-grid { display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 28px; align-items: start; }
        .review-list { display: grid; gap: 14px; }
        .review-card, .review-form { padding: 20px; background: #fff; border: 1px solid var(--line); border-radius: 12px; }
        .review-meta { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 10px; }
        .stars { color: #f2a900; letter-spacing: 2px; }
        .verified { margin-top: 8px; color: #198754; font-size: 13px; font-weight: 700; }
        .review-form { display: grid; gap: 12px; }
        .review-form label { display: grid; gap: 7px; font-weight: 700; }
        .review-form input, .review-form select, .review-form textarea { width: 100%; padding: 11px 12px; border: 1px solid #ccc; border-radius: 7px; font: inherit; }
        .review-form textarea { min-height: 120px; resize: vertical; }
        .review-feedback { display: none; padding: 11px; border-radius: 7px; }
        @media (max-width: 900px) {
            .header { padding: 18px 24px; }
            .header-top { justify-content: space-between; margin-bottom: 20px; }
            .logo, .header-icons { position: static; }
            .nav-links { display: none; }
            .detail-card { grid-template-columns: 1fr; gap: 34px; padding: 28px; }
            .product-image { min-height: 320px; }
            .reviews-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 520px) {
            .header-icons .icon-link:first-of-type { display: none; }
            .page { width: min(100% - 24px, 1180px); padding-top: 34px; }
            .detail-card { padding: 18px; border-radius: 14px; }
            .product-image { min-height: 260px; padding: 20px; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-top">
            <a class="logo" href="main page.php" aria-label="LOOO home"><img src="logo.png" alt="LOOO Logo"></a>
            <nav class="nav-links" aria-label="Main navigation">
                <div class="nav-item">
                    <a href="Products.html">Products</a>
                    <div class="dropdown-menu">
                        <a href="Products.html?brand=Acer">Acer</a>
                        <a href="Products.html?brand=Asus">Asus</a>
                        <a href="Products.html?brand=Dell">Dell</a>
                        <a href="Products.html?brand=HP">HP</a>
                        <a href="Products.html?brand=Lenovo">Lenovo</a>
                    </div>
                </div>
                <div class="nav-item">
                    <a href="Customer Services.php">Services</a>
                    <div class="dropdown-menu">
                        <a href="Customer Services.php">Customer Services</a>
                        <a href="Support Services.html">Support Services</a>
                    </div>
                </div>
                <div class="nav-item">
                    <a href="Technical page.html">Support</a>
                    <div class="dropdown-menu">
                        <a href="Technical page.html">Technical Support</a>
                        <a href="Extend Device Warranty.php">Extend Device Warranty</a>
                        <a href="Register Products Services.html">Register Products &amp; Services</a>
                    </div>
                </div>
                <div class="nav-item">
                    <a href="rewards.html">Deals</a>
                    <div class="dropdown-menu">
                        <a href="rewards.html">My Rewards</a>
                        <a href="Student Discounts.html">Student Discounts</a>
                    </div>
                </div>
            </nav>
            <div class="header-icons">
                <a class="login-btn" href="Login.php">Login / Sign in</a>
                <a class="icon-link" href="rewards.html" aria-label="Rewards">&#127873;</a>
                <a class="icon-link" id="cartLink" href="Cart.html" aria-label="Cart">&#128722;</a>
            </div>
        </div>
    </header>

    <main class="page">
        <?php if ($product): ?>
            <div class="breadcrumb">
                <a href="main page.php">Home</a><span aria-hidden="true">›</span>
                <a href="Products.html">Products</a><span aria-hidden="true">›</span>
                <span><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <article class="detail-card">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($displayImage, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="product-info">
                    <p class="eyebrow"><?php echo htmlspecialchars($product['brand'] . ' · ' . $product['category'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <h1><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="price">RM <?php echo number_format((float)$product['price'], 2); ?></p>
                    <p class="description"><?php echo nl2br(htmlspecialchars((string)($product['description'] ?: 'Product details are available from LOOO customer support.'), ENT_QUOTES, 'UTF-8')); ?></p>
                    <ul class="features">
                        <li><?php echo (int)$product['stock_quantity']; ?> unit<?php echo (int)$product['stock_quantity'] === 1 ? '' : 's'; ?> currently in stock</li>
                        <li><?php echo (int)$product['is_new_arrival'] ? 'New arrival' : 'Available in the LOOO catalog'; ?></li>
                        <li><?php echo (int)$product['is_best_selling'] ? 'Best-selling selection' : 'Eligible for LOOO customer and technical support'; ?></li>
                    </ul>
                    <div class="actions">
                        <button class="primary-btn" id="addToCart" type="button" <?php echo (int)$product['stock_quantity'] < 1 ? 'disabled' : ''; ?>><?php echo (int)$product['stock_quantity'] < 1 ? 'Out of Stock' : 'Add to Cart'; ?></button>
                        <a class="secondary-btn" href="Products.html">Back to Products</a>
                    </div>
                </div>
            </article>
            <section class="reviews-section" aria-labelledby="reviewsTitle">
                <div class="reviews-heading"><div><h2 id="reviewsTitle">Customer reviews</h2><p><?php echo count($reviews); ?> review<?php echo count($reviews) === 1 ? '' : 's'; ?> for this product.</p></div></div>
                <div class="reviews-grid">
                    <div class="review-list" id="reviewList">
                        <?php if ($reviews): ?>
                            <?php foreach ($reviews as $review): ?>
                                <article class="review-card">
                                    <div class="review-meta"><strong><?php echo htmlspecialchars($review['reviewer'], ENT_QUOTES, 'UTF-8'); ?></strong><span class="stars" aria-label="<?php echo (int)$review['rating']; ?> out of 5 stars"><?php echo str_repeat('★', (int)$review['rating']) . str_repeat('☆', 5 - (int)$review['rating']); ?></span></div>
                                    <p><?php echo nl2br(htmlspecialchars($review['comment'], ENT_QUOTES, 'UTF-8')); ?></p>
                                    <?php if ((int)$review['verified_purchase']): ?><div class="verified">Verified purchase</div><?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <article class="review-card"><p>No reviews yet. Be the first to share your experience.</p></article>
                        <?php endif; ?>
                    </div>
                    <form class="review-form" id="reviewForm">
                        <h3>Write a review</h3>
                        <label>Rating<select name="rating" required><option value="">Choose a rating</option><option value="5">5 - Excellent</option><option value="4">4 - Very good</option><option value="3">3 - Good</option><option value="2">2 - Fair</option><option value="1">1 - Poor</option></select></label>
                        <label>Review<textarea name="comment" minlength="10" required></textarea></label>
                        <label>Media URL (optional)<input name="media_url" type="url" placeholder="https://"></label>
                        <button class="primary-btn" type="submit">Submit Review</button>
                        <div class="review-feedback" id="reviewFeedback" role="status"></div>
                    </form>
                </div>
            </section>
        <?php else: ?>
            <section class="not-found">
                <h1>Product not found</h1>
                <p>The selected item is unavailable. Please return to the product list and choose another item.</p>
                <a class="primary-btn" href="Products.html">Browse Products</a>
            </section>
        <?php endif; ?>
    </main>

    <script>
        async function updateCartLink() {
            const response = await fetch('api.php?action=session', { credentials: 'same-origin' });
            const result = await response.json();
            const count = Number(result.cart_count || 0);
            const cartLink = document.getElementById('cartLink');
            cartLink.innerHTML = count ? `&#128722; (${count})` : '&#128722;';
            cartLink.setAttribute('aria-label', count ? `Cart with ${count} item${count === 1 ? '' : 's'}` : 'Cart');
        }

        <?php if ($product): ?>
        const product = <?php echo json_encode([
            'id' => (int)$product['id'],
            'name' => $product['name'],
            'price' => (float)$product['price'],
            'image' => $displayImage
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;

        const addToCartButton = document.getElementById('addToCart');
        if (!addToCartButton.disabled) addToCartButton.addEventListener('click', async () => {
            const response = await fetch('api.php?action=cart_add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ product_id: product.id, quantity: 1 })
            });
            const result = await response.json();
            if (result.login_required) {
                window.location.href = `Login.php?return=${encodeURIComponent(window.location.pathname + window.location.search)}`;
                return;
            }
            if (!response.ok || !result.success) {
                alert(result.message || 'Unable to add this product.');
                return;
            }
            window.location.href = 'Cart.html';
        });

        document.getElementById('reviewForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const feedback = document.getElementById('reviewFeedback');
            const data = Object.fromEntries(new FormData(form));
            data.product_id = product.id;
            const response = await fetch('api.php?action=review_add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.login_required) {
                window.location.href = `Login.php?return=${encodeURIComponent(window.location.pathname + window.location.search)}`;
                return;
            }
            feedback.textContent = result.message;
            feedback.style.display = 'block';
            feedback.style.background = result.success ? '#d1e7dd' : '#f8d7da';
            feedback.style.color = result.success ? '#0f5132' : '#842029';
            if (result.success) setTimeout(() => window.location.reload(), 700);
        });
        <?php endif; ?>

        updateCartLink().catch(() => {});
    </script>
</body>
</html>
