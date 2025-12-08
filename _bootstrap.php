<?php
// FILE: _bootstrap.php

// 1. INITIALIZATION
// =============================================================================
ini_set('display_errors', 0); // Suppress errors in production
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. LOAD ENVIRONMENT
// =============================================================================
try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['API_URL', 'SITE_URL']);
} catch (Exception $e) {
    // Graceful exit if config is missing
    header("Location: error.php?code=config_error");
    exit();
}

// 3. INPUT VALIDATION
// =============================================================================
if (!isset($_GET['id'])) {
    header('Location: error.php?error=missing_id');
    exit;
}

// Sanitize the ID
$id = preg_replace('/[^a-zA-Z0-9-]/', '', $_GET['id']);

// 4. DATA RETRIEVAL (API vs Static)
// =============================================================================
$static_ids = ['10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008', '10009'];
$json = false;

if (in_array($id, $static_ids)) {
    // Legacy/Testing: Load local JSON
    $location = __DIR__ . '/data/' . $id . '.json';
    if (file_exists($location)) {
        $json = file_get_contents($location);
    }
} else {
    // Production: Call API
    $url = $_ENV['API_URL'] . '/serial/' . $id;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // Use env variable to toggle SSL verification (False for local dev, True for Prod)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $_ENV['APP_ENV'] === 'production');

    $json = curl_exec($ch);

    if (curl_errno($ch)) {
        // Log error internally if needed
        // error_log(curl_error($ch));
        $json = false;
    }
    curl_close($ch);
}

// 5. DATA PROCESSING
// =============================================================================
if (!$json) {
    header('Location: error.php?error=api_failure');
    exit;
}

$data = json_decode($json, true);

// Extract variables for the View
// We use null coalescing (??) to prevent "undefined index" warnings
$product_code        = (string)($data['data'][0]['product_code'] ?? '');
$cellno              = (string)($data['data'][0]['cellno'] ?? '');
$product_description = (string)($data['data'][0]['product_name'] ?? 'Product');
$product_price       = (string)($data['data'][0]['product_price'] ?? '0.00');
$activationid        = $data['data'][0]['activationid'] ?? null;

// Handle nested config JSON
$product_config_json = $data['data'][0]['product_configuration'] ?? '{}';
$options             = json_decode($product_config_json, true);
$term                = (string)($options['term'] ?? '0');
$cover               = (string)($options['cover'] ?? '0');

// 6. LOGIC GATES
// =============================================================================
if (is_null($activationid)) {
    header('Location: error.php?st=500&error=You have not activated this policy yet.');
    exit;
}

// If we reach here, everything is valid.
// The variables ($product_description, $cover, etc.) are ready for the view.
?>