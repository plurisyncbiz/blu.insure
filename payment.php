<?php
// Load the engine.
require_once '_bootstrap.php';

// 1. GET ID
$id = (string)($_GET['id'] ?? '');
if(!$id){
    header('Location: error.php?st=400&error=Invalid Link');
    exit();
}

// 2. FETCH SERIAL DATA (Using Bootstrap Helper)
$data = getSerialData($id);

if(!$data){
    header('Location: error.php?st=404&error=Policy data not found');
    exit();
}

// Extract Variables
$product_description = (string) ($data['data'][0]['product_name'] ?? '');
$product_price       = (string) ($data['data'][0]['product_price'] ?? '');
$serialno            = (string) ($data['data'][0]['serialno'] ?? '');
$cellno              = (string) ($data['data'][0]['cellno'] ?? '');
$activationid        = (string) ($data['data'][0]['activationid'] ?? '');

// 3. FETCH POLICY DETAILS (Using Bootstrap Helper)
$fullname = '';
if($activationid) {
    $policy_data = getPolicyDetails($activationid);
    if($policy_data) {
        $fullname = getPolicyHolderName($policy_data);
    }
}
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

        /* --- FORM FIELD SPACING & CONSISTENCY --- */
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: 3.625rem !important;
            padding-top: 1.625rem !important;
            padding-bottom: 0.625rem !important;
            line-height: 1.25;
        }

        .form-floating > label {
            padding: 1rem 0.75rem !important;
            color: #6c757d;
        }

        .form-control,
        .form-select {
            color: #212529 !important;
            font-size: 1rem !important;
            font-weight: 400;
            border: 1px solid #ced4da;
        }

        /* Readonly Fields (Account Holder) - White Background */
        .form-control:read-only,
        .form-control[readonly] {
            background-color: #fff !important;
            opacity: 1;
        }

        /* Info Box */
        .info-box-blue {
            background-color: #2672bf;
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

        .section-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: #212529;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

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
                            <select name="source_of_funds" class="form-select" id="source_of_funds" required>
                                <option value="" selected disabled>Select source...</option>
                                <option value="SALARY">Salary</option>
                                <option value="SAVINGS">Savings</option>
                                <option value="INHERITANCE">Inheritance</option>
                            </select>
                            <label for="source_of_funds">Source of funds</label>
                        </div>

                        <div class="section-title">Bank details</div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="acc_holder" placeholder="Account Holder" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                            <label for="acc_holder">Account holder name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="acc_no" class="form-control" id="acc_no" placeholder="Account Number" inputmode="numeric" required>
                            <label for="acc_no">Bank account number</label>
                            <div class="invalid-feedback">Valid account number required.</div>
                        </div>

                        <div class="form-floating mb-4">
                            <select name="bank" class="form-select" id="bank" required>
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
                                    I confirm this once-off payment of <strong>R<?php echo htmlspecialchars($product_price); ?></strong> via debit order.<br><br>
                                    Please check your banking app for your DebiCheck confirmation. We will collect payment unless you reject the DebiCheck mandate.<br><br>
                                    This payment will appear on your bank statement with the reference <strong>UBELONG <?php echo htmlspecialchars($serialno); ?></strong>.
                                </label>
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
        <p style="font-size: 0.75em; color: red;">DEBUG: <?php print_r($data); ?></p>
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