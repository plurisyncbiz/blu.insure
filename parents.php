<?php
$id = (string)$_GET['id'];
$debug = (string)$_GET['debug'];
$location = 'data/' . $id . '.json';
$json = file_get_contents($location);
$ip = $_SERVER['REMOTE_ADDR'];
$data = json_decode($json, true);
$product_code = (string) $data['data'][0]['product_code'];
$product_description = (string) $data['data'][0]['product_description'];
$cellno = (string) $data['data'][0]['cellno'];

$currentYear = date('Y');
$lowerYear = date('Y', strtotime('-85 years'));
$higherYear = date('Y', strtotime('-21 years'));
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
    <div class="py-3 text-center">
      <img class="d-block mx-auto mb-4" src="https://www.blucreditdeals.co.za/v1/img/bluapproved_logo_landscape.png" alt="" width="200" >
      <h2>Add Extended Family to Your Prepaid <?php echo $product_description; ?></h2>
      <p class="lead">Add upto 2 parents. Parents must be between 21 and 85 years old.</p>
    </div>

    <div class="row g-5">
      <div class="col">
          <form class="needs-validation" action="_parents.php" method="post" novalidate>
              <div id="beneficiary_section" class="bg-light">
                  <p class="h4">First Parent</p>
                  <div class="row g-3 p-3 beneficiary-entry">
                      <div class="col-sm-6">
                          <label for="parent_name[]" class="form-label">First name</label>
                          <input name="parent_name[]" type="text" class="form-control form-control-lg" id="benname" placeholder="First Name" value="" required>
                          <div class="invalid-feedback">
                              Valid first name is required.
                          </div>
                      </div>
                      <div class="col-sm-6">
                          <label for="parent_surname" class="form-label">Surname</label>
                          <input name="parent_surname[]" type="text" class="form-control form-control-lg" id="bensurname" placeholder="Surname" value="" required>
                          <div class="invalid-feedback">
                              Valid last name is required.
                          </div>
                      </div>
                      <div class="col-12">
                          <label for="day" class="form-label">Date of Birth:</label>
                          <div class="row">
                              <div class="col-xs-3 col-sm-3 col-md-3">
                                  <select id="day" name="parent_dob_day[]" class="form-select form-select-lg mb-3" required>
                                      <option value="">Day</option>
                                      <?php for ($i = 1; $i <= 31; $i++) {
                                          echo "<option value='$i'>$i</option>";
                                      }
                                      ?>
                                  </select>
                              </div>
                              <div class="col-xs-3 col-sm-3 col-md-3">
                                  <select id="month" name="parent_dob_month[]" class="form-select form-select-lg mb-3" required>
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
                                  <select id="year" name="parent_dob_year[]" class="form-select form-select-lg mb-3" required>
                                      <option value="">Year</option>
                                      <?php for ($i = $lowerYear; $i <= $higherYear; $i++){
                                          echo "<option value='$i'>$i</option>";
                                      }
                                      ?>
                                  </select>

                              </div>
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <label for="gender" class="form-label">Choose Gender</label>
                          <select name="parent_gender[]" class="form-select form-select-lg mb-3" required>
                              <option value="">--- choose gender ---</option>
                              <option value="female">Female</option>
                              <option value="male">Male</option>
                          </select>
                          <div class="invalid-feedback">
                              Valid gender is required.
                          </div>
                      </div>
                      <div class="col-12">
                          <label for="benidno" class="form-label">South African Identity Number</label>
                          <div class="input-group has-validation">
                              <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                              <input name="parent_idno[]" type="text" class="form-control form-control-lg" id="benidno" placeholder="South African Identity Number">
                              <div class="invalid-feedback">
                                  Please enter a valid South African Identity Number.
                              </div>
                          </div>
                      </div>
                      <div class="col-12">
                          <label for="bencellno" class="form-label">Cellphone Number:</label>
                          <div class="input-group has-validation">
                              <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                              <input name="parent_cellno[]" type="text" class="form-control form-control-lg" id="bencellno" placeholder="0831231234" pattern="^0(6[012345678][0-9]{7}|7[1234689][0-9]{7}|8[1234][0-9]{7})$">
                                  <div class="invalid-feedback">
                                  Please enter a valid South African Cellphone Number. Must be 10 digits and in local format 0831231234
                              </div>
                          </div>
                      </div>
                      <hr/>
                  </div>
              </div>
              <div class="row g-3 p-3">
                  <div class="col-4 pb-3">
                      <button id="AddSecond"  type="button" class="w-100 btn btn-primary">
                          <i class="bi bi-plus-circle-fill"></i> Add Second Parent
                      </button>
                  </div>
                  <div class="col-4 pb-3">
                      <button id="DeleteSecond"  type="button" class="w-100 btn btn-danger">
                          <i class="bi bi-trash-fill"></i> Delete Parent
                      </button>
                  </div>
                  <div class="col-4 pb-3">
                      <button id="Submit"  type="submit" class="w-100 btn btn-success">
                          <i class="bi bi-check-circle-fill"></i> Add Parents
                      </button>
                  </div>
              </div>

              <input type="hidden" name="policy_holder_cellno" value="<?php echo $cellno; ?>">
              <input type="hidden" name="id" value="<?php echo $id; ?>">
              <input type="hidden" name="debug" value="<?php echo $debug; ?>">
          </form>
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
<!-- Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script>
      $(document).ready(function() {
          //cannot delete
          $('#DeleteSecond').prop('disabled', true);

          $('#AddSecond').on('click', function() {
              $('#AddSecond').prop('disabled', true);
              //now you can delete
              $('#DeleteSecond').prop('disabled', false);
              //clone the first entry
              var newContactEntry = $('.beneficiary-entry:first').clone();
              newContactEntry.find('input').val(''); // Clear values in the new fields
              $('#beneficiary_section').append(newContactEntry);
              $('.beneficiary-entry:last').prepend("<p class='h4'>Second Parent</p>")
          });
          $('#DeleteSecond').click(function() {
              // Code to be executed when the element is clicked
              $(".beneficiary-entry:last").attr("style", "display:none");
              //now you can't delete
              $('#DeleteSecond').prop('disabled', true);
              //now you can add another item
              $('#AddSecond').prop('disabled', false);

          });
      });
  </script>
  </body>
</html>
