<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/UniFiApi.php';

use Src\Config;
use Src\Auth;
use Src\UniFiApi;

$config = new Config(__DIR__ . '/../.env');
$auth = new Auth($config);

try {
    $mac = $_GET['id'] ?? '';
    if (!$mac || !preg_match('/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/', $mac)) {
        throw new Exception('Invalid or missing MAC address.');
    }
    $token = $auth->getAccessToken();
    $email = $auth->getEmail($token);
    if (!str_ends_with($email, $config->get('ALLOWED_DOMAIN'))) {
        throw new Exception('Email domain not allowed.');
    }
    $unifi = new UniFiApi($config);
    $unifi->authorizeMac($mac, 1440);
    echo "<h2>Welcome, you are now connected.</h2><p>Email: " . htmlspecialchars($email) . "</p>";
} catch (Exception $e) {
    echo "<h2>Error</h2><p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
