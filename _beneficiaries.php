<?php
function parseSAID($idNumber) {
    // Validate basic format
    if (strlen($idNumber) != 13 || !ctype_digit($idNumber)) {
        return ["error" => "Invalid ID number format"];
    }

    // --- Date of Birth ---
    $dobPart = substr($idNumber, 0, 6);
    $year = intval(substr($dobPart, 0, 2));
    $month = intval(substr($dobPart, 2, 2));
    $day = intval(substr($dobPart, 4, 2));

    // Determine century
    $currentYear = intval(date("Y"));
    $currentCentury = intval(substr($currentYear, 0, 2));
    $fullYear = intval($currentCentury . $year);
    if ($fullYear > $currentYear) {
        $fullYear -= 100;
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


/* Output:
Array
(
    [date_of_birth] => 1980-01-01
    [gender] => Male
    [citizenship] => SA Citizen
    [valid] => 1
)
*/
//ini_set('display_errors', true);
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


    //populate schema values
    $product_code = (string) $data['data'][0]['product_code'];
    $product_description = (string) $data['data'][0]['product_name'];
    //$cellno = (string) $data['data'][0]['cellno'];
    $term = (string) $data['data'][0]['product_term'];
    $activationid = (string) $data['data'][0]['activationid'];


    $names = $_POST['beneficiary_name'];
    $surnames = $_POST['beneficiary_surname'];
    $dobs = $_POST['beneficiary_dob'];
    $idnos = $_POST['beneficiary_idno'];
    $cellnos = $_POST['beneficiary_cellno'];
    $genders = $_POST['beneficiary_gender'];
    $emails = $_POST['beneficiary_email'];

    // Ensure arrays have the same number of elements to avoid errors
    if (count($names) === count($surnames)) {
        $json = array();
        for ($i = 0; $i < count($names); $i++) {
            $currentName = htmlspecialchars($names[$i]);
            $currentSurname = htmlspecialchars($surnames[$i]);
            $currentDob = htmlspecialchars($dobs[$i]);
            $currentIdno = htmlspecialchars($idnos[$i]);
            $currentGender = htmlspecialchars($genders[$i]);
            $currentCellno = htmlspecialchars($cellnos[$i]);
            $currentEmail = htmlspecialchars($emails[$i]);


            //build json array of values
            $beneficiaries = array(
                'name' => $currentName,
                'surname' => $currentSurname,
                'date_of_birth' => $currentDob,
                'gender' => $currentGender,
                'mobile_number' => $currentCellno,
                'email_address' => $currentEmail,
                'relationship_to_main' => 'BENEFICIARY',
                'id_type' => 'IDENTITY_DOCUMENT',
                'id_value' => $currentIdno,
                'id_expiry_date' => '2080-01-01',
                'id_country_of_issue' => 'ZAF',
                'id_issued_by' => 'DHA',
                'id_validated_by' => '',
                'id_validated_when' => '',
                'activationid' => $activationid
            );

            $payload['beneficiaries'] = [$beneficiaries];

            //submit to API
            //get activation details.
            $url = 'https://api.blu.insure/policy/beneficiary';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($beneficiaries));
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

        }

        $timestamp = date('Ymd_His');
        $fh = fopen('_beneficiaries_' . $timestamp . '.json', 'w+');
        fwrite($fh, json_encode($payload, JSON_PRETTY_PRINT));
        fclose($fh);


        //update activation table
        if ($_POST['debug'] == 1) {
            header('Content-type: application/json');
            $json = json_encode($beneficiaries, JSON_PRETTY_PRINT);
            echo $json;
        } else {
            //redirect
            if ($product_description == 'Sanlam Individual Funeral Cover') {
                //No dependents go directly to beneficiaries
                $location = 'https://blu.insure/payment.php?id=' . $id;
            } elseif ($product_description == 'Sanlam Family Funeral Cover') {
                $location = 'https://blu.insure/payment.php?id=' . $id;
            } elseif ($product_description == 'Sanlam Extended Family Funeral Cover') {
                $location = 'https://blu.insure/payment.php?id=' . $id;
            } else {
                $location = 'error.php';
            }
            header('Location: ' . $location);
        }
    }
}
?>