<?php
declare(strict_types=1);
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php?return=profile.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim((string)($_POST['first_name'] ?? ''));
    $lastName = trim((string)($_POST['last_name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $gender = trim((string)($_POST['gender'] ?? '')) ?: null;
    if ($firstName === '' || $lastName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid name and email address.';
        $messageType = 'error';
    } elseif ($gender !== null && !in_array($gender, ['male', 'female'], true)) {
        $message = 'Please choose a valid gender.';
        $messageType = 'error';
    } else {
        try {
            $statement = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, birth_date = ?, address = ?, city = ?, postal_code = ?, avatar_url = ?, gender = ?, default_avatar = ? WHERE id = ?');
            $statement->execute([
                $firstName,
                $lastName,
                $email,
                trim((string)($_POST['phone'] ?? '')) ?: null,
                trim((string)($_POST['birth_date'] ?? '')) ?: null,
                trim((string)($_POST['address'] ?? '')) ?: null,
                trim((string)($_POST['city'] ?? '')) ?: null,
                trim((string)($_POST['postal_code'] ?? '')) ?: null,
                trim((string)($_POST['avatar_url'] ?? '')) ?: null,
                $gender,
                trim((string)($_POST['default_avatar'] ?? '')) ?: null,
                $userId,
            ]);
            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
            $_SESSION['user_email'] = $email;
            $message = 'Profile updated successfully.';
        } catch (PDOException $error) {
            $message = (string)$error->getCode() === '23000' ? 'That email address is already used by another account.' : 'The profile could not be updated.';
            $messageType = 'error';
        }
    }
}

$statement = $pdo->prepare('SELECT id, first_name, last_name, email, phone, birth_date, address, city, postal_code, avatar_url, created_at, updated_at, gender, default_avatar FROM users WHERE id = ?');
$statement->execute([$userId]);
$user = $statement->fetch();
if (!$user) {
    session_destroy();
    header('Location: Login.php');
    exit;
}

$orderStatement = $pdo->prepare('SELECT order_number, total_amount, payment_status, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10');
$orderStatement->execute([$userId]);
$orders = $orderStatement->fetchAll();

function field_value(array $user, string $key): string
{
    return htmlspecialchars((string)($user[$key] ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOOO - My Profile</title>
    <link rel="stylesheet" href="site-pages.css">
    <style>
        body { background: #f8f9fa; }
        .profile-page { width: min(1100px, calc(100% - 40px)); margin: 0 auto; padding: 48px 0 70px; }
        .profile-heading { margin-bottom: 28px; }
        .profile-heading a { color: #007bff; text-decoration: none; font-weight: 700; }
        .profile-heading h1 { margin: 12px 0 6px; font-size: clamp(34px, 5vw, 48px); }
        .profile-heading p { color: #666; }
        .profile-layout { display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 28px; align-items: start; }
        .profile-card { padding: 28px; background: #fff; border: 1px solid #e7e7e7; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,.05); }
        .profile-card h2 { margin-bottom: 20px; }
        .profile-form { display: grid; grid-template-columns: 1fr 1fr; gap: 17px; }
        .field { display: grid; gap: 7px; }
        .field.full { grid-column: 1 / -1; }
        .field label { font-weight: 700; }
        .field input, .field select, .field textarea { width: 100%; padding: 12px 13px; border: 1px solid #ccc; border-radius: 7px; font: inherit; }
        .field textarea { min-height: 100px; resize: vertical; }
        .save-btn { grid-column: 1 / -1; min-height: 48px; color: #fff; background: #007bff; border: 0; border-radius: 8px; cursor: pointer; font-weight: 800; }
        .message { margin-bottom: 18px; padding: 13px 15px; border-radius: 8px; }
        .message.success { color: #0f5132; background: #d1e7dd; }
        .message.error { color: #842029; background: #f8d7da; }
        .orders { display: grid; gap: 12px; }
        .order { padding: 15px; background: #f8f9fa; border-radius: 9px; }
        .order strong, .order span { display: block; }
        .order span { margin-top: 5px; color: #666; font-size: 14px; }
        .empty { color: #666; line-height: 1.55; }
        .account-meta { margin-top: 20px; color: #666; font-size: 13px; }
        @media (max-width: 850px) { .profile-layout { grid-template-columns: 1fr; } }
        @media (max-width: 560px) { .profile-form { grid-template-columns: 1fr; } .field.full, .save-btn { grid-column: auto; } }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-top">
            <a class="site-logo" href="main page.php" aria-label="LOOO home"><img src="logo.png" alt="LOOO Logo"></a>
            <nav class="site-nav" aria-label="Main navigation"><div class="nav-item"><a href="Products.html">Products</a></div><div class="nav-item"><a href="Customer Services.php">Services</a></div><div class="nav-item"><a href="Technical page.html">Support</a></div><div class="nav-item"><a href="rewards.html">Deals</a></div></nav>
            <div class="header-actions"><a class="login-link" href="logout.php">Logout</a><a class="icon-link" href="Cart.html" aria-label="Cart">&#128722;</a></div>
        </div>
    </header>
    <main class="profile-page">
        <div class="profile-heading"><a href="main page.php">← Return to Main Page</a><h1>My Profile</h1><p>Manage the account details saved in your LOOO database record.</p></div>
        <?php if ($message): ?><div class="message <?php echo $messageType; ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <div class="profile-layout">
            <section class="profile-card"><h2>Personal information</h2>
                <form class="profile-form" method="post">
                    <div class="field"><label for="first_name">First name</label><input id="first_name" name="first_name" value="<?php echo field_value($user, 'first_name'); ?>" required></div>
                    <div class="field"><label for="last_name">Last name</label><input id="last_name" name="last_name" value="<?php echo field_value($user, 'last_name'); ?>" required></div>
                    <div class="field full"><label for="email">Email address</label><input id="email" name="email" type="email" value="<?php echo field_value($user, 'email'); ?>" required></div>
                    <div class="field"><label for="phone">Phone number</label><input id="phone" name="phone" value="<?php echo field_value($user, 'phone'); ?>"></div>
                    <div class="field"><label for="birth_date">Birth date</label><input id="birth_date" name="birth_date" type="date" value="<?php echo field_value($user, 'birth_date'); ?>"></div>
                    <div class="field"><label for="gender">Gender</label><select id="gender" name="gender"><option value="">Prefer not to say</option><option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option><option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option></select></div>
                    <div class="field"><label for="postal_code">Postcode</label><input id="postal_code" name="postal_code" value="<?php echo field_value($user, 'postal_code'); ?>"></div>
                    <div class="field full"><label for="address">Address</label><textarea id="address" name="address"><?php echo field_value($user, 'address'); ?></textarea></div>
                    <div class="field"><label for="city">City</label><input id="city" name="city" value="<?php echo field_value($user, 'city'); ?>"></div>
                    <div class="field"><label for="default_avatar">Default avatar</label><input id="default_avatar" name="default_avatar" value="<?php echo field_value($user, 'default_avatar'); ?>" placeholder="avatar-1"></div>
                    <div class="field full"><label for="avatar_url">Avatar URL</label><input id="avatar_url" name="avatar_url" type="url" value="<?php echo field_value($user, 'avatar_url'); ?>" placeholder="https://"></div>
                    <button class="save-btn" type="submit">Save Profile</button>
                </form>
                <p class="account-meta">Account created <?php echo htmlspecialchars((string)$user['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
            </section>
            <aside class="profile-card"><h2>Recent orders</h2><div class="orders">
                <?php if ($orders): foreach ($orders as $order): ?>
                    <article class="order"><strong><?php echo htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8'); ?></strong><span>RM <?php echo number_format((float)$order['total_amount'], 2); ?> · <?php echo htmlspecialchars(ucfirst($order['status']), ENT_QUOTES, 'UTF-8'); ?></span><span><?php echo htmlspecialchars((string)$order['created_at'], ENT_QUOTES, 'UTF-8'); ?></span></article>
                <?php endforeach; else: ?><p class="empty">No orders yet. Products added to your account cart will appear here after checkout.</p><?php endif; ?>
            </div></aside>
        </div>
    </main>
</body>
</html>
