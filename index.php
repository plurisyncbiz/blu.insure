<?php
// Load the engine. If this fails, the page stops here.
require_once '_bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activate your Prepaid Funeral Policy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        body { font-family: 'Roboto', sans-serif; }
        .form-floating>.form-control { height: calc(2.25rem + 30px) !important; }
        .form-floating>label { padding: .5rem .5rem !important; }
        .progress-step-gap { margin-right: 2px; }
    </style>
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
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #0075C9 !important;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col p-3" style="background-color: #0075C9;">
                    <p class="text-white lead fw-semibold">Policyholder details</p>
                </div>
            </div>

            <div class="col" id="payments_section">
                <div class="col p-3" style="background-color: #ffffff;">

                    <p class="mb-1 fw-bold"><?php echo htmlspecialchars($product_description); ?></p>
                    <p class="pb-1">
                        You are buying <span>R&nbsp;<?php echo number_format((float)$cover, 0, '.', ','); ?></span>
                        cover for <?php echo htmlspecialchars($term); ?> Months at a once-off payment of R<?php echo htmlspecialchars($product_price); ?>
                    </p>

                    <p class="lead">Complete your personal details to activate your policy</p>

                    <div class="d-flex align-items-start p-3 rounded text-white fs-6" style="background-color: #0075C9;">
                        <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                        <div>
                            <p class="mb-0 fon">
                                Your prepaid <?php echo htmlspecialchars($product_description); ?> will be active for <?php echo htmlspecialchars($term); ?> months.
                            </p>
                        </div>
                    </div>

                    <form action="_mainlife.php" method="post" class="needs-validation mt-3" novalidate>

                        <div class="col pt-3">
                            <div class="form-floating">
                                <input type="text" name="policy_holder_name" class="form-control form-control-lg" id="name" placeholder="First Name" required>
                                <label for="name" class="form-label">First names</label>
                                <small class="text-muted">same as your ID</small>
                                <div class="invalid-feedback">Valid first name(s) are required.</div>
                            </div>
                        </div>

                        <div class="col pt-3">
                            <div class="form-floating">
                                <input type="text" name="policy_holder_surname" class="form-control form-control-lg" id="surname" placeholder="Surname" required>
                                <label for="surname" class="form-label">Surname</label>
                                <small class="text-muted">same as your ID</small>
                                <div class="invalid-feedback">Valid surname is required.</div>
                            </div>
                        </div>

                        <div class="col pt-3">
                            <div class="form-floating">
                                <input type="text" name="policy_holder_idno" class="form-control form-control-lg" id="idno" placeholder="South African Identity Number"
                                       inputmode="numeric" pattern="^(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))" required>
                                <label for="idno" class="form-label">South African Identity Number</label>
                                <div class="invalid-feedback">Please enter a valid South African Identity Number.</div>
                            </div>
                        </div>

                        <div class="col pt-3">
                            <div class="form-floating">
                                <div class="form-control form-control-lg text-muted bg-light pt-3">
                                    <?php echo htmlspecialchars($cellno); ?>
                                </div>
                                <label for="cellno" class="form-label">Cellphone Number</label>
                                <p class="fs-6 pt-1 text-muted"><small>This number is linked to your policy and cannot be changed here.</small></p>
                            </div>
                        </div>

                        <div class="col pt-3">
                            <div class="form-floating">
                                <input type="email" name="policy_holder_email" class="form-control form-control-lg" id="email" placeholder="Email">
                                <label for="email" class="form-label">Email address</label>
                                <small class="text-muted">optional</small>
                            </div>
                        </div>

                        <div class="col pt-3">
                            <div class="form-floating">
                                <select name="employment_status" class="form-select form-select-lg" required>
                                    <option value="" selected disabled></option>
                                    <option value="EMPLOYED">Employed</option>
                                    <option value="OTHER">Other</option>
                                    <option value="RETIRED">Retired</option>
                                    <option value="SELF_EMPLOYED">Self Employed</option>
                                    <option value="STUDENT">Student</option>
                                    <option value="UNEMPLOYED">Unemployed</option>
                                </select>
                                <label for="employment_status" class="form-label">Employment status</label>
                            </div>
                        </div>

                        <div class="col pt-3">
                            <div class="form-floating">
                                <select name="employment_industry" class="form-select form-select-lg mb-3" required>
                                    <option value="" selected disabled></option>
                                    <option value="AGRICULTURE_FORESTRY_AND_FISHING">Agriculture, Forestry & Fishing</option>
                                    <option value="FINANCIAL_AND_INSURANCE">Financial & Insurance</option>
                                    <option value="GOVERNMENT_SERVICES">Government</option>
                                    <option value="HEALTHCARE_AND_MEDICAL">Healthcare & Medical</option>
                                    <option value="INFORMATION_TECHNOLOGY">IT & Telecoms</option>
                                    <option value="MANUFACTURING">Manufacturing</option>
                                    <option value="TRANSPORT">Transport</option>
                                    <option value="OTHER">Other</option>
                                </select>
                                <label for="employment_industry" class="form-label">Industry</label>
                            </div>
                        </div>

                        <hr />
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="w-100 btn btn-outline-primary btn-lg" onclick="history.back()">Back</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="w-100 btn btn-primary btn-lg">Continue</button>
                            </div>
                        </div>

                        <input type="hidden" name="policy_holder_cellno" value="<?php echo htmlspecialchars($cellno); ?>">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="form-validation.js"></script>
</body>
</html>