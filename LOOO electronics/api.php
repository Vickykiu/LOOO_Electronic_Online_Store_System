<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function request_data(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $decoded = json_decode(file_get_contents('php://input'), true);
        return is_array($decoded) ? $decoded : [];
    }
    return $_POST;
}

function require_method(string $method): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== $method) {
        respond(['success' => false, 'message' => 'Invalid request method.'], 405);
    }
}

function require_user_id(): int
{
    $userId = (int)($_SESSION['user_id'] ?? 0);
    if ($userId < 1) {
        respond(['success' => false, 'message' => 'Please sign in to continue.', 'login_required' => true], 401);
    }
    return $userId;
}

function required_text(array $data, string $key, string $label, int $maxLength = 1000): string
{
    $value = trim((string)($data[$key] ?? ''));
    if ($value === '') {
        throw new InvalidArgumentException($label . ' is required.');
    }
    if (strlen($value) > $maxLength) {
        throw new InvalidArgumentException($label . ' is too long.');
    }
    return $value;
}

function optional_text(array $data, string $key, int $maxLength = 1000): ?string
{
    $value = trim((string)($data[$key] ?? ''));
    if ($value === '') {
        return null;
    }
    if (strlen($value) > $maxLength) {
        throw new InvalidArgumentException('One of the supplied values is too long.');
    }
    return $value;
}

function valid_email(string $email): string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Please enter a valid email address.');
    }
    return $email;
}

function cart_rows(PDO $pdo, int $userId): array
{
    $statement = $pdo->prepare(
        'SELECT c.id AS cart_id, c.product_id, c.quantity, p.name, p.description, p.price, p.image_url, p.category, p.brand, p.stock_quantity
         FROM cart c
         INNER JOIN products p ON p.id = c.product_id
         WHERE c.user_id = ?
         ORDER BY c.created_at ASC, c.id ASC'
    );
    $statement->execute([$userId]);
    $rows = $statement->fetchAll();
    foreach ($rows as &$row) {
        $row['cart_id'] = (int)$row['cart_id'];
        $row['product_id'] = (int)$row['product_id'];
        $row['quantity'] = (int)$row['quantity'];
        $row['price'] = (float)$row['price'];
        $row['stock_quantity'] = (int)$row['stock_quantity'];
    }
    unset($row);
    return $rows;
}

function cart_subtotal(array $items): float
{
    return array_reduce($items, static fn(float $total, array $item): float => $total + ((float)$item['price'] * (int)$item['quantity']), 0.0);
}

function voucher_result(PDO $pdo, int $userId, string $code, array $items): array
{
    $statement = $pdo->prepare('SELECT * FROM vouchers WHERE code = ? LIMIT 1');
    $statement->execute([strtoupper(trim($code))]);
    $voucher = $statement->fetch();
    if (!$voucher) {
        throw new InvalidArgumentException('Voucher code was not found.');
    }

    $today = date('Y-m-d');
    if ($voucher['status'] !== 'active' || $voucher['start_date'] > $today || $voucher['end_date'] < $today) {
        throw new InvalidArgumentException('This voucher is not currently active.');
    }
    if ((int)$voucher['current_usage'] >= (int)$voucher['max_usage']) {
        throw new InvalidArgumentException('This voucher has reached its usage limit.');
    }

    $usedStatement = $pdo->prepare('SELECT id FROM user_voucher_usage WHERE user_id = ? AND voucher_id = ? LIMIT 1');
    $usedStatement->execute([$userId, $voucher['id']]);
    if ($usedStatement->fetch()) {
        throw new InvalidArgumentException('You have already used this voucher.');
    }

    $subtotal = cart_subtotal($items);
    if ($subtotal < (float)$voucher['min_purchase']) {
        throw new InvalidArgumentException('This order does not meet the voucher minimum purchase.');
    }

    $eligibleSubtotal = $subtotal;
    $categories = json_decode((string)($voucher['categories'] ?? ''), true);
    if (is_array($categories) && $categories !== []) {
        $allowed = array_map(static fn($value): string => strtolower(rtrim(trim((string)$value), 's')), $categories);
        if (!in_array('all', $allowed, true)) {
            $eligibleSubtotal = 0.0;
            foreach ($items as $item) {
                $category = strtolower(rtrim(trim((string)($item['category'] ?? '')), 's'));
                if (in_array($category, $allowed, true)) {
                    $eligibleSubtotal += (float)$item['price'] * (int)$item['quantity'];
                }
            }
        }
    }
    if ($eligibleSubtotal <= 0) {
        throw new InvalidArgumentException('This voucher does not apply to the products in your cart.');
    }

    if ($voucher['discount_type'] === 'percentage') {
        $discount = $eligibleSubtotal * ((float)$voucher['discount_value'] / 100);
    } else {
        $discount = min($eligibleSubtotal, (float)$voucher['discount_value']);
    }
    if ($voucher['max_discount'] !== null) {
        $discount = min($discount, (float)$voucher['max_discount']);
    }

    return [
        'voucher' => $voucher,
        'discount' => round($discount, 2),
    ];
}

