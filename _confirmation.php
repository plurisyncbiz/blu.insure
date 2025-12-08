<?php
declare(strict_types=1);

// Show all errors, warnings, and notices
error_reporting(E_ALL);

// Display errors on the page
ini_set('display_errors', '1');

// Optional: display startup errors as well
ini_set('display_startup_errors', '1');

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
        //echo $url; die();
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
        } else {
            $location = 'review.php?id=' . $id;
            header('Location: ' . $location);
            exit();
        }
    }

}


?>

