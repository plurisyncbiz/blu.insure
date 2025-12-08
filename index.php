<?php
ini_set('display_errors', 0);
if(!isset($_GET['id'])){
    header('Location: error.php');
    exit;
}
$id = (string)$_GET['id'];
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
    //fetch product details
    $ip = $_SERVER['REMOTE_ADDR'];
    $data = json_decode($json, true);
    $product_code = (string) $data['data'][0]['product_code'];
    $cellno = (string) $data['data'][0]['cellno'];
    $product_description = (string) $data['data'][0]['product_name'];
    $product_price = (string) $data['data'][0]['product_price'];
    $product_config = (string) $data['data'][0]['product_configuration'];
    $options = json_decode($product_config, true);
    $term = (string) $options['term'];
    $cover = (string) $options['cover'];
    $activationid = $data['data'][0]['activationid'];
}

if(is_null($activationid)){
    header('Location: error.php?st=500&error=You have not activted this policy yet.');
    exit;
}
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

      .custom-floating {
          padding-top: 0.2rem;  /* reduce top padding */
          padding-bottom: 0.2rem; /* optional: reduce bottom padding too */
          height: auto; /* adjust if needed */
      }

      .form-floating>label {
          padding: .5rem .5rem !important;
      }

        .progress-step-gap {
            /* Ensures the gap has a visible color (like the background) */
            margin-right: 2px; /* Adjust this value for a wider/smaller gap */
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

  <div class="container justify-content-center align-items-center">
      <div class="row">
          <div class="col-md-6 mx-auto">
              <div class="py-1">
                  <img class="d-block mx-auto mb-4 mt-2" src="https://www.blucreditdeals.co.za/v1/img/bluapproved_logo_landscape.png" alt="" width="150" >
              </div>
              <div class="col" id="payments_header_section">
                  <div class="container-fluid bg-white p-3">
                      <div class="d-flex align-items-center">
                          <img src="img/SanlamLogomark.png" alt="Sanlam Prepaid Funeral Cover" style="max-width: 30px;" class="me-4" />
                          <span>Sanlam Prepaid Funeral Cover</span>
                      </div>
                  </div>
                  <div class="col pt-0 pb-0 bg-white">
                      <div class="d-flex" style="height: 5px; background-color: #d9dce1;">
                          <div class="progress-bar progress-step-gap" role="progressbar"
                               style="width: 20%; background-color: #0075C9 !important;"
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
                               style="width: 20%; background-color: #8faed3 !important;"
                               aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                          </div>
                      </div>
                  </div>
                  <div class="col p-3" style="background-color: #0075C9;">
                      <p class="text-white lead fw-semibold">Policyholder details</p>
                  </div>
              </div>
              <div class="col" id="payments_section">
                  <div class="col p-3" style="background-color: #ffffff;">
                      <p class="mb-1 fw-bold"><?php echo $product_description; ?></p>
                      <p class="pb-1">You are buying <span>R&nbsp;<?php echo number_format($cover, 0, '.', ','); ?></span> cover for <?php echo $term; ?> Months at a once-off payment of R<?php echo $product_price; ?> </p>
                      <p class="lead">Complete your personal details to activate your policy</p>
                      <div class="d-flex align-items-start p-3 rounded text-white fs-6" style="background-color: #0075C9;">
                          <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                          <div>
                              <p class="mb-0 fon">
                                Your prepaid <?php echo $product_description; ?> will be active for <?php echo $term; ?> months.
                              </p>
                          </div>
                      </div>
                      <form action="_mainlife.php" method="post" class="needs-validation mt-3" novalidate>
                          <div class="col pt-3">
                              <div class="form-floating">
                                  <input type="text" name="policy_holder_name" class="form-control form-control-lg" id="name" placeholder="First Name" value="" required>
                                  <label for="name" class="form-label">First names</label>
                                  <small class="text-muted">same as your ID</small>
                                  <div class="invalid-feedback">
                                      Valid first name(s) are required.
                                  </div>
                              </div>

                          </div>
                          <div class="col pt-3">
                              <div class="form-floating">
                                  <input type="text" name="policy_holder_surname" class="form-control form-control-lg" id="surname" placeholder="Surname" value="" required>
                                  <label for="surname" class="form-label">Surname</label>
                                  <small class="text-muted">same as your ID</small>
                                  <div class="invalid-feedback">
                                      Valid surname is required.
                                  </div>
                              </div>

                          </div>
                          <div class="col pt-3">
                              <div class="form-floating">
                                  <input type="text" name="policy_holder_idno" class="form-control form-control-lg" id="idno" placeholder="South African Identity Number" inputmode="numeric" pattern="^(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))" required>
                                  <label for="idno" class="form-label">South African Identity Number</label>
                                  <div class="invalid-feedback">
                                      Please enter a valid South African Identity Number, we will use this to verify your identity (KYC).
                                  </div>
                              </div>
                          </div>
                          <div class="col pt-3">
                              <div class="form-floating">
                                  <div class="form-control form-control-lg text-muted bg-light"><?php echo $cellno; ?></div>
                                  <p class="fs-6 pt-1"><span class="text-decoration-underline">Why can't you edit this</span>? This is the number you used when activating your policy. Your cellphone number is the key to your policy. Please email us on info@blu.insure should you wish to change the cellphone number</p>
                                  <label for="cellno" class="form-label">Cellphone Number</label>
                              </div>
                          </div>
                          <div class="col pt-3">
                              <div class="form-floating">
                                  <input type="text" name="policy_holder_email" class="form-control form-control-lg" id="email" placeholder="First Name" value="">
                                  <label for="email" class="form-label ">Email address</label>
                                  <small class="text-muted">optional</small>
                              </div>
                              <div class="invalid-feedback">
                                  Valid email address is required.
                              </div>
                          </div>
                          <div class="col pt-3">
                              <div class="form-floating">
                                  <select name="employment_status" class="form-select form-select-lg">
                                      <option value=""></option>
                                      <option value="EMPLOYED">Employed</option>
                                      <option value="OTHER">Other</option>
                                      <option value="RETIRED">Retired</option>
                                      <option value="SELF_EMPLOYED">Self Employed</option>
                                      <option value="STUDENT">Student</option>
                                      <option value="UNEMPLOYED">Unemployed</option>
                                  </select>
                                  <label for="employment_status" class="form-label text-muted">What is your employment status</label>
                              </div>
                          </div>
                          <div class="col pt-3">
                              <div class="form-floating">
                                  <select name="employment_industry" class="form-select form-select-lg mb-3" >
                                      <option value=""></option>
                                      <option value="AGRICULTURE_FORESTRY_AND_FISHING">Agriculture, Forestry & Fishing</option>
                                      <option value="ADULT_ENTERTAINMENT">Adult Entertainment</option>
                                      <option value="ARTS_ENTERTAINMENT_AND_RECREATION">Arts, Entertainment & Recreation</option>
                                      <option value="BROADCASTING_AND_ENTERTAINMENT">Broadcasting & Entertainment</option>
                                      <option value="CHEMICAL_ENGINEERING_MANUFACTURING">Chemical Engineering & Manufacturing</option>
                                      <option value="ELECTRICITY_SOLAR_WATER_GAS_AND_WASTE_SERVICES">Electricity, Solar, Water, Gas & Waste Services</option>
                                      <option value="EXTRACTIVE_SERVICES_MINING_AND_QUARRYING">Mining & Quarrying</option>
                                      <option value="ENTREPRENEURSHIP">Entrepreneurship</option>
                                      <option value="ESTATE_LIVING_AND_FAMILY_TRUSTS">Estate, Living & Family Trusts</option>
                                      <option value="FINANCIAL_AND_INSURANCE">Financial & Insurance</option>
                                      <option value="LEGAL_PRACTITIONER">Legal Practitioner</option>
                                      <option value="PROFESSIONAL_SPORT">Professional Sport</option>
                                      <option value="REAL_ESTATE_AND_PROPERTY_SERVICES">Real Estate & Property Services</option>
                                      <option value="SHELL_BANKING">Banking</option>
                                      <option value="VIRTUAL_CURRENCIES">Virtual Currencies</option>
                                      <option value="EDUCATION">Education</option>
                                      <option value="GOVERNMENT_SERVICES_ARMS_AND_STATE_OWNED_ENTERPRISES">Government</option>
                                      <option value="HEALTHCARE_AND_MEDICAL">Healthcare & Medical</option>
                                      <option value="PFMA_SCHEDULE_1_CONSTITUTIONAL_INSTITUTIONS">State Owned Enterprise (Constitutional)</option>
                                      <option value="PFMA_SCHEDULE_2_MAJOR_PUBLIC_ENTITIES">State Owned Enterprise (commercial)</option>
                                      <option value="PFMA_SCHEDULE_3A_NATIONAL_PUBLIC_ENTITIES">State Owned Enterprise (Non-commercial)</option>
                                      <option value="ADMINISTRATIVE_AND_SUPPORT_SERVICES">Administration</option>
                                      <option value="COMMUNITY_AND_SOCIAL_ACTIVITIES">Social Services</option>
                                      <option value="GAMBLING">Gambling</option>
                                      <option value="INFORMATION_TECHNOLOGY_COMMUNICATION_AND_TELECOMS">Information Technology & Telecommunications</option>
                                      <option value="MANUFACTURING">Manufacturing</option>
                                      <option value="MOTOR_WHOLESALE_RETAIL_TRADE_AND_REPAIR">Motor Vehicle Trade & Repair</option>
                                      <option value="NON_PROFIT_ORGANISATION_REGULATED_CHARITY">Non-profit Charity</option>
                                      <option value="NON_GOVERNMENT_ORGANISATION_NGO">Non-governmental Organisation</option>
                                      <option value="TRANSPORT_STORAGE_COURIER_AND_FREIGHT">Transport</option>
                                      <option value="TRAVEL_TOURISM_ACCOMMODATION_AND_FOOD_SERVICES">Travel & Tourism</option>
                                      <option value="OTHER">Other</option>
                                  </select>
                                  <label for="employment_industry" class="form-label">What Industry are You Employed in?</label>
                              </div>
                          </div>
                          <hr />
                          <div class="row">
                              <div class="col-6">
                                  <button class="w-100 btn btn-outline-primary btn-lg" style="background-color: #ffffff !important; border-color: #0075C9 !important; color: #0075C9;">Back</button>
                              </div>
                              <div class="col-6">
                                  <button type="submit" class="w-100 btn btn-primary btn-lg" style="background-color: #0075C9 !important; border-color: #0075C9 !important; color: white;">Continue</button>
                              </div>
                          </div>

                          <!-- hidden fields -->
                          <input type="hidden" name="policy_holder_cellno" value="<?php echo $cellno; ?>">
                          <input type="hidden" name="id" value="<?php echo $id; ?>">

                      </form>
                  </div>
              </div>
          </div>

      </div>
  </div>

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
<!-- Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  </body>
</html>
