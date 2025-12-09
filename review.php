<?php
// FILE: review.php

// 1. SETUP
require_once '_bootstrap.php';

// 2. GET ID
$id = (string)($_GET['id'] ?? '');

if(!$id){
    $location = 'error.php?st=400&error=' . urlencode('Policy not activated or invalid link');
    header('Location: ' . $location);
    exit();
}

// 3. FETCH SERIAL DATA (Using Bootstrap Helper)
$data = getSerialData($id);

if(!$data){
    header('Location: error.php?st=500&error=API Error or Data Not Found');
    exit;
}

$activationid = $data['data'][0]['activationid'] ?? null;

if(!$activationid){
    header('Location: error.php?st=404&error=Policy not activated');
    exit;
}

// 4. FETCH POLICY DETAILS (Using Bootstrap Helper)
// We remove the $api_base argument as the bootstrap function handles the env var
$policy_details = getPolicyDetails($activationid);

if(!$policy_details || !isset($policy_details['data'])){
    header('Location: error.php?st=500&error=Could not fetch policy details');
    exit;
}

$policy_data = $policy_details['data'];

// 5. ASSIGN VARIABLES
$product_code = (string) ($data['data'][0]['product_code'] ?? '');
$cellno       = (string) ($data['data'][0]['cellno'] ?? '');
$product_description = (string) ($data['data'][0]['product_name'] ?? '');
$term         = (string) ($data['data'][0]['product_description'] ?? '');
$price        = (string) ($data['data'][0]['product_price'] ?? '');

// Decode Configuration for Cover
$jsonString = $data['data'][0]['product_configuration'] ?? '{}';
$config     = json_decode($jsonString, true);
$cover      = $config['cover'] ?? '0.00';

// Holder Details
$name      = $policy_data['policy_holder']['name'] ?? '';
$surname   = $policy_data['policy_holder']['surname'] ?? '';
$dob       = $policy_data['policy_holder']['date_of_birth'] ?? '';
$id_number = $policy_data['policy_holder']['id_value'] ?? '';
$email     = $policy_data['policy_holder']['email_address'] ?? '';

// Beneficiaries
$beneficiaries = $policy_data['beneficiaries'] ?? [];

