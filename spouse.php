<?php
$ip = $_SERVER['REMOTE_ADDR'];

$id = (string)$_GET['id'];
$debug = (string)$_GET['debug'];

//api call will go here
$static = array('10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008','10009');
if(in_array($id, $static)){
    $location = 'data/' . $id . '.json';
    $json = file_get_contents($location);
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
}

//file get contents returns false on error or API had no valid data
if(!$json){
    header('Location: error.php');
    exit;
} else {
    //fetch activation details
    $ip = $_SERVER['REMOTE_ADDR'];
    $data = json_decode($json, true);
    $product_code = (string) $data['data'][0]['product_code'];
    $cellno = (string) $data['data'][0]['cellno'];
}

$product_code = (string) $data['data'][0]['product_code'];
$cellno = (string) $data['data'][0]['cellno'];
$product_description = (string) $data['data'][0]['product_name'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Activate your Prepaid Funeral Policy</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">



    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        .form-floating>.form-control,
        .form-floating>.form-control-plaintext,
        .form-floating>.form-select {
            height: calc(2.25rem + 30px) !important;
        }

        .contact-card {
            position: relative;
            padding: 20px 12px 12px 12px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .remove-contact {
            cursor: pointer;
            z-index: 10;
        }
        .info-box {
            display: flex;
            align-items: start;
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 0.5rem;
            background-color: #57b3f6;
            color: #fff;
            font-size: 0.95rem;
        }
        .custom-floating {
            padding-top: 0.2rem;  /* reduce top padding */
            padding-bottom: 0.2rem; /* optional: reduce bottom padding too */
            height: auto; /* adjust if needed */
        }

        .form-floating>label {
            padding: .5rem .5rem !important;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <main>
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="https://www.blucreditdeals.co.za/v1/img/bluapproved_logo_landscape.png" alt="" width="200" >
            <p class="h2">Add a Spouse to Your Prepaid <?php echo $product_description; ?></p>
        </div>

        <div class="row g-5">
            <div class="col-md-12 col-lg-12">
                <form class="mb-3 needs-validation" id="addSpouse" method="post" action="_spouse.php" novalidate>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="spouse_name" class="form-label">First name</label>
                            <input type="text" class="form-control form-control-lg" id="spouse_name" name="spouse_name" placeholder="First Name" value="" required>
                            <div class="invalid-feedback">
                                Valid first name is required.
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="spouse_surname" class="form-label">Surname</label>
                            <input type="text" class="form-control form-control-lg" id="spouse_surname" name="spouse_surname" placeholder="Surname" value="" required>
                            <div class="invalid-feedback">
                                Valid last name is required.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="day" class="form-label">Date of Birth:</label>
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <select id="day" name="spouse_dob_day" class="form-select form-select-lg mb-3" required>
                                        <option value="">Day</option>
                                        <?php for ($i = 1; $i <= 31; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <select id="month" name="spouse_dob_month" class="form-select form-select-lg mb-3" required>
                                        <option value="">Month</option>
                                        <option value="Jan">Jan</option>
                                        <option value="Feb">Feb</option>
                                        <option value="Mar">Mar</option>
                                        <option value="Apr">Apr</option>
                                        <option value="May">May</option>
                                        <option value="Jun">Jun</option>
                                        <option value="Jul">Jul</option>
                                        <option value="Aug">Aug</option>
                                        <option value="Sep">Sep</option>
                                        <option value="Oct">Oct</option>
                                        <option value="Nov">Nov</option>
                                        <option value="Dec">Dec</option>
                                    </select>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <select id="year" name="spouse_dob_year" class="form-select form-select-lg mb-3" required>
                                        <option value="">Year</option>
                                        <?php for ($i = 1900; $i <= 2025; $i++){
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="gender" class="form-label">Choose Gender</label>
                            <select name="spouse_gender" class="form-select form-select-lg mb-3" required>
                                <option value="">--- choose gender ---</option>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                            </select>
                            <div class="invalid-feedback">
                                Valid gender is required.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="spouse_idno" class="form-label">South African Identity Number</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input name="spouse_idno" type="text" class="form-control form-control-lg" id="spouse_idno" pattern="[0-9]{13}" placeholder="South African Identity Number"><div class="invalid-feedback">
                                    The id number you have entered is not valid.
                                </div>

                            </div>
                        </div>

                        <div class="col-12">
                            <label for="spouse_cellno" class="form-label">Cellphone Number</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="spouse_cellno" id="spouse_cellno" class="form-control form-control-lg" placeholder="0831231234">
                            </div>
                        </div>

                        <div class="col-6">
                            <button class="w-100 btn btn-success btn-lg" type="submit" id="addspouse">Add Spouse</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="w-100 btn btn-danger btn-lg" id="nospouse">No Spouse</button>
                        </div>
                    </div>
                    <input type="hidden" name="policy_holder_cellno" value="<?php echo $cellno; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="debug" value="<?php echo $debug; ?>">


                </form>
            </div>
        </div>
    </main>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p style="font-size: 0.75em;">
            <?php print_r($data); ?>
        </p>

        <p class="mb-1">&copy; 2025 Blue Label Data Solutions (Pty) Ltd</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy</a></li>
            <li class="list-inline-item"><a href="#">Terms</a></li>
            <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
    </footer>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="form-validation.js"></script>
<!-- jQuery (often required by datepickers) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#nospouse').click(function (){
            var id = <?php echo $id; ?>;
            var newUrl = "https://blu.insure/children.php?id=" + id;
            $(location).attr('href', newUrl);
        })


</script>
</body>
</html>
