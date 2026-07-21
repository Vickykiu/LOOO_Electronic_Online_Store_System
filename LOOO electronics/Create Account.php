<?php
session_start();
require_once 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $termsAccepted = isset($_POST['terms']);
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = 'All fields are required.';
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } elseif (strlen($password) < 8) {
        $message = 'Password must be at least 8 characters long.';
        $messageType = 'error';
    } elseif ($password !== $confirmPassword) {
        $message = 'Passwords do not match.';
        $messageType = 'error';
    } elseif (!$termsAccepted) {
        $message = 'You must agree to the Terms and Privacy Policy.';
        $messageType = 'error';
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $message = 'Email already exists. Please use a different email.';
                $messageType = 'error';
            } else {
                // Hash password and insert user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);
                
                header('Location: Login.php?registered=1');
                exit;
            }
        } catch(PDOException $e) {
            $message = 'Registration failed. Please try again.';
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOOO - Create Account</title>
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            align-items: center;
        }

        /* Left Side - Image Panel */
        .background-section {
            flex: 1 1 50%;
            order: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 40px; /* no vertical padding to match exact height */
            background: white;
            position: relative;
        }

        .side-image {
            max-width: 100%;
            width: auto;
            height: 100%;
            display: block;
            object-fit: contain;
            transform: translateX(-16px); /* subtle left nudge for alignment */
        }

        .workspace-scene {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .workspace-content {
            text-align: center;
            color: #333;
        }

        .workspace-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .workspace-subtitle {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
        }

        .laptop-placeholder {
            width: 200px;
            height: 120px;
            background: #f0f0f0;
            border-radius: 10px;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 14px;
        }

        .lamp-placeholder {
            width: 60px;
            height: 100px;
            background: #333;
            border-radius: 30px;
            margin: 20px auto;
            position: relative;
        }

        .lamp-placeholder::after {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 40px;
            background: #333;
            border-radius: 40px 40px 0 0;
        }

        /* Right Side - Create Account Form */
        .form-section {
            flex: 1 1 50%;
            order: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            background: white;
        }

        .form-content {
            width: 100%;
            max-width: 480px;
        }

        .title {
            font-size: 38px;
            font-weight: 800;
            color: black;
            margin-bottom: 20px;
            text-align: left;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .name-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-group { margin-bottom: 20px; }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            background: #f8f9fa;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #007bff;
            background: white;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            color: #666;
        }

        .form-options {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 8px 0 22px 0;
            color: #666;
            font-size: 14px;
        }

        .form-options input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #007bff;
        }

        .primary-btn {
            width: 220px;
            background: #007bff;
            color: white;
            border: none;
            padding: 14px 26px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin: 0 auto 26px auto;
            transition: background-color 0.3s ease;
            display: block;
        }

        .primary-btn:hover { background: #0056b3; }

        .footnote {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .footnote a {
            color: #007bff;
            text-decoration: none;
        }

        .footnote a:hover { text-decoration: underline; }

        .back-to-main {
            text-align: center;
            margin-top: 15px;
        }

        .back-to-main a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-to-main a:hover {
            color: #007bff;
            text-decoration: underline;
        }

        .back-to-main {
            text-align: center;
            margin-top: 15px;
        }

        .back-to-main a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-to-main a:hover {
            color: #007bff;
            text-decoration: underline;
        }

        /* Message styles */
        .message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .message.error {
            background-color: #ffe6e6;
            color: #d32f2f;
            border: 1px solid #ffcdd2;
        }

        .message.success {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container { flex-direction: column; }
            .form-section { order: 2; padding: 40px 20px; }
            .background-section { order: 1; height: 40vh; padding: 0 20px; }
            .side-image { transform: translateX(-8px); }
            .workspace-scene { width: 90%; height: 90%; }
            .title { text-align: center; }
        }
    </style>
    <script>
        function validateEmail(email) {
            return /[^\s@]+@[^\s@]+\.[^\s@]+/.test(email);
        }
    </script>
    
</head>
<body>
    <div class="container">
        <!-- Left Image Panel -->
        <section class="background-section">
            <img class="side-image" src="Picture1.png" alt="Decorative create account illustration">
        </section>

        <!-- Right Form Panel -->
        <section class="form-section">
            <div class="form-content">
                <h1 class="title">Create Account</h1>
                <p class="subtitle">Fill in the details below to get started</p>

                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form id="createAccountForm" method="POST" action="">
                    <div class="name-row">
                        <input id="firstName" name="firstName" type="text" class="form-input" placeholder="First Name" value="<?php echo htmlspecialchars($firstName ?? '', ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="given-name" aria-label="First name">
                        <input id="lastName" name="lastName" type="text" class="form-input" placeholder="Last Name" value="<?php echo htmlspecialchars($lastName ?? '', ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="family-name" aria-label="Last name">
                    </div>

                    <div class="form-group">
                        <input id="email" name="email" type="email" class="form-input" placeholder="Email Address" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="email" aria-label="Email address">
                    </div>

                    <div class="form-group">
                        <div class="password-container">
                            <input id="password" name="password" type="password" class="form-input" placeholder="Create Password" minlength="8" required autocomplete="new-password" aria-label="Create password">
                            <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">Show</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="password-container">
                            <input id="confirmPassword" name="confirmPassword" type="password" class="form-input" placeholder="Password Confirmation" minlength="8" required autocomplete="new-password" aria-label="Confirm password">
                            <button type="button" class="password-toggle" id="toggleConfirm" aria-label="Show password confirmation">Show</button>
                        </div>
                    </div>

                    <div class="form-options">
                        <input id="terms" name="terms" type="checkbox" required>
                        <label for="terms">I agree with Terms and Privacy Policy</label>
                    </div>

                    <button type="submit" class="primary-btn">Create Account</button>

                    <div class="footnote">
                        Already have an account? <a href="Login.php" id="loginLink">Login</a>
                    </div>
                    
                    <div class="back-to-main">
                        <a href="main page.php">← Back to Main Page</a>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        // Sync left image panel height to match the account form.
        function syncLeftPanelHeight() {
            const formContent = document.querySelector('.form-content');
            const leftPanel = document.querySelector('.background-section');
            if (!formContent || !leftPanel) return;

            const title = formContent.querySelector('.title');
            const lastAction = formContent.querySelector('.back-to-main');
            if (!title || !lastAction) return;

            const top = title.offsetTop;
            const bottom = lastAction.offsetTop + lastAction.offsetHeight;
            const height = Math.max(0, bottom - top);
            leftPanel.style.height = height + 'px';
        }

        window.addEventListener('load', syncLeftPanelHeight);
        window.addEventListener('resize', syncLeftPanelHeight);
        // Password visibility toggles
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirmPassword');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirm = document.getElementById('toggleConfirm');

        function toggleVisibility(input, toggleBtn) {
            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.textContent = 'Hide';
            } else {
                input.type = 'password';
                toggleBtn.textContent = 'Show';
            }
            toggleBtn.setAttribute('aria-label', `${input.type === 'password' ? 'Show' : 'Hide'} ${input.getAttribute('aria-label').toLowerCase()}`);
        }

        togglePassword.addEventListener('click', () => toggleVisibility(passwordInput, togglePassword));
        toggleConfirm.addEventListener('click', () => toggleVisibility(confirmInput, toggleConfirm));

        // Form submission - PHP handles validation now
        const form = document.getElementById('createAccountForm');
        // Form will submit normally to PHP for processing

        // Login link navigates directly via anchor
    </script>
</body>
</html>


