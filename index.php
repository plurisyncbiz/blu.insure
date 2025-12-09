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

        /* Fix for consistent height on both Inputs and Selects */
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3.5rem + 2px) !important;
            line-height: 1.25;
        }

        /* Ensure labels align correctly */
        .form-floating > label {
            padding: 1rem 0.75rem;
        }

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
                        <span><?php echo htmlspecialchars($product_description); ?></span>
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

                    <p class="mb-1 fw-bold"><?php echo $product_type; ?></p>
                    <p class="pb-1">
                        You are buying <span>R&nbsp;<?php echo number_format((float)$cover, 0, '.', ','); ?></span>
                        cover for <?php echo htmlspecialchars($term); ?> Months at a once-off payment of R<?php echo htmlspecialchars($product_price); ?>
                    </p>

                    <p class="lead">Complete your personal details.</p>

                    <div class="d-flex align-items-start p-3 rounded text-white fs-6" style="background-color: #0075C9;">
                        <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                        <div>
                            <p class="mb-0 fon">
                                Your prepaid <?php echo htmlspecialchars($product_description); ?> will be active for <?php echo htmlspecialchars($term); ?> months.
                            </p>
                        </div>
                    </div>

                    <form action="_mainlife.php" method="post" class="needs-validation mt-4" novalidate>

                        <div class="form-floating mb-3">
                            <input type="text" name="policy_holder_name" class="form-control form-control-lg" id="name" placeholder="First Name" required>
                            <label for="name">First names</label>
                            <div class="invalid-feedback">Valid first name(s) are required.</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="policy_holder_surname" class="form-control form-control-lg" id="surname" placeholder="Surname" required>
                            <label for="surname">Surname</label>
                            <div class="invalid-feedback">Valid surname is required.</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="policy_holder_idno" class="form-control form-control-lg" id="idno" placeholder="South African Identity Number"
                                   inputmode="numeric" pattern="^(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))" required>
                            <label for="idno">South African Identity Number</label>
                            <div class="invalid-feedback">Please enter a valid South African Identity Number.</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control form-control-lg bg-light" id="cellno" placeholder="Cellphone" value="<?php echo htmlspecialchars($cellno); ?>" readonly>
                            <label for="cellno">Cellphone number</label>
                            <div class="form-text">This number is linked to your policy and cannot be changed here.</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" name="policy_holder_email" class="form-control form-control-lg" id="email" placeholder="name@example.com">
                            <label for="email">Email address <span class="text-muted small">(Optional)</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="employment_status" class="form-select form-select-lg" id="employment_status" required aria-label="Employment Status">
                                <option value="" selected disabled>Select status...</option>
                                <option value="EMPLOYED">Employed</option>
                                <option value="OTHER">Other</option>
                                <option value="RETIRED">Retired</option>
                                <option value="SELF_EMPLOYED">Self Employed</option>
                                <option value="STUDENT">Student</option>
                                <option value="UNEMPLOYED">Unemployed</option>
                            </select>
                            <label for="employment_status">Employment status</label>
                        </div>

                        <div class="form-floating mb-4">
                            <select name="employment_industry" class="form-select form-select-lg" id="employment_industry" required aria-label="Industry">
                                <option value="" selected disabled>Select industry...</option>
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
                            <label for="employment_industry">Industry</label>
                        </div>

                        <hr class="my-4" />

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
    <?php if(isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] !== 'production'): ?>
        <p style="font-size: 0.75em; color: red;">DEBUG: <?php if(isset($serial_data)) print_r($serial_data); ?></p>
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