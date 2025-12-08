<?php
// ==========================================
// 1. SETUP & DEPENDENCIES
// ==========================================
// Suppress errors in production, show in UAT
ini_set('display_errors', 0);

require __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/Validation.php';


use Dotenv\Dotenv;

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 2. LOAD ENVIRONMENT VARIABLES
// ==========================================
try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['API_URL', 'SITE_URL', 'APP_ENV']);
} catch (Exception $e) {
    // If .env is missing, kill the script immediately
    die("Server Configuration Error: .env file missing.");
}

// Toggle error display based on environment
if ($_ENV['APP_ENV'] !== 'production') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// ==========================================
// 3. IDENTIFY THE USER (The "Check")
// ==========================================
// Priority:
// 1. POST (Form submission from index.php)
// 2. GET (Landing on index.php)
// 3. SESSION (User navigating between pages)

$raw_id = $_POST['id'] ?? $_GET['id'] ?? $_SESSION['current_id'] ?? null;

// Sanitize the ID immediately
$id = $raw_id ? preg_replace('/[^a-zA-Z0-9-]/', '', $raw_id) : null;

if (!$id) {
    // No ID found anywhere? Send to error page.
    header("Location: error.php?code=missing_id");
    exit();
}

// ==========================================
// 4. DATA RETRIEVAL (Cache vs API)
// ==========================================
$serial_data = [];
$is_cached = false;

// CHECK 1: Do we already have this specific ID in the session?
if (isset($_SESSION['serial_data']) && isset($_SESSION['current_id'])) {
    if ($_SESSION['current_id'] === $id) {
        $serial_data = $_SESSION['serial_data'];
        $is_cached = true;
    }
}

// CHECK 2: If not cached, call the API
if (!$is_cached) {

    // Check for Legacy Static Data (Optional, based on your code)
    $static_ids = ['10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008', '10009'];

    if (in_array($id, $static_ids)) {
        // Load Local JSON
        $local_file = __DIR__ . '/data/' . $id . '.json';
        if (file_exists($local_file)) {
            $json_response = file_get_contents($local_file);
            $decoded = json_decode($json_response, true);
            $serial_data = $decoded['data'][0] ?? null;
        }
    } else {
        // Call Live API
        $url = $_ENV['API_URL'] . '/serial/' . $id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $_ENV['APP_ENV'] === 'production');

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200 && $response) {
            $decoded = json_decode($response, true);
            $serial_data = $decoded['data'][0] ?? null;
            die(var_dump($serial_data));
        }
    }

    // Save result to Session for next time
    if ($serial_data) {
        $_SESSION['current_id'] = $id;
        $_SESSION['serial_data'] = $serial_data;
    } else {
        // Valid ID format, but API returned nothing or error
        header("Location: error.php?code=invalid_activation");
        exit();
    }
}

// ==========================================
// 5. EXPOSE VARIABLES FOR VIEWS
// ==========================================
// This extracts keys like $product_code, $cellno, etc. into the global scope
// so index.php can use them directly.

$product_code        = $serial_data['product_code'] ?? '';
$product_type        = $serial_data['product_type'] ?? 'Product';
$product_description = $serial_data['product_name'] ?? 'Product';
$cellno              = $serial_data['cellno'] ?? '';
$product_price       = $serial_data['product_price'] ?? '0.00';
$activationid        = $serial_data['activationid'] ?? null;
$serialno            = $serial_data['serialno'] ?? null;

// Handle Product Configuration JSON safely
$config_raw = $serial_data['product_configuration'] ?? '{}';
$options    = is_array($config_raw) ? $config_raw : json_decode($config_raw, true);

$term       = $options['term'] ?? '0';
$cover      = $options['cover'] ?? '0';

// Check if already activated (Logic Gate)
if (!$activationid && strpos($_SERVER['SCRIPT_NAME'], 'index.php') !== false) {
    // If we are on the index page and activation ID is missing/null
    // You might want to handle this, or perhaps your API only returns unactivated serials?
    // Leaving this open based on your previous code logic.
}

// END OF BOOTSTRAP
// Script continues to the file that included this...
?>