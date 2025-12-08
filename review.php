<?php
// 1. PHP LOGIC & DATA FETCHING
// Keep existing error reporting settings if needed for dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ip = $_SERVER['REMOTE_ADDR'];
$id = (string)($_GET['id'] ?? ''); // Null coalescing for safety

if(!$id){
    header('Location: error.php?st=400&error=' . urlencode('Policy not activated or invalid link'));
    exit();
}

// Data Fetching Logic
$static = array('10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008','10009');
$json = false;

if(in_array($id, $static)){
    $location = 'data/' . $id . '.json';
    if(file_exists($location)){
        $json = file_get_contents($location);
    }
} else {
    $url = 'https://api.blu.insure/serial/' . $id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Added timeout for safety
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $json = curl_exec($ch);
    if (curl_errno($ch)) {
        // Log error if needed
    }
    curl_close($ch);
}

// Redirect if no JSON data
if(!$json){
    header('Location: error.php?st=500&error=API Error');
    exit;
}

$data = json_decode($json, true);

// Safely access data
$activationid = $data['data'][0]['activationid'] ?? null;

if(!$activationid){
    header('Location: error.php?st=404&error=Policy not activated or invalid link');
    exit;
}

// Helper Function for Policy Details
function getPolicyDetails($activation_id) {
    $url = 'https://api.blu.insure/policy/details/' . $activation_id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $json = curl_exec($ch);
    if (curl_errno($ch)) {
        return null;
    }
    curl_close($ch);
    return json_decode($json, true);
}

// Fetch Deep Policy Details
$policy_details = getPolicyDetails($activationid);

if (!$policy_details || !isset($policy_details['data'])) {
    header('Location: error.php?st=500&error=Could not retrieve policy details');
    exit;
}

$policy_data = $policy_details['data'];

// Extract Variables for Display
$product_code = (string) ($data['data'][0]['product_code'] ?? '');
$cellno       = (string) ($data['data'][0]['cellno'] ?? '');
$product_name = (string) ($data['data'][0]['product_name'] ?? '');
$term         = (string) ($data['data'][0]['product_description'] ?? ''); // Maps to term in your logic
$price        = (string) ($data['data'][0]['product_price'] ?? '');

// Parse Configuration for Cover Amount
$jsonString = $data['data'][0]['product_configuration'] ?? '{}';
$config     = json_decode($jsonString, true);
$cover      = $config['cover'] ?? '0.00';

// Policy Holder Info
$name      = $policy_data['policy_holder']['name'] ?? '';
$surname   = $policy_data['policy_holder']['surname'] ?? '';
$dob       = $policy_data['policy_holder']['date_of_birth'] ?? '';
$id_number = $policy_data['policy_holder']['id_value'] ?? '';
$email     = $policy_data['policy_holder']['email_address'] ?? '';

// Beneficiaries Array
$beneficiaries = $policy_data['beneficiaries'] ?? [];

?>
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Review your policy details">
    <title>Review Application</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        :root {
            --sanlam-blue: #0075C9;
            --sanlam-light-blue: #8faed3;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        /* Review Section Styling */
        .review-label {
            color: #6c757d;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .review-value {
            font-weight: 500;
            font-size: 1.05rem;
            color: #212529;
            margin-bottom: 1rem;
        }

        .beneficiary-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
        }

        /* Progress Bar */
        .progress-container {
            height: 6px;
            background-color: #e9ecef;
            display: flex;
            margin-bottom: 0;
        }
        .progress-segment {
            flex-grow: 1;
            margin-right: 2px;
            background-color: var(--sanlam-light-blue);
        }
        .progress-segment:last-child {
            margin-right: 0;
        }
        /* All segments completed for final review */
        .progress-segment.completed {
            background-color: var(--sanlam-blue);
        }

        /* Header Colors */
        .bg-sanlam-blue {
            background-color: var(--sanlam-blue) !important;
        }
        .btn-sanlam-fill {
            background-color: var(--sanlam-blue);
            border-color: var(--sanlam-blue);
            color: white;
        }
        .btn-sanlam-fill:hover {
            background-color: #0060a3;
            border-color: #0060a3;
        }
        .btn-sanlam-outline {
            color: var(--sanlam-blue);
            border-color: var(--sanlam-blue);
            background-color: #fff;
        }
        .btn-sanlam-outline:hover {
            background-color: #f0f8ff;
            color: var(--sanlam-blue);
            border-color: var(--sanlam-blue);
        }

    </style>
