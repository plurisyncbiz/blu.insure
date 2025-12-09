<?php
// Load the engine. If this fails, the page stops here.
require_once '_bootstrap.php';

// Logic to ensure we have the name for the "Account Holder" field
// Assuming $data is populated from the API call in your provided logic
$policy_holder_name = $data['data'][0]['policy_holder']['name'] ?? '';
$policy_holder_surname = $data['data'][0]['policy_holder']['surname'] ?? '';
$fullname = trim($policy_holder_name . ' ' . $policy_holder_surname);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activate your Prepaid Funeral Policy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        body { font-family: 'Roboto', sans-serif; }

        /* Consistency for Inputs and Selects */
        .form-floating>.form-control,
        .form-floating>.form-select {
            height: calc(3.5rem + 2px) !important;
            line-height: 1.25;
        }
        .form-floating>label {
            padding: 1rem 0.75rem;
        }

        /* Info Box Styling (Matches Screenshot) */
        .info-box-blue {
            background-color: #2672bf; /* Slightly distinct blue from screenshot or keep #0075C9 */
            color: white;
            border-radius: 5px;
            padding: 15px;
            display: flex;
            align-items: flex-start;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .info-box-blue .bi {
            font-size: 1.3rem;
            margin-right: 12px;
            margin-top: -2px;
        }

        /* Section Headers */
        .section-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: #212529;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        /* Checkbox Styling */
        .form-check-input {
            width: 1.3em;
            height: 1.3em;
            margin-top: 0.1em;
            border: 2px solid #6c757d;
        }
        .form-check-label {
            margin-left: 0.5rem;
            font-size: 0.95rem;
            color: #212529;
        }

        .progress-step-gap { margin-right: 2px; }
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

            <div class="col" id="payments_header_section">
                <div class="container-fluid bg-white p-3">
                    <div class="d-flex align-items-center">
                        <img src="img/sanlam_small_logo.png" alt="Sanlam" style="max-width: 30px;" class="me-4" />
                        <span><?php echo htmlspecialchars($product_description); ?></span>
                    </div>
                </div>

                <div class="col pt-0 pb-0 bg-white">
                    <div class="d-flex" style="height: 5px; background-color: #d9dce1;">
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #0075C9 !important;"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;"></div>
                        <div class="progress-bar" role="progressbar" style="width: 20%; background-color: #8faed3 !important;"></div>
                    </div>
                </div>

                <div class="col p-3" style="background-color: #0075C9;">
                    <p class="text-white lead fw-semibold m-0">Payment details</p>
                </div>
            </div>

            <div class="col" id="payments_section">
                <div class="col p-3" style="background-color: #ffffff;">

                    <div class="info-box-blue mb-4">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            The policyholder must be the bank account holder and policy payer. We do not accept third-party payers.
                        </div>
                    </div>

                    <form action="_payment.php" method="post" class="needs-validation" novalidate>

                        <div class="form-floating mb-4">
                            <select name="source_of_funds" class="form-select form-select-lg" id="source_of_funds" required>
                                <option value="" selected disabled>Select source...</option>
                                <option value="SALARY">Salary</option>
                                <option value="SAVINGS">Savings</option>
                                <option value="INHERITANCE">Inheritance</option>
                            </select>
                            <label for="source_of_funds">Source of funds</label>
                        </div>

                        <div class="section-title">Bank details</div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control form-control-lg bg-light" id="acc_holder" placeholder="Account Holder" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                            <label for="acc_holder">Account holder name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="acc_no" class="form-control form-control-lg" id="acc_no" placeholder="Account Number" inputmode="numeric" required>
                            <label for="acc_no">Bank account number</label>
                            <div class="invalid-feedback">Valid account number required.</div>
                        </div>

                        <div class="form-floating mb-4">
                            <select name="bank" class="form-select form-select-lg" id="bank" required>
                                <option value="" selected disabled>Select bank...</option>
                                <option value="ABSA">ABSA</option>
                                <option value="ACCESSBANK">Access Bank</option>
                                <option value="AFRICANBANK">African Bank</option>
                                <option value="CAPITEC">Capitec Bank</option>
                                <option value="FINBOND">Finbond Mutual Bank</option>
                                <option value="FNB">FNB</option>
                                <option value="NEDBANK">Nedbank</option>
                                <option value="SBSA">Standard Bank</option>
                                <option value="TYMEBANK">Tyme Bank</option>
                            </select>
                            <label for="bank">Bank name</label>
                        </div>

                        <div class="d-flex mb-4 p-2">
                            <div class="me-3">
                                <input type="checkbox" name="payment_terms" class="form-check-input" id="payment_terms" required>
                            </div>
                            <div>
                                <label class="form-check-label lh-sm" for="payment_terms">
                                    I confirm this once-off payment of <strong>R<?php echo htmlspecialchars($product_price); ?></strong> via debit order.
                                </label>
                                <div class="text-muted small mt-2">
                                    This payment will appear on your bank statement with the reference <strong>UBELONG <?php echo htmlspecialchars($serialno); ?></strong>.
                                </div>
                                <div class="invalid-feedback">You must confirm the payment terms.</div>
                            </div>
                        </div>

                        <hr class="my-4"/>

                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="w-100 btn btn-outline-primary btn-lg" style="background-color: #ffffff !important; border-color: #0075C9 !important; color: #0075C9;" onclick="history.back()">Back</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="w-100 btn btn-primary btn-lg" style="background-color: #0075C9 !important; border-color: #0075C9 !important; color: white;">Pay now</button>
                            </div>
                        </div>

                        <input type="hidden" name="policy_holder_cellno" value="<?php echo htmlspecialchars($cellno); ?>">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                    </form>

                    <p class="text-center small fw-semibold pt-5">Underwritten by Sanlam Developing Markets</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="my-5 pt-5 text-muted text-center text-small">
    <?php if(isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] !== 'production'): ?>
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