$action = trim((string)($_GET['action'] ?? ''));

try {
    switch ($action) {
        case 'session':
            $userId = (int)($_SESSION['user_id'] ?? 0);
            $user = null;
            $cartCount = 0;
            if ($userId > 0) {
                $statement = $pdo->prepare('SELECT id, first_name, last_name, email FROM users WHERE id = ?');
                $statement->execute([$userId]);
                $user = $statement->fetch() ?: null;
                $countStatement = $pdo->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?');
                $countStatement->execute([$userId]);
                $cartCount = (int)$countStatement->fetchColumn();
            }
            respond(['success' => true, 'user' => $user, 'cart_count' => $cartCount]);

        case 'products':
            $statement = $pdo->query('SELECT id, name, description, price, image_url, category_id, category, brand, stock_quantity, is_featured, is_new_arrival, is_best_selling, created_at, updated_at FROM products ORDER BY is_featured DESC, is_new_arrival DESC, name ASC');
            $products = $statement->fetchAll();
            foreach ($products as &$product) {
                $product['id'] = (int)$product['id'];
                $product['price'] = (float)$product['price'];
                $product['stock_quantity'] = (int)$product['stock_quantity'];
                $product['is_featured'] = (bool)$product['is_featured'];
                $product['is_new_arrival'] = (bool)$product['is_new_arrival'];
                $product['is_best_selling'] = (bool)$product['is_best_selling'];
            }
            unset($product);
            respond(['success' => true, 'products' => $products]);

        case 'cart':
            $userId = require_user_id();
            $items = cart_rows($pdo, $userId);
            $subtotal = cart_subtotal($items);
            $shipping = $subtotal > 0 && $subtotal < 500 ? 25.0 : 0.0;
            respond(['success' => true, 'items' => $items, 'subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $subtotal + $shipping]);

        case 'cart_add':
            require_method('POST');
            $userId = require_user_id();
            $data = request_data();
            $productId = (int)($data['product_id'] ?? 0);
            $quantity = max(1, min(99, (int)($data['quantity'] ?? 1)));
            $productStatement = $pdo->prepare('SELECT id, stock_quantity FROM products WHERE id = ? LIMIT 1');
            $productStatement->execute([$productId]);
            $product = $productStatement->fetch();
            if (!$product || (int)$product['stock_quantity'] < 1) {
                respond(['success' => false, 'message' => 'This product is unavailable.'], 404);
            }
            $quantity = min($quantity, (int)$product['stock_quantity']);
            $cartStatement = $pdo->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? ORDER BY id LIMIT 1');
            $cartStatement->execute([$userId, $productId]);
            $cartItem = $cartStatement->fetch();
            if ($cartItem) {
                $newQuantity = min(99, (int)$product['stock_quantity'], (int)$cartItem['quantity'] + $quantity);
                $update = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?');
                $update->execute([$newQuantity, $cartItem['id'], $userId]);
            } else {
                $insert = $pdo->prepare('INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)');
                $insert->execute([$userId, $productId, $quantity]);
            }
            $count = $pdo->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?');
            $count->execute([$userId]);
            respond(['success' => true, 'message' => 'Product added to cart.', 'cart_count' => (int)$count->fetchColumn()]);

        case 'cart_update':
            require_method('POST');
            $userId = require_user_id();
            $data = request_data();
            $cartId = (int)($data['cart_id'] ?? 0);
            $quantity = max(1, min(99, (int)($data['quantity'] ?? 1)));
            $statement = $pdo->prepare('UPDATE cart c INNER JOIN products p ON p.id = c.product_id SET c.quantity = LEAST(?, p.stock_quantity) WHERE c.id = ? AND c.user_id = ?');
            $statement->execute([$quantity, $cartId, $userId]);
            respond(['success' => true]);

        case 'cart_remove':
            require_method('POST');
            $userId = require_user_id();
            $data = request_data();
            $statement = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
            $statement->execute([(int)($data['cart_id'] ?? 0), $userId]);
            respond(['success' => true]);

        case 'profile':
            $userId = require_user_id();
            if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
                $statement = $pdo->prepare('SELECT id, first_name, last_name, email, phone, birth_date, address, city, postal_code, avatar_url, gender, default_avatar, created_at, updated_at FROM users WHERE id = ?');
                $statement->execute([$userId]);
                respond(['success' => true, 'user' => $statement->fetch()]);
            }
            require_method('POST');
            $data = request_data();
            $firstName = required_text($data, 'first_name', 'First name', 50);
            $lastName = required_text($data, 'last_name', 'Last name', 50);
            $email = valid_email(required_text($data, 'email', 'Email', 100));
            $gender = optional_text($data, 'gender', 10);
            if ($gender !== null && !in_array($gender, ['male', 'female'], true)) {
                throw new InvalidArgumentException('Please choose a valid gender.');
            }
            $statement = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, birth_date = ?, address = ?, city = ?, postal_code = ?, avatar_url = ?, gender = ?, default_avatar = ? WHERE id = ?');
            $statement->execute([
                $firstName,
                $lastName,
                $email,
                optional_text($data, 'phone', 20),
                optional_text($data, 'birth_date', 10),
                optional_text($data, 'address', 5000),
                optional_text($data, 'city', 50),
                optional_text($data, 'postal_code', 10),
                optional_text($data, 'avatar_url', 255),
                $gender,
                optional_text($data, 'default_avatar', 255),
                $userId,
            ]);
            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
            $_SESSION['user_email'] = $email;
            respond(['success' => true, 'message' => 'Profile updated successfully.']);

        case 'orders':
            $userId = require_user_id();
            $statement = $pdo->prepare('SELECT id, order_number, total_amount, shipping_address, payment_method, payment_status, status, notes, created_at, updated_at FROM orders WHERE user_id = ? ORDER BY created_at DESC');
            $statement->execute([$userId]);
            respond(['success' => true, 'orders' => $statement->fetchAll()]);

        case 'order':
            $userId = require_user_id();
            $orderNumber = trim((string)($_GET['order_id'] ?? $_GET['order_number'] ?? ''));
            $statement = $pdo->prepare('SELECT id, order_number, total_amount, shipping_address, payment_method, payment_status, status, notes, created_at, updated_at FROM orders WHERE user_id = ? AND order_number = ? LIMIT 1');
            $statement->execute([$userId, $orderNumber]);
            $order = $statement->fetch();
            if (!$order) {
                respond(['success' => false, 'message' => 'Order not found.'], 404);
            }
            $itemStatement = $pdo->prepare('SELECT oi.product_id, oi.quantity, oi.price, p.name, p.image_url FROM order_items oi INNER JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ? ORDER BY oi.id');
            $itemStatement->execute([$order['id']]);
            $order['items'] = $itemStatement->fetchAll();
            respond(['success' => true, 'order' => $order]);

        case 'contact':
            require_method('POST');
            $data = request_data();
            $name = required_text($data, 'customerName', 'Name', 255);
            $email = valid_email(required_text($data, 'customerEmail', 'Email', 255));
            $subject = required_text($data, 'helpTopic', 'Help topic', 100);
            $message = required_text($data, 'customerMessage', 'Message', 10000);
            $orderNumber = optional_text($data, 'orderNumber', 50);
            if ($orderNumber !== null) {
                $message = "Order number: {$orderNumber}\n\n{$message}";
            }
            $statement = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message, status, priority) VALUES (?, ?, ?, ?, \'new\', \'medium\')');
            $statement->execute([$name, $email, $subject, $message]);
            respond(['success' => true, 'message' => 'Your customer service request was submitted.']);

        case 'technical_support':
            require_method('POST');
            $data = request_data();
            $name = trim((string)($data['name'] ?? $data['supportName'] ?? ''));
            $email = trim((string)($data['email'] ?? $data['supportEmail'] ?? ''));
            $phone = trim((string)($data['phone'] ?? $data['supportPhone'] ?? 'Not provided'));
            $organization = trim((string)($data['organization'] ?? $data['supportOrganization'] ?? 'Individual customer'));
            $subject = trim((string)($data['subject'] ?? ''));
            $description = trim((string)($data['description'] ?? $data['supportIssue'] ?? ''));
            if ($subject === '') {
                $brand = trim((string)($data['deviceBrand'] ?? 'Device'));
                $model = trim((string)($data['deviceModel'] ?? ''));
                $subject = trim($brand . ' ' . $model . ' support request');
            }
            if ($name === '' || $description === '') {
                throw new InvalidArgumentException('Name and issue description are required.');
            }
            valid_email($email);
            $statement = $pdo->prepare('INSERT INTO technical_support (name, email, phone, organization, subject, description, status, priority) VALUES (?, ?, ?, ?, ?, ?, \'pending\', \'medium\')');
            $statement->execute([$name, $email, $phone, $organization, $subject, $description]);
            respond(['success' => true, 'message' => 'Your technical support request was submitted.']);

        case 'product_registration':
            require_method('POST');
            $data = request_data();
            $model = required_text($data, 'registerModel', 'Product model', 255);
            $brand = required_text($data, 'registerBrand', 'Brand', 100);
            $additional = json_encode([
                'brand' => $brand,
                'category' => optional_text($data, 'registerCategory', 100),
                'invoice_number' => optional_text($data, 'invoiceNumber', 100),
                'notes' => optional_text($data, 'registrationNotes', 5000),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $warrantyType = trim((string)($data['warrantyType'] ?? 'basic'));
            if (!in_array($warrantyType, ['basic', 'extended', 'premium'], true)) {
                $warrantyType = 'basic';
            }
            $statement = $pdo->prepare('INSERT INTO product_registrations (product_name, serial_number, purchase_date, customer_name, email, phone, warranty_type, additional_services, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, \'active\')');
            $statement->execute([
                $brand . ' ' . $model,
                required_text($data, 'registerSerial', 'Serial number', 100),
                required_text($data, 'purchaseDate', 'Purchase date', 10),
                required_text($data, 'registerName', 'Owner name', 255),
                valid_email(required_text($data, 'registerEmail', 'Email', 255)),
                optional_text($data, 'registerPhone', 20),
                $warrantyType,
                $additional,
            ]);
            respond(['success' => true, 'message' => 'Your product was registered successfully.']);

        case 'student_application':
            require_method('POST');
            $data = request_data();
            $firstName = trim((string)($data['firstName'] ?? ''));
            $lastName = trim((string)($data['lastName'] ?? ''));
            if ($firstName === '' && isset($data['studentName'])) {
                $parts = preg_split('/\s+/', trim((string)$data['studentName']), 2);
                $firstName = $parts[0] ?? '';
                $lastName = $parts[1] ?? '-';
            }
            if ($firstName === '' || $lastName === '') {
                throw new InvalidArgumentException('First and last name are required.');
            }
            $course = trim((string)($data['course'] ?? $data['studyLevel'] ?? ''));
            $studyUse = optional_text($data, 'studyUse', 2000);
            if ($studyUse !== null) {
                $course .= ' - ' . $studyUse;
            }
            $statement = $pdo->prepare('INSERT INTO student_applications (first_name, last_name, email, phone, institution, student_id, course, graduation_year, address, city, postal_code, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'pending\')');
            $statement->execute([
                $firstName,
                $lastName,
                valid_email(required_text($data, 'studentEmail', 'Student email', 255)),
                optional_text($data, 'studentPhone', 50),
                required_text($data, 'institution', 'Institution', 255),
                required_text($data, 'studentId', 'Student ID', 100),
                $course !== '' ? $course : required_text($data, 'course', 'Course', 255),
                optional_text($data, 'graduationYear', 4),
                optional_text($data, 'studentAddress', 5000),
                optional_text($data, 'studentCity', 100),
                optional_text($data, 'studentPostalCode', 20),
            ]);
            respond(['success' => true, 'message' => 'Your student discount application was submitted.']);

        case 'warranty_extension':
            require_method('POST');
            $data = request_data();
            $deviceType = trim((string)($data['deviceType'] ?? 'Laptop'));
            if (!in_array($deviceType, ['Laptop', 'Desktop'], true)) {
                throw new InvalidArgumentException('Please choose a valid device type.');
            }
            $term = trim((string)($data['warrantyTerm'] ?? ''));
            $plans = [
                '1 year' => ['basic', 199.00],
                '2 years' => ['extended', 349.00],
                '3 years' => ['premium', 499.00],
            ];
            if (!isset($plans[$term])) {
                throw new InvalidArgumentException('Please choose a warranty plan.');
            }
            $currentWarranty = trim((string)($data['currentWarranty'] ?? 'Standard (1 Year)'));
            $allowedWarranties = ['Standard (1 Year)', 'Extended (2 Years)', 'Premium (3 Years)', 'Expired'];
            if (!in_array($currentWarranty, $allowedWarranties, true)) {
                $currentWarranty = 'Standard (1 Year)';
            }
            $model = required_text($data, 'warrantyModel', 'Model', 200);
            $serial = optional_text($data, 'warrantySerial', 100);
            if ($serial !== null) {
                $model .= ' (SN: ' . $serial . ')';
            }
            $statement = $pdo->prepare('INSERT INTO warranty_extensions (device_type, brand, model, purchase_date, current_warranty, customer_name, email, phone, selected_plan, plan_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'pending\')');
            $statement->execute([
                $deviceType,
                required_text($data, 'warrantyBrand', 'Brand', 100),
                $model,
                required_text($data, 'warrantyPurchaseDate', 'Purchase date', 10),
                $currentWarranty,
                required_text($data, 'warrantyName', 'Customer name', 255),
                valid_email(required_text($data, 'warrantyEmail', 'Email', 255)),
                optional_text($data, 'warrantyPhone', 20),
                $plans[$term][0],
                $plans[$term][1],
            ]);
            respond(['success' => true, 'message' => 'Your warranty extension request was submitted.']);

        case 'vouchers':
            $statement = $pdo->query('SELECT id, code, description, discount_type, discount_value, max_discount, min_purchase, max_usage, current_usage, start_date, end_date, categories, status, created_at, updated_at FROM vouchers ORDER BY status = \'active\' DESC, end_date ASC');
            $vouchers = $statement->fetchAll();
            foreach ($vouchers as &$voucher) {
                $voucher['id'] = (int)$voucher['id'];
                $voucher['discount_value'] = (float)$voucher['discount_value'];
                $voucher['max_discount'] = $voucher['max_discount'] === null ? null : (float)$voucher['max_discount'];
                $voucher['min_purchase'] = (float)$voucher['min_purchase'];
                $voucher['max_usage'] = (int)$voucher['max_usage'];
                $voucher['current_usage'] = (int)$voucher['current_usage'];
                $voucher['categories'] = json_decode((string)($voucher['categories'] ?? ''), true) ?: ['all'];
            }
            unset($voucher);
            respond(['success' => true, 'vouchers' => $vouchers]);

        case 'voucher_preview':
            $userId = require_user_id();
            $code = trim((string)($_GET['code'] ?? ''));
            $items = cart_rows($pdo, $userId);
            $result = voucher_result($pdo, $userId, $code, $items);
            respond(['success' => true, 'code' => $result['voucher']['code'], 'discount' => $result['discount']]);

        case 'checkout':
            require_method('POST');
            $userId = require_user_id();
            $data = request_data();
            $items = cart_rows($pdo, $userId);
            if ($items === []) {
                throw new InvalidArgumentException('Your cart is empty.');
            }
            foreach ($items as $item) {
                if ((int)$item['quantity'] > (int)$item['stock_quantity']) {
                    throw new InvalidArgumentException($item['name'] . ' does not have enough stock.');
                }
            }
            $subtotal = cart_subtotal($items);
            $shipping = $subtotal < 500 ? 25.0 : 0.0;
            $voucherData = null;
            $voucherCode = trim((string)($data['voucherCode'] ?? ''));
            $discount = 0.0;
            if ($voucherCode !== '') {
                $voucherData = voucher_result($pdo, $userId, $voucherCode, $items);
                $discount = $voucherData['discount'];
            }
            $total = max(0, round($subtotal + $shipping - $discount, 2));
            $methodInput = trim((string)($data['paymentMethod'] ?? 'card'));
            $methodMap = ['card' => 'credit_card', 'credit_card' => 'credit_card', 'debit_card' => 'debit_card', 'bank' => 'fpx', 'fpx' => 'fpx', 'ewallet' => 'ewallet', 'cash_on_delivery' => 'cash_on_delivery'];
            if (!isset($methodMap[$methodInput])) {
                throw new InvalidArgumentException('Please choose a valid payment method.');
            }
            $shippingAddress = json_encode([
                'name' => required_text($data, 'fullName', 'Full name', 255),
                'email' => valid_email(required_text($data, 'email', 'Email', 255)),
                'phone' => required_text($data, 'phone', 'Phone', 20),
                'address' => required_text($data, 'address', 'Address', 1000),
                'city' => required_text($data, 'city', 'City', 100),
                'state' => required_text($data, 'state', 'State', 100),
                'postal' => required_text($data, 'postalCode', 'Postcode', 20),
                'country' => required_text($data, 'country', 'Country', 100),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $orderNumber = 'LOOO-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            $orderStatement = $pdo->prepare('INSERT INTO orders (order_number, user_id, total_amount, shipping_address, payment_method, payment_status, status, notes) VALUES (?, ?, ?, ?, ?, \'paid\', \'confirmed\', ?)');
            $notes = 'Order placed via checkout' . ($voucherCode !== '' ? '; voucher ' . strtoupper($voucherCode) . '; discount RM ' . number_format($discount, 2, '.', '') : '');
            $orderStatement->execute([$orderNumber, $userId, $total, $shippingAddress, $methodMap[$methodInput], $notes]);
            $orderId = (int)$pdo->lastInsertId();
            $itemStatement = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
            $stockStatement = $pdo->prepare('UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?');
            foreach ($items as $item) {
                $itemStatement->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
                $stockStatement->execute([$item['quantity'], $item['product_id'], $item['quantity']]);
            }
            if ($voucherData !== null) {
                $usageStatement = $pdo->prepare('INSERT INTO user_voucher_usage (user_id, voucher_id, order_id) VALUES (?, ?, ?)');
                $usageStatement->execute([$userId, $voucherData['voucher']['id'], $orderId]);
                $voucherUpdate = $pdo->prepare('UPDATE vouchers SET current_usage = current_usage + 1 WHERE id = ?');
                $voucherUpdate->execute([$voucherData['voucher']['id']]);
            }
            $clearStatement = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
            $clearStatement->execute([$userId]);
            respond(['success' => true, 'order_number' => $orderNumber, 'subtotal' => $subtotal, 'shipping' => $shipping, 'discount' => $discount, 'total' => $total]);

        case 'reviews':
            $productId = (int)($_GET['product_id'] ?? 0);
            $statement = $pdo->prepare('SELECT r.id, r.user_id, r.product_id, r.order_id, r.order_number, r.rating, r.comment, r.media_url, r.verified_purchase, r.helpful_count, r.total_votes, r.status, r.created_at, r.updated_at, CONCAT(u.first_name, \' \', u.last_name) AS reviewer FROM product_reviews r INNER JOIN users u ON u.id = r.user_id WHERE r.product_id = ? AND r.status = \'active\' ORDER BY r.created_at DESC');
            $statement->execute([$productId]);
            respond(['success' => true, 'reviews' => $statement->fetchAll()]);

        case 'review_add':
            require_method('POST');
            $userId = require_user_id();
            $data = request_data();
            $productId = (int)($data['product_id'] ?? 0);
            $rating = (int)($data['rating'] ?? 0);
            if ($productId < 1 || $rating < 1 || $rating > 5) {
                throw new InvalidArgumentException('Please choose a valid product and rating.');
            }
            $purchaseStatement = $pdo->prepare('SELECT o.id, o.order_number FROM orders o INNER JOIN order_items oi ON oi.order_id = o.id WHERE o.user_id = ? AND oi.product_id = ? AND o.payment_status = \'paid\' ORDER BY o.created_at DESC LIMIT 1');
            $purchaseStatement->execute([$userId, $productId]);
            $purchase = $purchaseStatement->fetch();
            $statement = $pdo->prepare('INSERT INTO product_reviews (user_id, product_id, order_id, order_number, rating, comment, media_url, verified_purchase, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, \'active\')');
            $statement->execute([
                $userId,
                $productId,
                $purchase['id'] ?? null,
                $purchase['order_number'] ?? null,
                $rating,
                required_text($data, 'comment', 'Review', 5000),
                optional_text($data, 'media_url', 500),
                $purchase ? 1 : 0,
            ]);
            respond(['success' => true, 'message' => 'Your review was submitted.']);

        default:
            respond(['success' => false, 'message' => 'Unknown database action.'], 404);
    }
} catch (InvalidArgumentException $error) {
    respond(['success' => false, 'message' => $error->getMessage()], 422);
} catch (PDOException $error) {
    $duplicate = (string)$error->getCode() === '23000';
    respond(['success' => false, 'message' => $duplicate ? 'This record already exists.' : 'The database request could not be completed.'], $duplicate ? 409 : 500);
} catch (Throwable $error) {
    respond(['success' => false, 'message' => 'The request could not be completed.'], 500);
}
