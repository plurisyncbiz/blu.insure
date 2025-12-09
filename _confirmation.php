<?php
declare(strict_types=1);

// FILE: _confirmation.php

// 1. SETUP
require_once '_bootstrap.php';

// 2. SECURITY & INIT
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: error.php?st=403&error=Invalid Request Method');
    exit();
}

$id = $_POST['id'] ?? '';
$errors = [];

// Load ENV variables
$api_base = $_ENV['API_URL'] ?? 'https://api.blu.insure';
$site_url = $_ENV['SITE_URL'] ?? 'https://blu.insure';

// 3. VALIDATE INPUTS
// The replacement checkbox array (name="replacement[]")
// Because user can technically uncheck both via JS, we must ensure one is set.
$replacementArr = $_POST['replacement'] ?? [];
$replacementVal = null;

if (!empty($replacementArr) && isset($replacementArr[0])) {
    $replacementVal = $replacementArr[0]; // "1" (Yes) or "0" (No)
}

if ($replacementVal === null) {
    // If somehow neither was picked (bypassing client validation)
    $errorString = urlencode("Please indicate if this is a replacement policy.");
    header("Location: confirmation.php?id=$id&error=$errorString");
    exit();
}

// 4. RETRIEVE ACTIVATION ID (Standard pattern)
// We need the activationID to update the specific policy record.
$static = array('10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008','10009');
$data = null;

if(in_array($id, $static)) {
    $location = 'data/' . $id . '.json';
    if(file_exists($location)) {
        $json = file_get_contents($location);
        $data = json_decode($json, true);
    }
} else {
    $url = $api_base . '/serial/' . $id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $json = curl_exec($ch);

    if (curl_errno($ch)) {
        header('Location: error.php?st=500&error=' . urlencode(curl_error($ch)));
        exit();
    }
    curl_close($ch);
    $data = json_decode($json, true);
}

if (!$data || (isset($data['type']) && $data['type'] == 'error')) {
    header('Location: error.php?st=404&error=Policy not found');
    exit();
}

$activationid = (string) ($data['data'][0]['activationid'] ?? '');

if (empty($activationid)) {
    header('Location: error.php?st=500&error=Activation ID missing');
    exit();
}

// 5. PREPARE PAYLOAD
// We are updating the policy to indicate if it is a replacement and confirming terms.

// 6. SUBMIT TO API
// Assuming a generic update endpoint or specific compliance endpoint.
// Adjust '/policy/update' to whatever your specific endpoint is for this step.
$apiUrl = $api_base . '/serial/replacement/' . $activationid;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$jsonResponse = curl_exec($ch);

if (curl_errno($ch)) {
    // Log error, but maybe don't fail the whole flow if strictly unnecessary
    // For now, we fail safely.
    header('Location: error.php?st=500&error=' . urlencode('API Error: ' . curl_error($ch)));
    exit();
}
curl_close($ch);

$response = json_decode($jsonResponse, true);

// Check API Response
if (isset($response['type']) && $response['type'] == 'error') {
    $location = 'error.php?st=400&error=' . urlencode($response['description']);
    header('Location: ' . $location);
    exit();
}

// 7. FINAL REDIRECT
// This is the end of the flow. Redirect to a Thank You page.
$location = $site_url . '/review.php?id=' . $id;
header('Location: ' . $location);
exit();
?>