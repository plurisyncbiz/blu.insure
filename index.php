<?php
// ==========================================
// 1. SETUP & ENVIRONMENT
// ==========================================
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['API_URL', 'SITE_URL', 'APP_ENV']);
} catch (Exception $e) {
    die("Server Configuration Error");
}

// ==========================================
// 2. INPUT SANITIZATION
// ==========================================
$uniqid = isset($_GET['id']) ? preg_replace('/[^a-zA-Z0-9-]/', '', $_GET['id']) : null;

if (!$uniqid) {
    header("Location: error.php?code=missing_id");
    exit();
}

// ==========================================
// 3. API VALIDATION
// ==========================================
$url = $_ENV['API_URL'] . '/serial/' . $uniqid;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, (int)($_ENV['API_TIMEOUT'] ?? 10));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Set to false only if strictly needed for local dev

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// ==========================================
// 4. DATA EXTRACTION
// ==========================================
$is_valid = false;
$product_code = '';
$serialno = '';

if ($http_code === 200 && !$curl_error) {
    $data = json_decode($response, true);
    // Use null coalescing operator ?? to avoid "undefined index" warnings
    $product_code = $data['data'][0]['product_code'] ?? null;
    $serialno     = $data['data'][0]['serialno'] ?? null;

    if ($product_code && $serialno) {
        $is_valid = true;
    }
}

// Fail if API didn't give us what we need
if (!$is_valid) {
    header("Location: error.php?code=invalid_activation");
    exit();
}

// ==========================================
// 5. HTML VIEW
// ==========================================
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activate Product <?php echo htmlspecialchars($product_code); ?></title>
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        body { font-family: 'Roboto', sans-serif; }
        .accordion-button, .accordion-button:not(.collapsed), .accordion-body {
            background-color: #f5f7fa !important;
            color: #000;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <?php
    // DYNAMIC INCLUDE LOGIC
    // 1. Sanitize product code specifically for file paths to prevent directory traversal
    $safe_product_code = basename($product_code);

    // 2. Build the path
    $template_file = "_includes/{$safe_product_code}.php";

    // 3. Check if file exists before including
    if (file_exists(__DIR__ . '/' . $template_file)) {
        // pass variables to the included file securely
        include $template_file;
    } else {
        // 4. FALLBACK: What if API returns "10099" but you haven't made 10099.php yet?
        echo '<div class="alert alert-warning text-center shadow-sm">';
        echo '<h4>Product configuration not found.</h4>';
        echo '<p>Please contact support referencing code: <strong>' . htmlspecialchars($product_code) . '</strong></p>';
        echo '</div>';

        // Optional: Log this missing template event
        // error_log("Missing template for product: $product_code");
    }
    ?>
</div>

</body>
</html>