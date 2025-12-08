<?php
// FILE: _mainlife.php

// 1. SETUP
require_once '_bootstrap.php';
// If using Composer autoload for classes:
// use App\Validation;
// Otherwise:
require_once '/src/Validation.php';

// 2. SECURITY & INPUTS
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: error.php?code=invalid_method");
    exit();
}

$id = preg_replace('/[^a-zA-Z0-9-]/', '', $_POST['id'] ?? '');

if (!$id) {
    header('Location: error.php?code=missing_id');
    exit();
}

// 3. VALIDATE ID NUMBER (Local Check)
$idno = $_POST['policy_holder_idno'] ?? '';
$id_analysis = \App\Validation::parseSAID($idno);

if (!$id_analysis['valid']) {
    // Redirect back to form with error (You can make this fancier with sessions later)
    header("Location: index.php?id=$id&error=invalid_id_number");
    exit();
}

// 4. PREPARE DATA
// We fetch the product details from Session (set by index.php) or API to ensure we have the Product Name for routing
if (!isset($_SESSION['serial_data']) || $_SESSION['current_id'] !== $id) {
    // Fetch fresh if missing
    // (You can copy the curl logic from bootstrap here or make it a reusable function)
    // For brevity, assuming session is valid from index.php step.
}
$product_name = $_SESSION['serial_data']['product_name'] ?? 'Unknown';
$activationid = $_SESSION['serial_data']['activationid'] ?? '';

// Build the payload
$policy_holder = [
    'name'                => filter_input(INPUT_POST, 'policy_holder_name', FILTER_SANITIZE_SPECIAL_CHARS),
    'surname'             => filter_input(INPUT_POST, 'policy_holder_surname', FILTER_SANITIZE_SPECIAL_CHARS),
    'date_of_birth'       => $id_analysis['date_of_birth'],
    'gender'              => $id_analysis['gender'],
    'mobile_number'       => filter_input(INPUT_POST, 'policy_holder_cellno', FILTER_SANITIZE_SPECIAL_CHARS),
    'email_address'       => filter_input(INPUT_POST, 'policy_holder_email', FILTER_SANITIZE_EMAIL),
    'relationship_to_main'=> 'MAIN',
    'id_type'             => 'IDENTITY_DOCUMENT',
    'id_value'            => $idno,
    'id_expiry_date'      => '2080-01-01', // Static default
    'id_country_of_issue' => 'ZAF',
    'id_issued_by'        => 'DHA',
    'employment_status'   => filter_input(INPUT_POST, 'employment_status', FILTER_SANITIZE_SPECIAL_CHARS),
    'employment_industry' => filter_input(INPUT_POST, 'employment_industry', FILTER_SANITIZE_SPECIAL_CHARS),
    'activationid'        => $activationid
];

// 5. SUBMIT TO API
$url = $_ENV['API_URL'] . '/policy/main';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($policy_holder));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $_ENV['APP_ENV'] === 'production');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$api_result = json_decode($response, true);

// 6. ERROR HANDLING
if ($http_code >= 400 || ($api_result['type'] ?? '') === 'error') {
    $error_desc = $api_result['description'] ?? 'API Submission Failed';
    header('Location: error.php?st=400&error=' . urlencode($error_desc));
    exit();
}

// 7. ROUTING LOGIC (The Switch)
// This decides where to go based on the Product Name we retrieved earlier
$next_page = 'error.php?error=unknown_product_route';

// Modern PHP Match (PHP 8.0+) or Switch
switch ($product_name) {
    case 'Sanlam Individual Funeral Cover':
        $next_page = 'beneficiaries.php';
        break;

    case 'Sanlam Family Funeral Cover':
    case 'Sanlam Extended Family Funeral Cover':
        $next_page = 'spouse.php';
        break;

    default:
        // Log unexpected product name
        // error_log("Unknown routing for product: " . $product_name);
        $next_page = 'beneficiaries.php'; // Default fallback?
        break;
}

// Redirect
header("Location: " . $_ENV['SITE_URL'] . "/" . $next_page . "?id=" . $id);
exit();
?>