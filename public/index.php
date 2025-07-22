<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Auth.php';

use Src\Config;
use Src\Auth;

$config = new Config(__DIR__ . '/../.env');

$mac = $_GET['id'] ?? '';
$error = '';
if (!$mac || !preg_match('/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/', $mac)) {
    $error = 'Invalid or missing MAC address.';
}

if ($error) {
    echo "<h2>Error</h2><p>$error</p>";
    exit;
}

$auth = new Auth($config);
$authUrl = $auth->getAuthUrl($mac);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wi-Fi Login</title>
    <style>body{font-family:sans-serif;text-align:center;margin-top:10%}button{padding:10px 20px;font-size:1.2em}</style>
</head>
<body>
    <h2>Welcome to Guest Wi-Fi</h2>
    <p>To access the internet, please log in with your Microsoft account.</p>
    <a href="<?= htmlspecialchars($authUrl) ?>"><button>Login with Microsoft</button></a>
</body>
</html>
