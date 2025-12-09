<?php
// Load the engine.
require_once '_bootstrap.php';

// 1. GET ID
$id = (string)($_GET['id'] ?? '');
if(!$id){
    header('Location: error.php?st=400&error=Invalid Link');
    exit();
}

// 2. FETCH SERIAL DATA
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

// 3. FETCH POLICY DETAILS
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

        /* --- STANDARD FORM FIELD STYLING --- */
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: 3.625rem !important;
            padding-top: 1.625rem !important;
            padding-bottom: 0.625rem !important;
            line-height: 1.25;
            color: #212529 !important;
            border: 1px solid #ced4da;
        }

        .form-floating > label {
            padding: 1rem 0.75rem !important;
            color: #6c757d;
        }

        .form-control:read-only {
            background-color: #fff !important;
            opacity: 1;
        }

        /* --- CUSTOM BANK DROPDOWN STYLING --- */
        /* Mimics the floating label look */
        .custom-select-container {
            position: relative;
            cursor: pointer;
        }

        .custom-select-trigger {
            height: 3.625rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background: #fff;
            padding: 1.625rem 0.75rem 0.625rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        /* Floating label simulation */
        .custom-select-label {
            position: absolute;
            top: 0;
            left: 0;
            padding: 1rem 0.75rem;
            pointer-events: none;
            color: #6c757d;
            transform-origin: 0 0;
            transition: opacity .1s ease-in-out,transform .1s ease-in-out;
            font-size: 1rem;
        }

        /* When value is selected or active, mimic Bootstrap floating label transform */
        .custom-select-container.has-value .custom-select-label,
        .custom-select-container.active .custom-select-label {
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
            color: #6c757d; /* Keep gray usually, or blue if active */
        }

        /* Active state (Blue Border) matches Screenshot */
        .custom-select-container.active .custom-select-trigger {
            border-color: #0075C9; /* Sanlam Blue or Tyme Blue */
            box-shadow: 0 0 0 0.25rem rgba(0, 117, 201, 0.25);
        }
        .custom-select-container.active .custom-select-label {
            color: #0075C9;
        }

        /* The Options Dropdown */
        .custom-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ced4da;
            border-top: none;
            border-radius: 0 0 0.25rem 0.25rem;
            z-index: 999;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-height: 300px;
            overflow-y: auto;
            display: none; /* Hidden by default */
        }

        .custom-select-container.active .custom-options {
            display: block;
            border-color: #0075C9;
        }

        .custom-option {
            padding: 10px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
        }

        .custom-option:hover {
            background-color: #f8f9fa;
        }

        .custom-option:last-child {
            border-bottom: none;
        }

        .bank-logo {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 12px;
            object-fit: contain;
            border: 1px solid #eee; /* Subtle border for white logos */
        }

        .arrow-icon {
            transition: transform 0.2s;
        }
        .custom-select-container.active .arrow-icon {
            transform: rotate(180deg);
        }

        /* --- OTHER STYLES --- */
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
        .info-box-blue .bi { font-size: 1.3rem; margin-right: 12px; margin-top: -2px; }

        .section-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: #212529;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
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

                    <form action="_payment.php" method="post" class="needs-validation" id="paymentForm" novalidate>

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

                        <div class="mb-4 position-relative">
                            <select name="bank" id="realBankSelect" class="d-none" required>
                                <option value=""></option>
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

                            <div class="custom-select-container" id="customBankDropdown">
                                <div class="custom-select-label">Bank name</div>
                                <div class="custom-select-trigger">
                                    <div class="d-flex align-items-center" id="selectedBankDisplay">
                                    </div>
                                    <i class="bi bi-chevron-down arrow-icon text-primary"></i>
                                </div>

                                <div class="custom-options">
                                    <div class="custom-option" data-value="ABSA">
                                        <img src="img/banks/absa.png" class="bank-logo" alt="ABSA">
                                        <span>ABSA</span>
                                    </div>
                                    <div class="custom-option" data-value="ACCESSBANK">
                                        <img src="img/banks/access.png" class="bank-logo" alt="Access">
                                        <span>Access Bank</span>
                                    </div>
                                    <div class="custom-option" data-value="AFRICANBANK">
                                        <img src="img/banks/africanbank.png" class="bank-logo" alt="African Bank">
                                        <span>African Bank</span>
                                    </div>
                                    <div class="custom-option" data-value="CAPITEC">
                                        <img src="img/banks/capitec.png" class="bank-logo" alt="Capitec">
                                        <span>Capitec Bank</span>
                                    </div>
                                    <div class="custom-option" data-value="FINBOND">
                                        <img src="img/banks/finbond.png" class="bank-logo" alt="Finbond">
                                        <span>Finbond Mutual Bank</span>
                                    </div>
                                    <div class="custom-option" data-value="FNB">
                                        <img src="img/banks/fnb.png" class="bank-logo" alt="FNB">
                                        <span>FNB</span>
                                    </div>
                                    <div class="custom-option" data-value="NEDBANK">
                                        <img src="img/banks/nedbank.png" class="bank-logo" alt="Nedbank">
                                        <span>Nedbank</span>
                                    </div>
                                    <div class="custom-option" data-value="SBSA">
                                        <img src="img/banks/standardbank.png" class="bank-logo" alt="Standard Bank">
                                        <span>Standard Bank</span>
                                    </div>
                                    <div class="custom-option" data-value="TYMEBANK">
                                        <img src="img/banks/tymebank.png" class="bank-logo" alt="TymeBank">
                                        <span>Tyme Bank</span>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block" id="bankError" style="display:none !important;">Please select a bank.</div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('customBankDropdown');
        const trigger = container.querySelector('.custom-select-trigger');
        const display = document.getElementById('selectedBankDisplay');
        const realSelect = document.getElementById('realBankSelect');
        const options = container.querySelectorAll('.custom-option');
        const errorMsg = document.getElementById('bankError');

        // Toggle dropdown
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            container.classList.toggle('active');
        });

        // Handle selection
        options.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const html = this.innerHTML; // Get logo and text

                // Update Visuals
                display.innerHTML = html;
                container.classList.remove('active');
                container.classList.add('has-value');

                // Update Hidden Input
                realSelect.value = value;

                // Hide error if present
                errorMsg.style.setProperty('display', 'none', 'important');
            });
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!container.contains(e.target)) {
                container.classList.remove('active');
            }
        });

        // Form Validation Hook (Manual check for bank)
        const form = document.getElementById('paymentForm');
        form.addEventListener('submit', function(event) {
            if (!realSelect.value) {
                errorMsg.style.setProperty('display', 'block', 'important');
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
</script>
</body>
</html>