</head>
<body>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="text-center mb-4">
                <img src="https://www.blucreditdeals.co.za/v1/img/bluapproved_logo_landscape.png" alt="Blu Approved" width="150" class="img-fluid">
            </div>

            <div class="card shadow-sm border-0 overflow-hidden">

                <div class="card-header bg-white p-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <img src="img/sanlam_small_logo.png" alt="Sanlam" style="max-width: 30px;" class="me-3" />
                        <span class="fw-medium text-secondary"><?php echo htmlspecialchars($product_name); ?></span>
                    </div>
                </div>

                <div class="progress-container">
                    <div class="progress-segment completed"></div>
                    <div class="progress-segment completed"></div>
                    <div class="progress-segment completed"></div>
                    <div class="progress-segment completed"></div>
                    <div class="progress-segment completed"></div>
                </div>

                <div class="bg-sanlam-blue p-4 text-center">
                    <h4 class="text-white fw-bold mb-0">Review your details</h4>
                </div>

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3 pb-2 border-bottom text-primary">Plan Details</h5>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="review-label">Plan Name</div>
                            <div class="review-value"><?php echo htmlspecialchars($product_name); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">Duration</div>
                            <div class="review-value"><?php echo htmlspecialchars($term); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">Total Premium</div>
                            <div class="review-value text-success">R<?php echo htmlspecialchars($price); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">Cover Amount</div>
                            <div class="review-value">R<?php echo htmlspecialchars($cover); ?></div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 mt-4 pb-2 border-bottom text-primary">Policyholder</h5>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="review-label">Name</div>
                            <div class="review-value"><?php echo htmlspecialchars($name); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">Surname</div>
                            <div class="review-value"><?php echo htmlspecialchars($surname); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">ID Number</div>
                            <div class="review-value"><?php echo htmlspecialchars($id_number); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">Date of Birth</div>
                            <div class="review-value"><?php echo htmlspecialchars($dob); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">Mobile</div>
                            <div class="review-value"><?php echo htmlspecialchars($cellno); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="review-label">Email</div>
                            <div class="review-value text-break"><?php echo htmlspecialchars($email); ?></div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 mt-4 pb-2 border-bottom text-primary">Beneficiaries</h5>
                    <?php if (!empty($beneficiaries)): ?>
                        <?php foreach($beneficiaries as $index => $ben): ?>
                            <div class="beneficiary-card">
                                <div class="row g-2">
                                    <div class="col-12 mb-2">
                                        <span class="badge bg-secondary">Beneficiary <?php echo $index + 1; ?></span>
                                    </div>
                                    <div class="col-6">
                                        <div class="review-label">Name</div>
                                        <div class="review-value mb-0"><?php echo htmlspecialchars($ben['name'] ?? ''); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="review-label">Surname</div>
                                        <div class="review-value mb-0"><?php echo htmlspecialchars($ben['surname'] ?? ''); ?></div>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <div class="review-label">Allocation</div>
                                        <div class="review-value mb-0"><?php echo htmlspecialchars($ben['percentage_allocation'] ?? ''); ?>%</div>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <div class="review-label">DOB</div>
                                        <div class="review-value mb-0"><?php echo htmlspecialchars($ben['date_of_birth'] ?? ''); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted fst-italic">No beneficiaries listed.</p>
                    <?php endif; ?>


                    <form id="reviewForm" class="needs-validation mt-5" action="thankyou.php" method="post" novalidate>

                        <div class="form-check p-3 border rounded bg-light mb-4">
                            <input class="form-check-input mt-1" type="checkbox" value="1" id="confirmCheckbox" required style="transform: scale(1.2);">
                            <label class="form-check-label ms-2" for="confirmCheckbox">
                                I confirm that all the details above are correct and I am ready to submit my application.
                            </label>
                            <div class="invalid-feedback">
                                You must confirm the details are correct.
                            </div>
                        </div>

                        <input type="hidden" name="policy_holder_cellno" value="<?php echo htmlspecialchars($cellno); ?>">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                        <div class="row g-3">
                            <div class="col-6">
                                <button type="button" class="w-100 btn btn-sanlam-outline btn-lg fs-6 fw-bold" onclick="history.back()">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="w-100 btn btn-sanlam-fill btn-lg fs-6 fw-bold">
                                    Submit Application <i class="bi bi-check2-circle ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <p class="text-center small text-muted mt-4 mb-0">Underwritten by Sanlam Developing Markets</p>

                </div>
            </div>
        </div>
    </div>

    <footer class="my-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2025 Blue Label Data Solutions (Pty) Ltd</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy</a></li>
            <li class="list-inline-item"><a href="#">Terms</a></li>
            <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
        <?php if(isset($_GET['debug']) && $_GET['debug'] === 'true'): ?>
            <pre class="text-start bg-dark text-white p-3 mt-3 rounded small"><?php print_r($data); ?></pre>
        <?php endif; ?>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    // Bootstrap Validation
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