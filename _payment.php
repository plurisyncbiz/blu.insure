<?php

// FILE: _payments.php

// 1. SETUP
require_once '_bootstrap.php';

// 2. SECURITY & INIT
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: error.php?st=403&error=Invalid Request Method');
    exit();
}

$id = $_POST['id'] ?? '';

// Load ENV variables
$api_base = $_ENV['API_URL'] ?? 'https://api.blu.insure';
$site_url = $_ENV['SITE_URL'] ?? 'https://blu.insure';

// 3. RETRIEVE ACTIVATION ID
$static = array('10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008','10009');
$data = null;

if(in_array($id, $static)) {
    // Static file lookup
    $location = 'data/' . $id . '.json';
    if(file_exists($location)) {
        $json = file_get_contents($location);
        $data = json_decode($json, true);
    }
} else {
    // API Call to get Serial/Activation details
    $url = $api_base . '/serial/' . $id;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $json = curl_exec($ch);

    if (curl_errno($ch)) {
        $location = 'error.php?st=500&error=' . urlencode(curl_error($ch));
        header('Location: ' . $location);
        exit();
    }
    curl_close($ch);

    $data = json_decode($json, true);

    if(isset($data['type']) && $data['type'] == 'error'){
        $location = 'error.php?st=400&error=' . urlencode($data['description']);
        header('Location: ' . $location);
        exit();
    }
}

if (!$data) {
    header('Location: error.php?st=404&error=Policy data not found');
    exit();
}

// Extract Activation ID
$activationid = (string) ($data['data'][0]['activationid'] ?? '');

if (empty($activationid)) {
    header('Location: error.php?st=500&error=Activation ID missing');
    exit();
}


// 4. PREPARE PAYMENT DATA
$acc_no = filter_var($_POST['acc_no'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$bank   = filter_var($_POST['bank'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Calculate Debit Date (Tomorrow)
$today = new DateTime();
$tomorrow = $today->add(new DateInterval('P1D'));
$debit_date = $tomorrow->format('Y-m-d');

$paymentPayload = array(
    'acc_no'       => $acc_no,
    'bank'         => $bank,
    'branch_code'  => '',
    'debit_date'   => $debit_date,
    'activationid' => $activationid,
);


// 5. SUBMIT PAYMENT TO API
$url = $api_base . '/policy/payment';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentPayload));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$json = curl_exec($ch);

if (curl_errno($ch)) {
    header('Location: error.php?st=500&error=' . urlencode(curl_error($ch)));
    exit();
}
curl_close($ch);

$paymentResponse = json_decode($json, true);


// 6. HANDLE RESPONSE & MANDATE
if (isset($paymentResponse['type']) && $paymentResponse['type'] == 'error') {
    // Payment API Failed
    $location = 'error.php?st=400&error=' . urlencode($paymentResponse['description']);
    header('Location: ' . $location);
    exit();

} elseif (isset($paymentResponse['type']) && $paymentResponse['type'] == 'success') {

    // Payment Success -> Trigger Mandate
    try {
        // --- UPDATED LOGIC HERE ---
        // We get the full response object back, including code and body
        $mandateResult = submit_mandate($activationid, $api_base);

        // Check the HTTP Status Code directly
        if ($mandateResult['code'] >= 200 && $mandateResult['code'] < 300) {

            // SUCCESS
            $location = $site_url . '/confirmation.php?id=' . $id;
            header('Location: ' . $location);
            exit();

        } else {

            // FAILURE (e.g. 409 Conflict / Mandate Error)
            // We extract the specific error description from the API body
            $errorMsg = $mandateResult['data']['description']
                ?? $mandateResult['data']['message']
                ?? 'Mandate processing failed';

            $location = 'error.php?st=' . $mandateResult['code'] . '&error=' . urlencode('Mandate Error: ' . $errorMsg);
            header('Location: ' . $location);
            exit();
        }

    } catch (RuntimeException $e) {
        // Network/Critical Crashes only
        $location = 'error.php?st=500&error=' . urlencode('System Error: ' . $e->getMessage());
        header('Location: ' . $location);
        exit();
    }
} else {
    // Unknown API Response
    header('Location: error.php?st=500&error=Unknown API Response');
    exit();
}


/**
 * Helper function to submit the mandate
 * UPDATED: Returns array ['code' => int, 'data' => array] instead of throwing exceptions on 4xx errors
 */
function submit_mandate(string $activationid, string $api_base): array
{
    $url = $api_base . '/mandate/' . $activationid;

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT        => 30,
    ]);

    $response = curl_exec($ch);

    // Network level errors (DNS, Timeout) still throw exceptions
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("cURL connection error: $error");
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Decode result safely
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Fallback if API returned non-JSON string
        $data = ['description' => 'Invalid JSON response from server', 'raw' => $response];
    }

    // Return both code and data so the caller can decide logic
    return [
        'code' => $httpCode,
        'data' => $data
    ];
}
?>