// NOTE: The duplicate getPolicyDetails function has been removed from here
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            background-color: #0075c9;
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

        /* Custom class to make the checkbox and text bigger */
        .checkbox-lg .form-check-input {
            transform: scale(1.5); /* Scale the box 1.5x larger */
            margin-left: -1.25rem; /* Adjust alignment */
        }

        .checkbox-lg .form-check-label {
            font-size: 1rem; /* Make the text bigger */
            padding-left: 10px; /* Add space between box and text */
            padding-top: 2px;
        }

        /* Add extra spacing between the two lines */
        .mb-custom {
            margin-top: 25px;
            margin-bottom: 25px;
        }

        /* Custom class to match the label style perfectly */
        .label-text {
            color: #6c757d; /* Bootstrap's text-muted color */
            font-size: 0.875em; /* Equivalent to .small */
            margin-bottom: 0.25rem;
        }
        /* Custom class for the value style */
        .value-text {
            font-weight: 600; /* A bit bolder than standard, matches image */
            font-size: 1.1rem;
            margin-bottom: 1.5rem; /* Spacing below each data point */
        }
        /* Tighter spacing for beneficiaries */
        .beneficiary-block .value-text {
            margin-bottom: 1rem;
        }

        .progress-step-gap {
            /* Ensures the gap has a visible color (like the background) */
            margin-right: 2px; /* Adjust this value for a wider/smaller gap */
        }

    </style>


    <link href="form-validation.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container justify-content-center align-items-center">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="py-1">
                <img class="d-block mx-auto mb-4 mt-2" src="https://www.blucreditdeals.co.za/v1/img/bluapproved_logo_landscape.png" alt="" width="150" >
            </div>
            <div class="col" id="review_details_header_section">
                <div class="container-fluid bg-white p-3">
                    <div class="d-flex align-items-center">
                        <img src="img/sanlam_small_logo.png" alt="Sanlam Prepaid Funeral Cover" style="max-width: 30px;" class="me-4" />
                        <span><?php echo $product_description; ?></span>
                    </div>
                </div>
            </div>
            <div class="col pt-0 pb-0 bg-white">
                <div class="d-flex" style="height: 5px; background-color: #d9dce1;">
                    <div class="progress-bar progress-step-gap" role="progressbar"
                         style="width: 20%; background-color: #8faed3 !important;"
                         aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                    </div>

                    <div class="progress-bar progress-step-gap" role="progressbar"
                         style="width: 20%; background-color: #8faed3 !important;"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>

                    <div class="progress-bar progress-step-gap" role="progressbar"
                         style="width: 20%; background-color: #8faed3 !important;"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>

                    <div class="progress-bar progress-step-gap" role="progressbar"
                         style="width: 20%; background-color: #8faed3 !important;"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>

                    <div class="progress-bar" role="progressbar"
                         style="width: 20%; background-color: #0075C9 !important;"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

            <div class="col" id="review_details_section">
                <div class="col p-3" style="background-color: #ffffff;">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">

                            <h4 class="mb-4 fw-normal">Policy details</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="label-text">Plan</div>
                                    <div class="value-text"><?php echo $product_description ?></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-text">Duration of plan</div>
                                    <div class="value-text"><?php echo $term ?></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-text">Once-off premium</div>
                                    <div class="value-text">R<?php echo $price ?></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-text">Cover</div>
                                    <div class="value-text">R<?php echo $cover ?></div>
                                </div>
                            </div>

                            <hr class="my-2">

                            <h4 class="my-4 fw-normal">Policyholder details</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="label-text">First name</div>
                                    <div class="value-text"><?php echo $name ?></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-text">Surname</div>
                                    <div class="value-text"><?php echo $surname ?></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-text">Cellphone number</div>
                                    <div class="value-text"><?php echo $cellno ?></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-text">Email address</div>
                                    <div class="value-text text-break"><?php echo $email ?></div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="label-text">ID number</div>
                                    <div class="value-text"><?php echo $id_number ?></div>
                                </div>
                            </div>

                            <h4 class="mb-4 fw-normal mt-4">Beneficiary details</h4>
                            <?php foreach($beneficiaries as $beneficiary){ ?>
                                <hr class="my-2">
                                <div class="beneficiary-block mb-3">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="label-text">First names</div>
                                            <div class="value-text"><?php echo $beneficiary['name'] ?></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="label-text">Surname</div>
                                            <div class="value-text"><?php echo $beneficiary['surname'] ?></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="label-text">Payout allocation</div>
                                            <div class="value-text"><?php echo $beneficiary['percentage_allocation'] ?>%</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="label-text">Date of birth</div>
                                            <div class="value-text"><?php echo $beneficiary['date_of_birth'] ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <form id="reviewForm" class="needs-validation" action="thankyou.php" method="post" novalidate>
                        <div class="form-check mt-3 mb-5">
                            <input class="form-check-input" type="checkbox" value="1" id="termsCheckbox" required>
                            <label class="form-check-label" for="termsCheckbox">
                                I confirm that all my details are correct.
                            </label>
                            <div class="invalid-feedback">
                                Please confirm these details are correct.
                            </div>
                        </div>

                        <input type="hidden" name="policy_holder_cellno" value="<?php echo $cellno; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="row mt-3 border-top pt-3">
                            <div class="col-12 mb-2">
                                <button type="submit" class="w-100 btn btn-primary btn-lg" style="background-color: #0075C9 !important; border-color: #0075C9 !important; color: white;">Submit application</button>
                            </div>
                            <div class="col-12 mb-2">
                                <button type="button" id="addContact" class="w-100 btn btn-outline-primary btn-lg" style="background-color: #ffffff !important; border-color: #0075C9 !important; color: #0075C9;" onclick="history.back()">Back</button>
                            </div>
                        </div>
                        <p class="text-center small fw-semibold pt-5">Underwritten by Sanlam Developing Markets, a Licenced Life Insurer & Authorised FSP (11230)</p>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="my-5 pt-5 text-muted text-center text-small">
    <p style="font-size: 0.75em;">
        <?php if(isset($data) && isset($_GET['debug']) && $_GET['debug'] === 'true') print_r($data); ?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    // Bootstrap form validation script
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
</body>
</html>