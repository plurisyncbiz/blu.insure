<?php
/* Output:
Array
(
    [date_of_birth] => 1980-01-01
    [gender] => Male
    [citizenship] => SA Citizen
    [valid] => 1
)
*/
function parseSAID($idNumber) {
    // Validate basic format
    if (strlen($idNumber) != 13 || !ctype_digit($idNumber)) {
        return ["error" => "Invalid ID number format"];
    }

    // --- Date of Birth ---
    // 1. Get the raw 2-digit year string (e.g. "05" or "99") - DO NOT intval yet
    $dobYearPart = substr($idNumber, 0, 2);
    $month = substr($idNumber, 2, 2);
    $day = substr($idNumber, 4, 2);

    // 2. Determine Century
    $currentYear = (int)date("Y");

    // Assume 2000s first (prepend "20")
    $fullYear = (int)("20" . $dobYearPart);

    // If the constructed 20XX year is in the future, it must be 19XX
    // Example: ID starts with 99. "2099" > "2024", so subtract 100 to get "1999"
    if ($fullYear > $currentYear) {
        $fullYear -= 100;
    }

    // Validate if the resulting date is a real calendar date (e.g. handles Feb 29)
    if (!checkdate((int)$month, (int)$day, $fullYear)) {
        return ["error" => "Invalid Date of Birth in ID"];
    }

    $dob = sprintf("%04d-%02d-%02d", $fullYear, $month, $day);

    // --- Gender ---
    $genderDigits = intval(substr($idNumber, 6, 4));
    $gender = ($genderDigits >= 5000) ? "Male" : "Female";

    // --- Citizenship ---
    $citizenDigit = intval(substr($idNumber, 10, 1));
    $citizenship = ($citizenDigit === 0) ? "SA Citizen" : "Permanent Resident";

    // --- Checksum validation (Luhn algorithm) ---
    $sum = 0;
    $idArray = str_split($idNumber);
    for ($i = 0; $i < 12; $i++) {
        $num = intval($idArray[$i]);
        if ($i % 2 === 0) {
            $sum += $num;
        } else {
            $sum += array_sum(str_split($num * 2));
        }
    }
    $checkDigit = (10 - ($sum % 10)) % 10;
    $valid = ($checkDigit === intval($idArray[12]));

    return [
        "date_of_birth" => $dob,
        "gender" => $gender,
        "citizenship" => $citizenship,
        "valid" => $valid
    ];
}

function activate($sreialno, $ip, $ua)
{

    $url = 'https://api.blu.insure/activate/';

    $ch = curl_init();

    $values = array(
        'sreialno' => $sreialno,
        'ip_address' => $ip,
        'user_agent' => $ua,
    );

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,                     // POST request
        CURLOPT_POSTFIELDS     => json_encode($values),     // Values to POST
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


//ini_set('display_errors', true);
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Convert to string for storage in MySQL (VARCHAR(36))

    //get the request
    $id = $_POST['id'] === null ? '' : $_POST['id'];

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
            $location = 'error.php?st=400&description=' . $data['description'];
            header('Location: ' . $location);
            exit();
        }
    }

    //print_r($data);
    //die();
    //populate schema values
    $product_code = (string) $data['data'][0]['product_code'];
    $product_description = (string) $data['data'][0]['product_description'];
    $product_name = (string) $data['data'][0]['product_name'];
    $cellno = (string) $data['data'][0]['cellno'];
    $term = (string) $data['data'][0]['product_term'];
    $activationid = (string) $data['data'][0]['activationid'];

    //post values
    $name = $_POST['policy_holder_name'];
    $surname = $_POST['policy_holder_surname'];
    $idno = $_POST['policy_holder_idno'];
    $cellno = $_POST['policy_holder_cellno'];
    $email = $_POST['policy_holder_email'];
    $employment_status = $_POST['employment_status'];
    $employment_industry = $_POST['employment_industry'];

    //calulcated values
    $id_info = parseSAID($idno);
    $gender = $id_info['gender'];
    $citizenship = $id_info['citizenship'];
    $valid = $id_info['valid'];
    $dob = $id_info['date_of_birth'];

    // Ensure arrays have the same number of elements to avoid errors
    $json = array();

    //build json array of values
    $policy_holderItem = array(
        'name' => $name,
        'surname' => $surname,
        'date_of_birth' => $dob,
        'gender' => $gender,
        'mobile_number' => $cellno,
        'email_address' => $email,
        'relationship_to_main' => 'MAIN',
        'id_type' => 'IDENTITY_DOCUMENT',
        'id_value' => $idno,
        'id_expiry_date' => '2080-01-01',
        'id_country_of_issue' => 'ZAF',
        'id_issued_by' => 'DHA',
        'id_validated_by' => '',
        'id_validated_when' => '',
        'employment_status' => $employment_status,
        'employment_industry' => $employment_industry,
        'activationid' => $activationid
    );
    $jsonPolicyHolder = json_encode($policy_holderItem, JSON_PRETTY_PRINT);
    $timestamp = date('Ymd_His');
    $fh = fopen('_mainlife_' . $timestamp . '.json', 'w+');
    fwrite($fh, $jsonPolicyHolder);
    fclose($fh);

    //submit to API
    //get activation details.
    $url = 'https://api.blu.insure/policy/main';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPolicyHolder);
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
    }

    //update activation table
    if($_GET['debug'] == 1){
        header('Content-type: application/json');
        $json = json_encode($policy_holderItem, JSON_PRETTY_PRINT);
        echo $json;
    } else {
        //redirect
        if($product_name == 'Sanlam Individual Funeral Cover'){
            //No dependents go directly to beneficiaries
            $location = 'https://blu.insure/beneficiaries.php?id=' . $id;
        } elseif($product_name == 'Sanlam Family Funeral Cover') {
            $location = 'https://blu.insure/spouse.php?id=' . $id;
        } elseif ($product_name == 'Sanlam Extended Family Funeral Cover'){
            $location = 'https://blu.insure/spouse.php?id=' . $id;
        } else {
            $location = 'error.php?error=Missing product';
        }
        header('Location: ' . $location);

    }
}
?>