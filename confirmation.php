<?php
// Load the engine. If this fails, the page stops here.
require_once '_bootstrap.php';
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

        .progress-step-gap {
            /* Ensures the gap has a visible color (like the background) */
            margin-right: 2px; /* Adjust this value for a wider/smaller gap */
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
                          <img src="img/sanlam_small_logo.png" alt="Sanlam Prepaid Funeral Cover" style="max-width: 30px;" class="me-4" />
                          <span><?php echo $product_description; ?></span>
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
                               style="width: 20%; background-color: #0075C9 !important;"
                               aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                          </div>

                          <div class="progress-bar" role="progressbar"
                               style="width: 20%; background-color: #8faed3 !important;"
                               aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                          </div>
                      </div>
                  </div>

                  <div class="col p-3" style="background-color: #0075C9;">
                      <p class="text-white lead fw-semibold m-0">Important Information</p>
                  </div>
              </div>
              <div class="col" id="payments_section">
                  <div class="col p-3" style="background-color: #ffffff;">
                      <p class="fw-bold">Please read the information below.</p>
                      <ul class="pt-3 pb-3">
                          <li class="p-1" style="color: #0175c9;"><span style="color: black;">No waiting period for accidental death</span></li>
                          <li class="p-1" style="color: #0175c9;"><span style="color: black;">Three (3) month waiting period for natural death</span></li>
                          <li class="p-1" style="color: #0175c9;"><span style="color: black;">Twelve (12) month waiting period for suicide</span></li>
                          <li class="p-1" style="color: #0175c9;"><span style="color: black;">You may cancel the policy within 31 days for a full refund of any premiums paid</span></li>
                      </ul>
                      <!-- FORM -->
                      <form id="replacementForm" class="needs-validation" action="_confirmation.php" method="post" novalidate>
                          <div class="mb-4 mt-3">
                              <label class="d-block">Is this policy replacing another funeral policy?</label>

                              <div class="form-check checkbox-lg mb-custom">
                                  <input class="form-check-input" type="checkbox" value="1" name="replacement[]" id="checkYes" onclick="selectOnly(this.id)">
                                  <label class="form-check-label" for="checkYes">
                                      Yes
                                  </label>
                              </div>

                              <div class="form-check checkbox-lg">
                                  <input class="form-check-input" type="checkbox" value="0" name="replacement[]"  id="checkNo" onclick="selectOnly(this.id)">
                                  <label class="form-check-label" for="checkNo">
                                      No
                                  </label>
                              </div>
                          </div>
                          <!-- Info box (shown on second card only) -->
                          <div class="info-box mt-3">
                              <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                              <div>If you cancelled this policy less than 30 days ago, or plan to cancel it within 30 days, send us your cancellation letter and policy schedule to qualify for reduced waiting periods.</div>
                          </div>
                          <p class="fw-bold pt-5">Don't have your previous policy documents?</p>
                          <p class="pt-3">That's OK, you can still apply for prepaid funeral cover. Send us your documents within 31 days from today, or our standard waiting period will apply to your policy.</p>
                          <p class="pt-3 pb-3">Send proof of cancellation to digitalfuneralsupport@sanlam.co.za</p>
                          <div class="form-check mt-3 mb-5">
                              <input class="form-check-input" type="checkbox" value="1" id="termsCheckbox" required>
                              <label class="form-check-label" for="termsCheckbox">
                                  I have read and agree to the
                                  <a href="#" class="text-decoration-underline" data-bs-toggle="modal" data-bs-target="#termsModal" onclick="event.stopPropagation()">
                                      Terms and Conditions
                                  </a> about the product
                              </label>
                              <div class="invalid-feedback">
                                  You must accept the terms to proceed.
                              </div>
                          </div>

                          <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                  <div class="modal-content">

                                      <div class="modal-header">
                                          <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>

                                      <div class="modal-body">

                                          <h5 class="fw-bold">When cover ends</h5>
                                          <ul>
                                              <li>If you (the policyholder) pass away</li>
                                              <li>The day after your policy expires</li>
                                              <li>If you instruct us to cancel your policy</li>
                                              <li>If Sanlam cancels your policy (31 days’ notice)</li>
                                          </ul>

                                          <h5 class="fw-bold mt-4">What’s excluded</h5>
                                          <p>We won’t pay a claim if:</p>
                                          <ul>
                                              <li>Any information you provide is false</li>
                                              <li>Death happens after cover starts, but the accident happened before the policy started</li>
                                              <li>Death is related to criminal activities, including war, riots and illegal strikes</li>
                                              <li>Death is caused by nuclear explosions or radioactivity</li>
                                          </ul>

                                          <h5 class="fw-bold mt-4">Cool-off period</h5>
                                          <p>If you change your mind, you can cancel the policy within 31 days as long as no benefits or claims have been paid. We will refund any premiums paid by you, the policyholder, up to the date we received notice of your cancellation.</p>

                                          <h5 class="fw-bold mt-4">Claims and payouts</h5>
                                          <ul>
                                              <li>Most claims are assessed and paid within 4 business hours once we receive all necessary documents.</li>
                                              <li>Beneficiaries must have South African bank accounts in their name.</li>
                                              <li>Claims may be rejected if any information is false or incorrect.</li>
                                          </ul>

                                          <h5 class="fw-bold mt-4">Relationships</h5>
                                          <p>If you’re covering family members, you can add the following people to your Sanlam Prepaid Funeral Cover bundle:</p>

                                          <h6 class="fw-bold mt-3">Spouse</h6>
                                          <p>Your spouse is your husband or wife by marriage.</p>
                                          <p>We consider it a marriage when two people are:</p>
                                          <ul>
                                              <li>Married according to the laws of any sovereign country</li>
                                              <li>Married according to customary or tribal law</li>
                                              <li>Married under any religion that is practised in South Africa</li>
                                              <li>Party to a civil union in terms of the Civil Union Act 2006</li>
                                          </ul>

                                          <h6 class="fw-bold mt-3">Child</h6>
                                          <p>This is your or your spouse’s child. They can be biological, adopted, a stepchild, or under your legal guardianship.</p>

                                          <h6 class="fw-bold mt-3">Parent / parent-in-law</h6>
                                          <p>The person who is your biological parent, parent-in-law, legal guardian or stepparent.</p>

                                          <div class="alert alert-secondary mt-3">
                                              These relationships must already be in place when you apply for cover.
                                          </div>

                                      </div>

                                      <div class="modal-footer p-0">
                                          <button
                                                  type="button"
                                                  class="btn btn-primary w-100 rounded-0 py-3"
                                                  data-bs-dismiss="modal"
                                                  onclick="document.getElementById('termsCheckbox').checked = true;"
                                          >
                                              Close & Agree
                                          </button>
                                      </div>

                                  </div>
                              </div>
                          </div>
                          <input type="hidden" name="policy_holder_cellno" value="<?php echo $cellno; ?>">
                          <input type="hidden" name="id" value="<?php echo $id; ?>">
                          <div class="row mt-3 border-top pt-3">
                              <div class="col-6 mb-2">
                                  <button type="button" id="addContact" class="w-100 btn btn-outline-primary btn-lg" style="background-color: #ffffff !important; border-color: #0075C9 !important; color: #0075C9;">Back</button>
                              </div>
                              <div class="col-6 mb-2">
                                  <button type="submit" class="w-100 btn btn-primary btn-lg" style="background-color: #0075C9 !important; border-color: #0075C9 !important; color: white;">Confirm</button>
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
      <?php if($_ENV['APP_ENV'] !== 'production'): ?>
          <p style="font-size: 0.75em; color: red;">DEBUG: <?php print_r($serial_data); ?></p>
      <?php endif; ?>

      <p class="mb-1">&copy; <?php echo date('Y'); ?> Blue Label Data Solutions (Pty) Ltd</p>
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
      function selectOnly(id) {
          var yes = document.getElementById("checkYes");
          var no = document.getElementById("checkNo");

          // If the user clicked Yes, uncheck No
          if (id === 'checkYes' && yes.checked) {
              no.checked = false;
          }
          // If the user clicked No, uncheck Yes
          if (id === 'checkNo' && no.checked) {
              yes.checked = false;
          }
      }
  </script>
  </body>
</html>
