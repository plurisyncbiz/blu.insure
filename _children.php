<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a version 4 (random) UUID
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
    $cellno = (string) $data['data'][0]['cellno'];
    $activationid = (string) $data['data'][0]['activationid'];

    //create the life, return uuid

    //populate schema values
    $product_code = (string) $data['data'][0]['product_code'];
    $names = $_POST['child_name'];
    $surnames = $_POST['child_surname'];
    $dob_days = $_POST['child_dob_day'];
    $dob_months = $_POST['child_dob_month'];
    $dob_years = $_POST['child_dob_year'];
    $idnos = $_POST['child_idno'];
    $cellnos = $_POST['child_cellno'];
    $genders = $_POST['child_gender'];

    // Ensure arrays have the same number of elements to avoid errors
    if (count($names) === count($surnames)) {
        $json = array();
        for ($i = 0; $i < count($names); $i++) {
            $currentName = htmlspecialchars($names[$i]);
            $currentSurname = htmlspecialchars($surnames[$i]);
            $currentDob = htmlspecialchars($dob_days[$i] . '-' . $dob_months[$i] . '-' . $dob_years[$i]);
            $currentIdno = htmlspecialchars($idnos[$i]);
            $currentGender = htmlspecialchars($genders[$i]);
            $currentCellno = htmlspecialchars($cellnos[$i]);

            //format the date
            $unixTimestamp = strtotime($currentDob);
            $formattedDate = date("Y-m-d", $unixTimestamp);

            //build json array of values
            $childItem[] = array(
                'name' => $currentName,
                'surname' => $currentSurname,
                'date_of_birth' => $currentDob,
                'gender' => $currentGender,
                'mobile_number' => $cellno,
                'email_address' => '',
                'relationship_to_main' => 'CHILD',
                'id_type' => 'IDENTITY_DOCUMENT',
                'id_value' => $currentIdno,
                'id_expiry_date' => '2080-01-01',
                'id_country_of_issue' => 'ZAF',
                'id_issued_by' => 'DHA',
                'id_validated_by' => '',
                'id_validated_when' => '',
                'activationid' => $activationid
            );
        }

        //update activation table
        if($_GET['debug'] == 1){
            header('Content-type: application/json');
            $json = json_encode($childItem, JSON_PRETTY_PRINT);
            echo $json;
        } else {
            //redirect
            if($product_description == 'Sanlam Individual Funeral Cover'){
                //No dependents go directly to beneficiaries
                $location = 'error.php';
            } elseif($product_description == 'Sanlam Family Funeral Cover') {
                $location = 'https://blu.insure/beneficiaries.php?id=' . $id;
            } elseif ($product_description == 'Sanlam Extended Family Funeral Cover'){
                $location = 'https://blu.insure/parents.php?id=' . $id;
            } else {
                $location = 'error.php';
            }
            header('Location: ' . $location);

        }

    }
}
?>