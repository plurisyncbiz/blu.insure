<?php
$ip = $_SERVER['REMOTE_ADDR'];
$uniqid = '4tkDn';
//set the submission url
$url = "https://blt-api.blds-leads.com/v1/click/link/" . $uniqid;

//Call the CURL options and return value.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

//get result or error;
$click_result = curl_exec($ch);
$err = curl_error($ch);
$click_results = json_decode($click_result);
if($click_results->status == 'error'){
    header("Location: https://offers.blu.deals/sanlampp/error.php", true, 301);
    exit();
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
    <title>Activate Your Policy Example</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">

    

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <!-- Datepicker CSS -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
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
      <h2>Activate Your Prepaid Funeral Policy</h2>
      <p class="lead">Please complete your details</p>
    </div>

    <div class="row g-5">
      <div class="col-md-5 col-lg-5 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Your Products</span>
          <span class="badge bg-primary rounded-pill">1</span>
        </h4>
        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Sanlam Prepaid Funeral Policy</h6>
              <small class="text-muted">Cover: R10 000</small>
                <br />
              <small class="text-muted">Term: 12 Months</small>
                <br />
              <small class="text-muted">Children Covered: upto 5</small>
                <br />
              <small class="text-muted">Spouses Covered: 1</small>
                <br />
              <small class="text-muted">Extended Family Covered: upto 2</small>
                <br />
              <small class="text-muted">Beneficiaries: upto 2</small>
                <br />
              <small class="text-muted">Policy Cell Number: <?php print_r($click_results->data->cellno) ?> </small>

            </div>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total (ZAR)</span>
            <strong>R 450.00</strong>
          </li>
        </ul>

      </div>
      <div class="col-md-7 col-lg-7">
        <h4 class="mb-3">Policyholder Information</h4>
        <p class="lead">This is the person entering into the funeral policy. The Policyholder owns the policy and is the only person entitled to receive payment of the benefits to pay for the funerals of the insured persons. Note: Cover is only available for a Policyholder who is younger than 65 on the start date of the Policy.</p>
        <form class="needs-validation" novalidate>
          <div class="row g-3">
            <div class="col-sm-6">
              <label for="name" class="form-label">First name</label>
              <input type="text" class="form-control" id="name" placeholder="First Name" value="" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>

            <div class="col-sm-6">
              <label for="surname" class="form-label">Surname</label>
              <input type="text" class="form-control" id="surname" placeholder="Surname" value="" required>
              <div class="invalid-feedback">
                Valid last name is required.
              </div>
            </div>

            <div class="col-12">
              <label for="idno" class="form-label">South African Identity Number</label>
              <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                <input type="text" class="form-control" id="idno" placeholder="South African Identity Number" required>
              <div class="invalid-feedback">
                  Please enter a valid South African Identity Number, we will use this to verify your identity (KYC).
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="cellno" class="form-label">Cellphone Number</label>
              <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" class="form-control" value="<?php print_r($click_results->data->cellno) ?>" id="cellno" placeholder="Cellphone number" disabled>
                  <small><a href="#">Why can't you edit this</a>? This is the number you used when activating your policy. Please contact us on 087123456789 to update these details</small>
              <div class="invalid-feedback">
                  Please enter a valid cellphone number, we will send updates about your policy to this number.
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="email" class="form-label">Email <span class="text-muted">(Optional)</span></label>
              <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control" id="email" placeholder="you@email.com">
                <div class="invalid-feedback">
                  Please enter a valid email address for policy updates.
                </div>
              </div>
            </div>


          <hr class="my-4">
          <h4 class="mb-3">Add Dependants</h4>
          <form class="needs-validation" novalidate>
                  <div class="row g-3">
                      <div class="col-sm-6">
                          <label for="benname" class="form-label">First name</label>
                          <input type="text" class="form-control" id="depname" name="depname" placeholder="First Name" value="" required>
                          <div class="invalid-feedback">
                              Valid first name is required.
                          </div>
                      </div>

                      <div class="col-sm-6">
                          <label for="bensurname" class="form-label">Surname</label>
                          <input type="text" class="form-control" id="depsurname" name="depsurname" placeholder="Surname" value="" required>
                          <div class="invalid-feedback">
                              Valid last name is required.
                          </div>
                      </div>
                      <div class="col-12">
                          <label for="day" class="form-label">Date of Birth:</label>
                          <div class="row">
                              <div class="col-xs-3 col-sm-3 col-md-3">
                                  <select id="day" name="dob_day" class="form-select mb-1" required>
                                      <option value="">Day</option>
                                      <?php for ($i = 1; $i <= 31; $i++) {
                                          echo "<option value='$i'>$i</option>";
                                      }
                                      ?>
                                  </select>
                              </div>
                              <div class="col-xs-3 col-sm-3 col-md-3">
                                  <select id="month" name="dob_month" class="form-select mb-1" required>
                                      <option value="">Month</option>
                                      <option value="1">Jan</option>
                                      <option value="2">Feb</option>
                                      <option value="3">Mar</option>
                                      <option value="4">Apr</option>
                                      <option value="5">May</option>
                                      <option value="6">Jun</option>
                                      <option value="7">Jul</option>
                                      <option value="8">Aug</option>
                                      <option value="9">Sep</option>
                                      <option value="10">Oct</option>
                                      <option value="11">Nov</option>
                                      <option value="12">Dec</option>
                                  </select>
                              </div>
                              <div class="col-xs-3 col-sm-3 col-md-3">
                                  <select id="year" name="dob_year" class="form-select mb-1" required>
                                      <option value="">Year</option>
                                      <?php for ($i = 1900; $i <= 2002; $i++){
                                          echo "<option value='$i'>$i</option>";
                                      }
                                      ?>
                                  </select>
                              </div>
                          </div>
                      </div>
                      <div class="col-12">
                          <label for="depidno" class="form-label">South African Identity Number</label>
                          <div class="input-group has-validation">
                              <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                              <input type="text" class="form-control" id="depidno" placeholder="South African Identity Number">
                          </div>
                      </div>
                  </div>
              </form>


          <hr class="my-4">

          <h4 class="mb-3">Add A Beneficiary</h4>
          <p class="lead">On the death of the Policyholder, the beneficiary stated below will receive the benefits of the policy (100%)</p>
          <form class="needs-validation" novalidate>
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="benname" class="form-label">First name</label>
                <input type="text" class="form-control" id="benname" placeholder="First Name" value="" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <div class="col-sm-6">
                <label for="bensurname" class="form-label">Surname</label>
                <input type="text" class="form-control" id="bensurname" placeholder="Surname" value="" required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>

              <div class="col-12">
                <label for="benidno" class="form-label">South African Identity Number</label>
                <div class="input-group has-validation">
                  <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                  <input type="text" class="form-control" id="benidno" placeholder="South African Identity Number" required>
                  <div class="invalid-feedback">
                    Please enter a valid South African Identity Number, we will use this to verify your identity (KYC).
                  </div>
                </div>
              </div>
            </div>
          </form>



          <hr class="my-4">

          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="dataSharing">
            <label class="form-check-label" for="dataSharing">I understand that the information I have provided will be shared with the Insurer to offer you this policy.</label>
          </div>

          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="privacy">
            <label class="form-check-label" for="privacy">I have read and agree to your <a href="#" target="_blank">privacy policy</a> and <a href="#" target="_blank">terms & conditions</a></label>
          </div>

          <hr class="my-4">

          <h4 class="mb-3">How would you like to make Payment</h4>

          <div class="my-3">
            <div class="form-check">
              <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
              <label class="form-check-label" for="credit">Credit or Debit card</label>
            </div>
            <div class="form-check">
              <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
              <label class="form-check-label" for="debit">DebiCheck (Debit Order)</label>
            </div>
            <div class="form-check">
              <input id="voucher" name="paymentMethod" type="radio" class="form-check-input" required>
              <label class="form-check-label" for="voucher">I have a Blu Approved Voucher</label>
            </div>
          </div>

          <hr class="my-4">

          <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="my-5 pt-5 text-muted text-center text-small">
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
  <script>
      $(document).ready(function() {
          $('#dobInput').datepicker({
              format: 'dd/mm/yyyy', // Desired date format
              startView: 'years', // Start with year selection for DOB
              autoclose: true,
              maxDate: new Date(), // Prevent selecting future dates
              changeMonth: true,
              changeYear: true
          });
      });
  </script>
  </body>
</html>
