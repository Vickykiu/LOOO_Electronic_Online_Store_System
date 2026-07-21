<?php
declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $_GET['action'] = 'checkout';
} elseif (isset($_GET['order_id'])) {
    $_GET['action'] = 'order';
}

require 'api.php';
