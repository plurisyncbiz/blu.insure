<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Generate a version 4 (random) UUID
    $uuid = 'ABCD';
    // Convert to string for storage in MySQL (VARCHAR(36))
    $parent_uuid = $uuid;

    //get the request
    $id = $_POST['id'];
    $debug = $_POST['debug'];
    //print_r($_POST);
    //die();
    //retrieve data from API
    $location = 'data/' . $id . '.json';
    $json = file_get_contents($location);
    $data = json_decode($json, true);

    //populate schema values
    $product_code = (string) $data['data'][0]['product_code'];
    $product_description = (string) $data['data'][0]['product_description'];
    $cellno = (string) $data['data'][0]['cellno'];
    $term = (string) $data['data'][0]['product_term'];

    $names = $_POST['parent_name'];
    $surnames = $_POST['parent_surname'];
    $dob_days = $_POST['parent_dob_day'];
    $dob_months = $_POST['parent_dob_month'];
    $dob_years = $_POST['parent_dob_year'];
    $idnos = $_POST['parent_idno'];
    $cellnos = $_POST['parent_cellno'];
    $genders = $_POST['parent_gender'];

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
            $formattedDate = date("d-m-Y", $unixTimestamp);

            //build json array of values
            $parentItem[] = array(
                'product_short_code' => $product_code,
                'external_product_life_id' => $parent_uuid,
                'name' => $currentName,
                'surname' => $currentSurname,
                'date_of_birth' => $formattedDate,
                'gender' => $currentGender,
                'mobile_number' => $currentCellno,
                'email_address' => '',
                'relationship_to_main' => 'PARENT',
                'id' => array(
                    'id_type' => 'IDENTITY_DOCUMENT',
                    'id_value' => $currentIdno,
                    'id_expiry_date' => '2080-01-01',
                    'id_country_of_issue' => 'ZAF',
                    'id_issued_by' => 'DHA',
                    'id_validated_by' => '',
                    'id_validated_when' => '')
            );

        }


        //update activation table
        if ($debug == 1) {
            header('Content-type: application/json');
            $json = json_encode($parentItem, JSON_PRETTY_PRINT);
            echo $json;
        } else {
            //redirect
            if ($product_description == 'Individual Bundle') {
                //No dependents go directly to beneficiaries
                $location = 'https://blu.insure/beneficiaries.php?id=' . $id;
            } elseif ($product_description == 'Sanlam Family Funeral Cover') {
                $location = 'https://blu.insure/beneficiaries.php?id=' . $id;
            } elseif ($product_description == 'Sanlam Extended Family Funeral Cover') {
                $location = 'https://blu.insure/beneficiaries.php?id=' . $id;
            } else {
                $location = 'error.php';
            }
            header('Location: ' . $location);
        }
    }
}
?>