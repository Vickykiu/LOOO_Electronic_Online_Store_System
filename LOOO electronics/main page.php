<?php
// Check PHP version for compatibility
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die('This application requires PHP 5.4.0 or higher. Current version: ' . PHP_VERSION);
}

// Fallback function for htmlspecialchars if not available
if (!function_exists('htmlspecialchars')) {
    function htmlspecialchars($string, $flags = ENT_QUOTES, $encoding = 'UTF-8') {
        return htmlentities($string, $flags, $encoding);
    }
}

session_start();
require_once 'config.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';
$userInitials = 'JD';
if ($isLoggedIn && !empty($userName)) {
    $nameParts = explode(' ', $userName);
    $firstInitial = !empty($nameParts[0]) ? strtoupper(substr($nameParts[0], 0, 1)) : '';
    $lastInitial = !empty($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '';
    $userInitials = $firstInitial . $lastInitial;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="en">
    <title>LOOO - Electronics & Computers</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: white;
            color: black;
        }

        /* Header Styles */
		.header {
			padding: 20px 50px;
			position: relative;
		}

		.header-top {
			display: flex;
			justify-content: center;
			align-items: center;
			margin-bottom: 40px;
			position: relative;
		}

		.header-top::after {
			content: '';
			position: absolute;
			bottom: -20px;
			left: 0;
			width: 100vw;
			height: 2px;
			background: linear-gradient(90deg, #007bff, #00ff88, #ff6b6b, #007bff);
			background-size: 300% 100%;
			animation: rotateBorder 3s linear infinite;
			margin-left: calc(-50vw + 50%);
		}

		@keyframes rotateBorder {
			0% {
				background-position: 0% 50%;
			}
			100% {
				background-position: 300% 50%;
			}
		}

        .logo {
            position: absolute;
            left: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            justify-content: center;
        }

        .nav-item {
            position: relative;
        }

        .nav-links a {
            text-decoration: none;
            color: black;
            position: relative;
            display: inline-block;
        }

        .nav-links a::after {
            content: '▼';
            margin-left: 5px;
            font-size: 10px;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            min-width: 200px;
            max-width: 250px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            margin-top: 5px;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-links a:hover + .dropdown-menu,
        .dropdown-menu:hover {
            opacity: 1;
            visibility: visible;
        }

        .dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
        }

        .dropdown-menu a:hover {
            background: #f8f9fa;
            color: #007bff;
        }

        .dropdown-menu a::after {
            content: '›';
            float: right;
            color: #ccc;
        }

        .search-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
			padding-top: 20px;
        }

        .search-bar {
            width: 400px;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 16px;
        }

        .search-bar::placeholder {
            color: #999;
        }

        /* Updated Header Icons Section */
        .header-icons {
            position: absolute;
            right: 0;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon {
            font-size: 20px;
            cursor: pointer;
            color: black;
            text-decoration: none;
        }

        /* Login/Logout Button */
        .auth-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .login-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        /* Enhanced User Profile Styles */
        .user-profile {
            display: <?php echo $isLoggedIn ? 'flex' : 'none'; ?>;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 25px;
            background: rgba(0, 123, 255, 0.08);
            border: 1px solid rgba(0, 123, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
        }

        .user-profile:hover {
            background: rgba(0, 123, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }

        /* Profile Picture Frame */
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            overflow: hidden;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
            position: relative;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* User Info Section */
        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 120px;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 2px;
            white-space: nowrap;
        }

        .user-email {
            font-size: 11px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }

        /* Profile Status Indicator */
        .profile-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #28a745;
            border: 2px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Rest of your existing CSS styles would go here */
        /* I'm including the essential parts for the header and authentication */

        /* Hero Section */
        .hero {
            padding: 60px 20px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        .hero h1 {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #333;
        }

        .carousel {
            position: relative;
            max-width: 800px;
            margin: 0 auto 40px;
        }

        .carousel-container {
            display: flex;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .carousel-item {
            min-width: 100%;
            transition: transform 0.5s ease;
        }

        .carousel-item img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.9);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-nav.prev {
            left: 20px;
        }

        .carousel-nav.next {
            right: 20px;
        }

        .carousel-nav:hover {
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .quick-view-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .quick-view-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .pagination-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination-dot.active {
            background: #007bff;
            transform: scale(1.2);
        }

        /* Brand Logos */
        .brands {
            padding: 60px 20px;
            background: white;
        }

        .brand-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .brand-logo {
            height: 60px;
            width: auto;
            opacity: 0.7;
            transition: all 0.3s ease;
            filter: grayscale(100%);
        }

        .brand-logo:hover {
            opacity: 1;
            filter: grayscale(0%);
            transform: scale(1.1);
        }

        /* Product Section */
        .product-section {
            padding: 60px 20px;
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #333;
        }

        .new-arrivals-carousel {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }

        .new-arrivals-container {
            display: flex;
            gap: 30px;
            overflow-x: auto;
            padding: 20px 0;
            scroll-behavior: smooth;
        }

        .product-card {
            min-width: 300px;
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
        }

        .add-to-cart-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .add-to-cart-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 40px 20px 20px;
            text-align: center;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #555;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .social-icon:hover {
            background: #007bff;
            transform: translateY(-3px);
        }

        .social-icon img {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }

        .footer-bottom {
            border-top: 1px solid #555;
            padding-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 20px;
            }
            
            .nav-links {
                gap: 20px;
            }
            
            .search-bar {
                width: 300px;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .brand-logos {
                gap: 20px;
            }
            
            .brand-logo {
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="logo">
                <img src="logo.png" alt="LOOO Logo">
            </div>
            <nav class="nav-links">
                <div class="nav-item">
                    <a href="Products.html">Products</a>
                    <div class="dropdown-menu">
                        <a href="Products.html?brand=Acer">Acer</a>
                        <a href="Products.html?brand=Asus">Asus</a>
                        <a href="Products.html?brand=Dell">Dell</a>
                        <a href="Products.html?brand=HP">HP</a>
                        <a href="Products.html?brand=Lenovo">Lenovo</a>
                        <a href="Products.html?brand=MSI">MSI</a>
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
                        <a href="Register Products Services.html">Register Products & Services</a>
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
                <div class="auth-section">
                    <?php if (!$isLoggedIn) { ?>
                        <!-- Login Button (shown when not logged in) -->
                        <button class="login-btn" onclick="handleLogin()">Login / Sign in</button>
                    <?php } else { ?>
                        <!-- User Profile Section (shown when logged in) -->
                        <div class="user-profile" onclick="goToProfile()">
                            <div class="user-avatar">
                                <?php echo $userInitials; ?>
                                <div class="profile-status"></div>
                            </div>
                            <div class="user-info">
                                <div class="user-name"><?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>
                        
                        <!-- Logout Button (shown when logged in) -->
                        <button class="logout-btn" onclick="handleLogout()">Logout</button>
                    <?php } ?>
                </div>
                <a class="icon" href="rewards.html" aria-label="Rewards">🎁</a>
                <a class="icon" href="Cart.html" aria-label="Cart">🛒</a>
            </div>
        </div>

        <div class="search-section">
            <input type="text" class="search-bar" placeholder="What are you looking for?">
        </div>
</header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Our Latest Deals</h1>
        <div class="carousel">
            <button class="carousel-nav prev">‹</button>
            <div class="carousel-container">
                <div class="carousel-item">
				    <img src="asus rog strix 18.png" 
							alt="Laptop 1 Image" 
							style="width: 100%; height: 100%; object-fit: contain; display: block; margin-bottom: 10px;"/>
					</div>
                <div class="carousel-item active">
                    <img src="Group 9.png"
							alt="Laptop 2 Image" 
							style="width: 100%; height: 100%; object-fit: contain; display: block; margin-bottom: 10px;"/>
					</div>
                <div class="carousel-item">
					<img src="asus zenbook duo 14.png"
							alt="Laptop 3 Image" 
							style="width: 100%; height: 100%; object-fit: contain; display: block; margin-bottom: 10px;"/>
				    </div>
				</div>
            <button class="carousel-nav next">›</button>
        </div>
        <button class="quick-view-btn">Quick View</button>
        <div class="pagination">
            <div class="pagination-dot"></div>
            <div class="pagination-dot active"></div>
            <div class="pagination-dot"></div>
        </div>
    </section>

    <!-- Brand Logos -->
    <section class="brands">
        <div class="brand-logos">
            <img src="lenovo.png" alt="Lenovo" class="brand-logo">
            <img src="microsoft.png" alt="Microsoft" class="brand-logo">
            <img src="asus.png" alt="ASUS" class="brand-logo">
            <img src="acer.png" alt="Acer" class="brand-logo">
            <img src="huawei.png" alt="Huawei" class="brand-logo">
            <img src="dell.png" alt="Dell" class="brand-logo">
            <img src="hp.png" alt="Hp" class="brand-logo">
            <img src="msi.png" alt="Msi" class="brand-logo">
            
            <img src="lenovo.png" class="brand-logo">
            <img src="microsoft.png" class="brand-logo">
            <img src="asus.png" class="brand-logo">
            <img src="acer.png" class="brand-logo">
            <img src="huawei.png" class="brand-logo">
            <img src="dell.png" class="brand-logo">
            <img src="hp.png" class="brand-logo">
            <img src="msi.png" class="brand-logo">
        </div>
    </section>

    <!-- New Arrivals with Carousel Navigation -->
    <section class="product-section" style="background-color: #f8f9fa;">
        <h2 class="section-title">New Arrivals</h2>
        <div class="new-arrivals-carousel">
            <div class="new-arrivals-container" id="newArrivalsContainer">
                <div class="product-card" data-product-id="10028">
                    <img src="asus rog strix 18.png" alt="ASUS ROG Strix 18" class="product-image">
                    <h3 class="product-title">ASUS ROG Strix 18</h3>
                    <div class="product-price">RM 8,999</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
                <div class="product-card" data-product-id="10010">
                    <img src="lenovo yoga slim 7i.png" alt="Lenovo Yoga Slim 7i" class="product-image">
                    <h3 class="product-title">Lenovo Yoga Slim 7i</h3>
                    <div class="product-price">RM 4,599</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
                <div class="product-card" data-product-id="10018">
                    <img src="hp pavilion plus.png" alt="HP Pavilion Plus" class="product-image">
                    <h3 class="product-title">HP Pavilion Plus</h3>
                    <div class="product-price">RM 3,899</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
                <div class="product-card" data-product-id="10032">
                    <img src="acer swift go 14.png" alt="Acer Swift Go 14" class="product-image">
                    <h3 class="product-title">Acer Swift Go 14</h3>
                    <div class="product-price">RM 2,999</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const dots = document.querySelectorAll('.pagination-dot');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.transform = `translateX(-${index * 100}%)`;
            });
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        // Event listeners
        document.querySelector('.next').addEventListener('click', nextSlide);
        document.querySelector('.prev').addEventListener('click', prevSlide);

        // Auto-advance carousel
        setInterval(nextSlide, 5000);

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        // Initialize first slide
        showSlide(0);

        // Add to cart functionality
        const CART_KEY = 'loooCart';
        let cartItems;
        try {
            const savedCart = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
            cartItems = Array.isArray(savedCart) ? savedCart : [];
        } catch (error) {
            cartItems = [];
        }
        let cartCount = cartItems.reduce((count, item) => count + Number(item.quantity || 1), 0);
        const cartIcons = document.querySelectorAll('.header-icons .icon:last-of-type, .search-section .icon:last-of-type');
        
        function updateCartIcon() {
            cartCount = cartItems.reduce((count, item) => count + Number(item.quantity || 1), 0);
            cartIcons.forEach(icon => {
                icon.textContent = cartCount > 0 ? `🛒 (${cartCount})` : '🛒';
                icon.setAttribute('aria-label', cartCount ? `Cart with ${cartCount} items` : 'Cart');
            });
        }

        async function loadDatabaseCartCount() {
            const response = await fetch('api.php?action=session', { credentials: 'same-origin' });
            const result = await response.json();
            cartCount = Number(result.cart_count || 0);
            cartIcons.forEach(icon => {
                icon.innerHTML = cartCount > 0 ? `&#128722; (${cartCount})` : '&#128722;';
                icon.setAttribute('aria-label', cartCount ? `Cart with ${cartCount} items` : 'Cart');
            });
        }

        async function loadHomepageProducts() {
            const response = await fetch('api.php?action=products', { credentials: 'same-origin' });
            const result = await response.json();
            if (!result.success) return;
            const products = new Map(result.products.map((product) => [Number(product.id), product]));
            document.querySelectorAll('.product-card[data-product-id]').forEach((card) => {
                const product = products.get(Number(card.dataset.productId));
                if (!product) return;
                card.querySelector('.product-title').textContent = product.name;
                card.querySelector('.product-price').textContent = new Intl.NumberFormat('en-MY', { style: 'currency', currency: 'MYR' }).format(product.price).replace('MYR', 'RM');
                const button = card.querySelector('.add-to-cart-btn');
                button.disabled = Number(product.stock_quantity) < 1;
                button.textContent = button.disabled ? 'Out of Stock' : 'Add to Cart';
            });
        }

        // Initialize cart
        loadDatabaseCartCount().catch(() => updateCartIcon());
        loadHomepageProducts().catch(() => {});

        // Add to cart buttons
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const card = this.closest('.product-card');
                const response = await fetch('api.php?action=cart_add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ product_id: Number(card.dataset.productId), quantity: 1 })
                });
                const result = await response.json();
                if (result.login_required) {
                    window.location.href = 'Login.php?return=main%20page.php';
                    return;
                }
                if (!response.ok || !result.success) {
                    alert(result.message || 'Unable to add this product.');
                    return;
                }
                await loadDatabaseCartCount();
                this.textContent = 'Added!';
                this.style.background = '#28a745';
                
                setTimeout(() => {
                    this.textContent = 'Add to Cart';
                    this.style.background = '#007bff';
                }, 2000);
            });
        });

        function handleLogin() {
            // Redirect to login page
            window.location.href = 'Login.php';
        }

        function handleLogout() {
            // Redirect to logout script
            window.location.href = 'logout.php';
        }

        function goToProfile() {
            // Open profile page
            window.location.href = 'profile.php';
        }

        // Quick view button functionality
        document.querySelector('.quick-view-btn').addEventListener('click', function() {
            alert('Quick view functionality would open here!');
        });
    </script>

    <!-- Footer -->
    <footer class="footer">
        <div class="social-icons">
            <div class="social-icon">
                <img src="facebook.png" alt="Facebook">
            </div>
            <div class="social-icon">
                <img src="whatsapp.png" alt="WhatsApp">
            </div>
            <div class="social-icon">
                <img src="youtube.png" alt="YouTube">
            </div>
            <div class="social-icon">
                <img src="google.png" alt="Google">
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 LOOO. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
