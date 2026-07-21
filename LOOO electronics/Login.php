<?php
session_start();
require_once 'config.php';

$message = '';
$messageType = '';
$returnTo = trim((string)($_POST['return'] ?? $_GET['return'] ?? 'main page.php'));
if ($returnTo === '' || preg_match('/[\r\n]/', $returnTo) || str_starts_with($returnTo, '//') || parse_url($returnTo, PHP_URL_SCHEME) !== null) {
    $returnTo = 'main page.php';
}

if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $message = 'Account created successfully. You can now log in.';
    $messageType = 'success';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $message = 'Please enter both email and password.';
        $messageType = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                
                header('Location: ' . $returnTo);
                exit;
            } else {
                $message = 'Invalid email or password.';
                $messageType = 'error';
            }
        } catch(PDOException $e) {
            $message = 'Login failed. Please try again.';
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
    <title>LOOO - Login</title>
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
            max-width: 1200px; /* match frame width for consistent layout */
            margin: 0 auto;
            width: 100%;
            align-items: center; /* vertically center sections and avoid stretching */
        }

        /* Left Side - Login Form */
        .login-section {
            flex: 1 1 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            background: white;
        }

        .login-content {
            width: 100%;
            max-width: 400px;
        }

        .login-title {
            font-size: 48px;
            font-weight: bold;
            color: black;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 40px;
            text-align: center;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
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

        .form-input::placeholder {
            color: #999;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
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
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #007bff;
        }

        .checkbox-container label {
            font-size: 14px;
            color: #666;
        }

        .forgot-password {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 200px;
            background: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin: 0 auto 30px auto;
            transition: background-color 0.3s ease;
            display: block;
        }

        .login-btn:hover {
            background: #0056b3;
        }

        .create-account {
            text-align: center;
            margin-bottom: 30px;
            font-size: 13px;
        }

        .create-account span {
            color: #666;
            font-size: 14px;
        }

        .create-account a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            margin-left: 5px;
        }

        .create-account a:hover {
            text-decoration: underline;
        }

        .back-to-main {
            text-align: center;
            margin-bottom: 20px;
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

        /* Right Side - Image panel sized to match left content height */
        .background-section {
            flex: 1 1 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 40px; /* no vertical padding so height matches exactly */
            background: white;
            position: relative;
        }

        .side-image {
            max-width: 100%;
            width: auto;
            height: 100%;
            display: block;
            transform: translateX(-24px); /* nudge a bit more left */
            object-fit: contain;
        }

        .background-image {
            max-width: 90%;
            max-height: 90%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .login-section {
                padding: 40px 20px;
                order: 2;
            }
            
            .background-section {
                order: 1;
                height: auto;
                padding: 0 20px;
            }
            .side-image {
                transform: translateX(-12px);
                width: auto;
                max-width: 100%;
                height: 100%;
            }
            
            .workspace-scene {
                width: 90%;
                height: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Side - Login Form -->
        <section class="login-section">
            <div class="login-content">
                <h1 class="login-title">Login</h1>
                <p class="login-subtitle">Please enter your login credentials to log in</p>
                
                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="return" value="<?php echo htmlspecialchars($returnTo, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group">
                            <input type="email" name="email" class="form-input" placeholder="Email Address" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="email">
                    </div>
                    
                    <div class="form-group">
                        <div class="password-container">
                            <input type="password" name="password" class="form-input" placeholder="Password" required autocomplete="current-password">
                            <button type="button" class="password-toggle" aria-label="Show password">Show</button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">Login</button>
                </form>
                
                <div class="create-account">
                    <span>Don't have an account?</span>
                    <a href="Create Account.php">Create Account</a>
                </div>
                
                <div class="back-to-main">
                    <a href="main page.php">← Back to Main Page</a>
                </div>
                
            </div>
        </section>

        <!-- Right Side - Image Panel -->
        <section class="background-section">
            <img class="side-image" src="Picture.png" alt="Decorative login illustration">
        </section>
    </div>

    <script>
        // Sync right panel height to the login form content.
        function syncRightPanelHeight() {
            const loginContent = document.querySelector('.login-content');
            const rightPanel = document.querySelector('.background-section');
            if (!loginContent || !rightPanel) return;

            const heading = loginContent.querySelector('.login-title');
            const lastAction = loginContent.querySelector('.back-to-main');
            if (!heading || !lastAction) return;

            const top = heading.offsetTop;
            const bottom = lastAction.offsetTop + lastAction.offsetHeight;
            const height = Math.max(0, bottom - top);

            // Apply exact height for the right panel to match the left visual block
            rightPanel.style.height = height + 'px';
        }

        window.addEventListener('load', syncRightPanelHeight);
        window.addEventListener('resize', syncRightPanelHeight);

        // Password toggle functionality
        const passwordToggle = document.querySelector('.password-toggle');
        const passwordInput = document.querySelector('input[type="password"]');
        
        passwordToggle.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.textContent = 'Hide';
                passwordToggle.setAttribute('aria-label', 'Hide password');
            } else {
                passwordInput.type = 'password';
                passwordToggle.textContent = 'Show';
                passwordToggle.setAttribute('aria-label', 'Show password');
            }
        });

        // Form submission - PHP handles login now
        // Form will submit normally to PHP for processing

        // Create account link navigates directly via anchor

        // Forgot password link
        const forgotPasswordLink = document.querySelector('.forgot-password');
        forgotPasswordLink.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Forgot Password functionality would open here');
        });
    </script>
</body>
</html>
