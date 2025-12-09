<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
            $location = 'error.php?st=400&description=' . $data['description'];
            header('Location: ' . $location);
            exit();
        }
    }

    //create the life, return uuid

    //populate schema values
    $product_code = (string) $data['data'][0]['product_code'];
    $product_description = (string) $data['data'][0]['product_name'];
    $activationid = (string) $data['data'][0]['activationid'];

    $name = $_POST['spouse_name'];
    $surname = $_POST['spouse_surname'];
    $dob_day = $_POST['spouse_dob_day'];
    $dob_month = $_POST['spouse_dob_month'];
    $dob_year = $_POST['spouse_dob_year'];
    $idno = $_POST['spouse_idno'];
    $cellno = $_POST['spouse_cellno'];
    $gender = $_POST['spouse_gender'];

    //build DOB
    $currentDob = htmlspecialchars($dob_day . '-' . $dob_month . '-' . $dob_year);
    $unixTimestamp = strtotime($currentDob);
    $formattedDate = date("Y-m-d", $unixTimestamp);

    // Ensure arrays have the same number of elements to avoid errors
    $json = array();

    //build json array of values
    $spouseItem = array(
        'name' => $name,
        'surname' => $surname,
        'date_of_birth' => $formattedDate,
        'gender' => $gender,
        'mobile_number' => $cellno,
        'email_address' => '',
        'relationship_to_main' => 'SPOUSE',
        'id_type' => 'IDENTITY_DOCUMENT',
        'id_value' => $idno,
        'id_expiry_date' => '2080-01-01',
        'id_country_of_issue' => 'ZAF',
        'id_issued_by' => 'DHA',
        'id_validated_by' => '',
        'id_validated_when' => '',
        'activationid' => $activationid
    );

    $timestamp = date('Ymd_His');
    $fh = fopen('_spouse_' . $timestamp . '.json', 'w+');
    fwrite($fh, json_encode($spouseItem, JSON_PRETTY_PRINT));
    fclose($fh);

    //print_r($product_description);
    //die();

//update activation table
    if($_GET['debug'] == 1){
        header('Content-type: application/json');
        $json = json_encode($spouseItem, JSON_PRETTY_PRINT);
        echo $json;
    } else {
        //redirect
        if($product_description == 'Individual Bundle'){
            //No dependents go directly to beneficiaries
            $location = 'https://blu.insure/beneficiaries.php?id=' . $id;
        } elseif($product_description == 'Sanlam Family Funeral Cover') {
            $location = 'https://blu.insure/children.php?id=' . $id;
        } elseif ($product_description == 'Sanlam Extended Family Funeral Cover'){
            $location = 'https://blu.insure/children.php?id=' . $id;
        } else {
            $location = 'error.php';
        }
        header('Location: ' . $location);

    }

}
?>