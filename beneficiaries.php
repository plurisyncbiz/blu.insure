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

        /* Consistency for Inputs */
        .form-floating>.form-control,
        .form-floating>.form-select {
            height: calc(3.5rem + 2px) !important;
            line-height: 1.25;
        }
        .form-floating>label {
            padding: 1rem 0.75rem;
        }

        /* Card Styling */
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
        .progress-step-gap { margin-right: 2px; }

        /* Datepicker Tweaks to match Bootstrap 5 */
        .datepicker { font-family: 'Roboto', sans-serif; font-size: 0.95rem; }
        .datepicker table tr td.active { background-color: #0075C9 !important; }
        .input-group-text { background-color: white; border-left: 0; }

        /* Hide the remove button on the first contact card only */
        #contactsContainer .contact-card:first-child .remove-contact {
            display: none !important;
        }
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
                        <img src="img/sanlam_small_logo.png" alt="Sanlam Prepaid Funeral Cover" style="max-width: 30px;" class="me-4" />
                        <span><?php echo $product_description; ?></span>
                    </div>
                </div>
                <div class="col pt-0 pb-0 bg-white">
                    <div class="d-flex" style="height: 5px; background-color: #d9dce1;">
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #0075C9 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-step-gap" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar" role="progressbar" style="width: 20%; background-color: #8faed3 !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="col p-3" style="background-color: #0075C9;">
                    <p class="text-white lead fw-semibold m-0">Beneficiary details</p>
                    <p class="text-white fw-bold m-0">Add up to 2 beneficiaries to your policy</p>
                </div>
            </div>
            <div class="col" id="payments_section">
                <div class="col p-3" style="background-color: #ffffff;">
                    <p class="lead">Enter your beneficiary details below.</p>
                    <div class="d-flex align-items-start p-3 rounded text-white fs-6" style="background-color: #0075C9;">
                        <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                        <div>
                            <p class="mb-0 fon">
                                Beneficiaries must have a South African bank account in their name.
                            </p>
                        </div>
                    </div>

                    <div id="contactTemplate" class="contact-card d-none" data-template="true">
                        <span class="remove-contact mb-2 text-danger fw-bold fs-4 position-absolute top-0 end-0 m-2" title="Remove beneficiary">✖</span>

                        <div class="info-box d-none">
                            <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                            <div>Each beneficiary will receive 50% of your cash benefit.</div>
                        </div>

                        <div class="col mb-3 mt-4">
                            <div class="form-floating">
                                <input name="beneficiary_name[]" type="text" class="form-control form-control-lg" placeholder="First Names" required>
                                <label>First name</label>
                                <div class="invalid-feedback">Valid first name is required.</div>
                            </div>
                        </div>
                        <div class="col mb-3">
                            <div class="form-floating">
                                <input name="beneficiary_surname[]" type="text" class="form-control form-control-lg" placeholder="Surname" required>
                                <label>Surname</label>
                                <div class="invalid-feedback">Valid surname is required.</div>
                            </div>
                        </div>

                        <div class="col mb-3">
                            <div class="form-floating">
                                <input type="text" name="beneficiary_dob[]" class="form-control form-control-lg datepicker-input" placeholder="YYYY-MM-DD" required readonly style="background-color: #fff;">
                                <label>Date of Birth</label>
                                <div class="invalid-feedback">Valid date of birth is required.</div>
                            </div>
                        </div>

                        <div class="col mb-3">
                            <div class="form-floating">
                                <input name="beneficiary_idno[]" type="text" class="form-control form-control-lg" inputmode="numeric" placeholder="South African ID number">
                                <label>South African ID number</label>
                                <small class="text-muted">Optional</small>
                            </div>
                        </div>
                        <div class="col mb-3">
                            <div class="form-floating">
                                <input name="beneficiary_cellno[]" type="tel" class="form-control form-control-lg" placeholder="0831231234" inputmode="numeric" pattern="^0(6[012345678][0-9]{7}|7[1234689][0-9]{7}|8[1234][0-9]{7})$">
                                <label>Cellphone number</label>
                                <small class="text-muted">Optional</small>
                                <div class="invalid-feedback">Must be a valid SA cell number (10 digits).</div>
                            </div>
                        </div>
                        <div class="col mb-3">
                            <div class="form-floating">
                                <input name="beneficiary_email[]" type="email" class="form-control form-control-lg" placeholder="you@you.com">
                                <label>Email address</label>
                                <small class="text-muted">Optional</small>
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                        </div>
                    </div>

                    <form id="beneficiariesForm" class="needs-validation" action="_beneficiaries.php" method="post" novalidate>
                        <div id="contactsContainer"></div>
                        <input type="hidden" name="policy_holder_cellno" value="<?php echo $cellno; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="debug" value="<?php echo $debug; ?>">
                        <div class="row mt-3">
                            <div class="col-6 mb-2">
                                <button type="button" id="addContact" class="w-100 btn btn-outline-primary btn-lg" style="background-color: #ffffff !important; border-color: #0075C9 !important; color: #0075C9;">Add beneficiary</button>
                            </div>
                            <div class="col-6 mb-2">
                                <button type="submit" class="w-100 btn btn-primary btn-lg" style="background-color: #0075C9 !important; border-color: #0075C9 !important; color: white;">Continue</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="form-validation.js"></script>

<script>
    $(function(){
        var $container = $('#contactsContainer');
        var $template = $('#contactTemplate');
        var maxContacts = 2;

        // Initialize first contact
        addContact();

        $('#addContact').on('click', function(){
            if ($container.find('.contact-card').length >= maxContacts) {
                $(this).prop('disabled', true);
                return;
            }
            addContact();
        });

        $container.on('click', '.remove-contact', function(){
            $(this).closest('.contact-card').remove();
            $('#addContact').prop('disabled', false);
            updateInfoBox();
        });

        function addContact() {
            var $clone = $template.clone().removeClass('d-none');
            // Remove IDs to avoid duplicates
            $clone.find('input, select, textarea').removeAttr('id');
            $clone.find('label').removeAttr('for');

            $container.append($clone);

            // Initialize Datepicker on the new input
            // We use 'autoclose' so it feels like a native selector
            $clone.find('.datepicker-input').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                startView: 2, // Start at 'Year' view for faster selection of birth years
                maxViewMode: 2,
                endDate: '0d', // Cannot select future dates
                container: 'body' // Helps with scrolling/positioning issues on mobile
            });

            .on('changeDate', function() {
                    // When a date is picked, manually hide the error if it's valid
                    if(this.value) {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    }
                });
            updateInfoBox();
        }

        function updateInfoBox() {
            $container.find('.contact-card').each(function(idx){
                if(idx === 1){
                    $(this).find('.info-box').removeClass('d-none');
                } else {
                    $(this).find('.info-box').addClass('d-none');
                }
            });
        }

        // Bootstrap validation
        var form = document.getElementById('beneficiariesForm');
        form.addEventListener('submit', function(event){
            if(!form.checkValidity()){
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
</script>
</body>
</html>