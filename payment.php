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
                  <div class="col p-3" style="background-color: #0075C9;">
                      <p class="text-white lead fw-semibold">Payment details</p>
                  </div>
              </div>
              <div class="col" id="payments_section">
                  <div class="col p-3" style="background-color: #ffffff;">
                      <p>Make a once off payment of R<?php echo $product_price; ?></p>
                      <div class="d-flex align-items-start p-3 rounded text-white fs-6" style="background-color: #0075C9;">
                          <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                          <div>
                              <p class="mb-0 fon">The policyholder must be the bank account holder and policy payer. Sanlam does not accept third-party payers</p>
                          </div>
                      </div>
                      <form action="_payment.php" method="post" class="needs-validation mt-3" novalidate>
                          <div class="col-sm-12">
                              <div class="form-floating">
                                  <label for="source_of_funds" class="form-label text-muted">Source of funds</label>
                                  <select name="source_of_funds" class="form-select form-select-lg mb-3 custom-floating fs-6 text-dark" required>
                                      <option value=""></option>
                                      <option value="SALARY">Salary</option>
                                      <option value="SAVINGS">Savings</option>
                                      <option value="INHERITANCE">Inheritance</option>
                                  </select>
                              </div>
                          </div>
                          <p>Bank details</p>
                          <div class="col-md-12">
                              <div class="form-floating">
                                  <label for="acc_no" class="form-label text-muted">Account Number</label>
                                  <input type="text" name="acc_no" class="form-control form-control-lg mb-3 custom-floating fs-6 text-dark" id="acc_no" placeholder="Account Number" inputmode="numeric" required>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-floating">
                                  <label for="bank" class="form-label text-muted">Your Bank</label>
                                  <select name="bank" class="form-select form-select-lg mb-3 custom-floating fs-6 text-dark" required>
                                      <option value=""></option>
                                      <option value="ABSA">ABSA</option>
                                      <option value="ACCESSBANK">Access Bank</option>
                                      <option value="AFRICANBANK">African Bank</option>
                                      <option value="CAPITEC">Capitec Bank</option>
                                      <option value="FINBOND">Finbond Mutual Bank</option>
                                      <option value="FNB">First National Bank (FNB)</option>
                                      <option value="NEDBANK">Nedbank</option>
                                      <option value="SBSA">Standard Bank</option>
                                      <option value="TYMEBANK">Tyme Bank</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-12">
                              <div class="form-check">
                                  <input type="checkbox" name="payment_terms" class="form-check-input" id="payment_terms" required>
                                  <label class="form-check-label" for="payment_terms">
                                      I Confirm this is a once-off payment of R <?php echo $product_price; ?> <br><br>
                                      Please check your banking app for your DebiCheck confirmation. We will collect payment unless you reject the DebiCheck mandate.<br><br>
                                      Payments on your bank account will appear as UBELONG along with the reference number - <?php echo $serialno; ?>.
                                  </label>
                              </div>
                          </div>
                          <hr />
                          <div class="row">
                              <div class="col-6">
                                  <button class="w-100 btn btn-outline-primary btn-lg" style="background-color: #ffffff !important; border-color: #0075C9 !important; color: #0075C9;">Go Back</button>
                              </div>
                              <div class="col-6">
                                  <button type="submit" class="w-100 btn btn-primary btn-lg" style="background-color: #0075C9 !important; border-color: #0075C9 !important; color: white;">Pay now</button>
                              </div>
                          </div>
                          <input type="hidden" name="policy_holder_cellno" value="<?php echo $cellno; ?>">
                          <input type="hidden" name="id" value="<?php echo $id; ?>">

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="form-validation.js"></script>
<!-- jQuery (often required by datepickers) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
      $(document).ready(function() {
      });
  </script>
  </body>
</html>
