<?php
// FILE: _beneficiaries.php

// 1. SETUP
require_once '_bootstrap.php';

// 2. SECURITY & INIT
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: error.php?st=403&error=Invalid Request Method');
    exit();
}

$id = $_POST['id'] ?? '';
$debug = $_POST['debug'] ?? 'false';
$errors = [];

// 3. RETRIEVE ACTIVATION ID
$static = array('10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008','10009');
$data = null;

if(in_array($id, $static)) {
    $location = 'data/' . $id . '.json';
    if(file_exists($location)) {
        $json = file_get_contents($location);
        $data = json_decode($json, true);
    }
} else {
    // USE ENV VARIABLE FOR API LOOKUP
    $url = $_ENV['API_URL'] . '/serial/' . $id;

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

// Extract Data
$product_description = (string) ($data['data'][0]['product_name'] ?? '');
$activationid = (string) ($data['data'][0]['activationid'] ?? '');

if (empty($activationid)) {
    header('Location: error.php?st=500&error=Activation ID missing');
    exit();
}

// 4. PREPARE & VALIDATE INPUTS
$names    = $_POST['beneficiary_name'] ?? [];
$surnames = $_POST['beneficiary_surname'] ?? [];
$dobs     = $_POST['beneficiary_dob'] ?? [];
$idnos    = $_POST['beneficiary_idno'] ?? [];
$cellnos  = $_POST['beneficiary_cellno'] ?? [];
$genders  = $_POST['beneficiary_gender'] ?? [];
$emails   = $_POST['beneficiary_email'] ?? [];

$validBeneficiaries = [];

if (count($names) !== count($surnames)) {
    header("Location: beneficiaries.php?id=$id&error=Form data mismatch");
    exit();
}

for ($i = 0; $i < count($names); $i++) {
    $idx = $i + 1;

    // Sanitize
    $currName    = trim($names[$i]);
    $currSurname = trim($surnames[$i]);
    $currDob     = trim($dobs[$i]);
    $currGender  = trim($genders[$i]);
    $currId      = trim($idnos[$i]);
    $currCell    = trim($cellnos[$i]);
    $currEmail   = trim($emails[$i]);

    // Basic Validation
    if (empty($currName))    $errors[] = "Beneficiary $idx: First Name is required.";
    if (empty($currSurname)) $errors[] = "Beneficiary $idx: Surname is required.";
    if (empty($currDob))     $errors[] = "Beneficiary $idx: Date of Birth is required.";
    if (empty($currGender))  $errors[] = "Beneficiary $idx: Gender is required.";

    // Optional Validation
    if (!empty($currId) && !preg_match('/^\d{13}$/', $currId)) {
        $errors[] = "Beneficiary $idx: ID must be 13 digits.";
    }
    if (!empty($currEmail) && !filter_var($currEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Beneficiary $idx: Invalid Email.";
    }

    // Build Payload
    $validBeneficiaries[] = array(
        'name'                 => htmlspecialchars($currName),
        'surname'              => htmlspecialchars($currSurname),
        'date_of_birth'        => htmlspecialchars($currDob),
        'gender'               => htmlspecialchars($currGender),
        'mobile_number'        => htmlspecialchars($currCell),
        'email_address'        => htmlspecialchars($currEmail),
        'relationship_to_main' => 'BENEFICIARY',
        'id_type'              => 'IDENTITY_DOCUMENT',
        'id_value'             => htmlspecialchars($currId),
        'id_expiry_date'       => '2080-01-01',
        'id_country_of_issue'  => 'ZAF',
        'id_issued_by'         => 'DHA',
        'id_validated_by'      => '',
        'id_validated_when'    => '',
        'activationid'         => $activationid
    );
}

// 5. STOP IF ERRORS
if (!empty($errors)) {
    $errorString = urlencode(implode(' | ', $errors));
    header("Location: beneficiaries.php?id=$id&error=$errorString");
    exit();
}

// 6. SUBMIT TO API
// USE ENV VARIABLE FOR SUBMISSION
$apiUrl = $_ENV['API_URL'] . '/policy/beneficiary';

foreach ($validBeneficiaries as $beneficiary) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($beneficiary));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $jsonResponse = curl_exec($ch);

    if (curl_errno($ch)) {
        // Log error silently
    }
    curl_close($ch);

    $response = json_decode($jsonResponse, true);

    if(isset($response['type']) && $response['type'] == 'error'){
        $location = 'error.php?st=400&error=' . urlencode($response['description']);
        header('Location: ' . $location);
        exit();
    }
}

// 7. REDIRECT ON SUCCESS
// USE ENV VARIABLE FOR REDIRECT DOMAIN
switch ($product_description) {
    case 'Sanlam Individual Funeral Cover':
        $location = $_ENV['SITE_URL'] . '/payment.php?id=' . $id;
        break;

    case 'Sanlam Family Funeral Cover':
    case 'Sanlam Extended Family Funeral Cover':
        $location = $_ENV['SITE_URL'] . '/payment.php?id=' . $id;
        break;

    default:
        $location = 'error.php?st=400&error=' . urlencode('Unknown Product: ' . $product_description);
        break;
}

header('Location: ' . $location);
exit();
?>