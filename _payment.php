<?php
declare(strict_types=1);

// Show all errors, warnings, and notices
error_reporting(E_ALL);

// Display errors on the page
ini_set('display_errors', '1');

// Optional: display startup errors as well
ini_set('display_startup_errors', '1');
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Convert to string for storage in MySQL (VARCHAR(36))


    //get the request
    $id = $_POST['id'];

    //retrieve data from API
    $static = array('10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008','10009');
    if(in_array($id, $static)) {
        //matches a defined product
        $location = 'data/' . $id . '.json';
        $json = file_get_contents($location);
        $data = json_decode($json, true);
    } else {
        //get activation details.
        $url = 'https://api.blu.insure/serial/' . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        $data = json_decode($json, true);
        if($data['type'] == 'error'){
            $location = 'error.php?st=400&error=' . $data['description'];
            header('Location: ' . $location);
            exit();
        }
    }


    //get values from the main life / activation
    $activationid = (string) $data['data'][0]['activationid'];

    $values = $_POST;
    $today = new DateTime(); // Creates a DateTime object for the current date and time

    //create tomorrow
    $tomorrow = $today;
    $tomorrow = $tomorrow->add(new DateInterval('P1D')); // Adds one day (P1D represents a period of 1 day)

    //retrieve p values
    $acc_no = $values['acc_no'];
    $bank = $values['bank'];
    $branch_code = '';
    $debit_date = $tomorrow->format('Y-m-d');

    $payment = array(
        'acc_no' => $acc_no,
        'bank' => $bank,
        'branch_code' => $branch_code,
        'debit_date' => $debit_date,
        'activationid' => $activationid,
    );


    //submit to API
    $url = 'https://api.blu.insure/policy/payment';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $json = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
    }
    curl_close($ch);
    $data = json_decode($json, true);
    if($data['type'] == 'error'){
        $location = 'error.php?st=400&error=' . $data['description'];
        header('Location: ' . $location);
        exit();
    } elseif($data['type'] == 'success') {
        //submit the mandate and store result
        $data = submit_mandate($activationid);
        $location = 'https://blu.insure/confirmation.php?id=' . $id;
        header('Location: ' . $location);
        exit();
    }

}

function submit_mandate($sreialno)
{

    $url = 'https://api.blu.insure/mandate/' . $sreialno;

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,                     // POST request
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT        => 30,                       // optional timeout
    ]);

    $response = curl_exec($ch);

// Check for cURL errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("cURL error: $error");
    }

// Get HTTP status code
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

// Decode JSON safely
    try {
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        throw new \RuntimeException("Invalid JSON response: " . $e->getMessage());
    }

// Optional: check for unsuccessful status
    if ($httpCode < 200 || $httpCode >= 300) {
        throw new \RuntimeException("API responded with HTTP $httpCode: " . print_r($data, true));
    }

// Success: $data contains the decoded JSON response
    return $data;
}
?>

