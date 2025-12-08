<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Finalizing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            /* Create the Blue-to-Light-Blue gradient background */
            background: linear-gradient(180deg, #0075c9 0%, #005a9e 30%, #eef6ff 60%, #eef6ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .success-card {
            background: rgba(255, 255, 255, 0.95); /* Slight transparency */
            border: none;
            border-radius: 1.5rem; /* Large rounded corners */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .illustration-circle {
            width: 180px;
            height: 180px;
            background-color: #eef6ff; /* Light blue circle background */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 4px solid #fff; /* White ring around circle */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .illustration-icon {
            font-size: 5rem;
            color: #0075c9;
        }

        /* Custom text colors to match the image */
        .text-primary-dark {
            color: #0d2c44;
        }
        .text-content {
            color: #2c3e50;
        }
        .text-disclaimer {
            color: #6c757d;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">

            <div class="card success-card p-4 text-center">

                <div class="mb-4 mt-2">
                    <div class="illustration-circle">
                        <i class="bi bi-person-check-fill illustration-icon"></i>
                    </div>
                </div>

                <div class="card-body p-0">
                    <h2 class="mb-4 fw-normal text-primary-dark">
                        We’re finalizing your application!
                    </h2>

                    <p class="fs-5 text-content mb-4">
                        We’ll let you know as soon as cover is active. This may take up to 3 days.
                    </p>

                    <p class="text-secondary mb-5">
                        Once your application and payment is successful, we’ll send you an SMS with your policy details.
                    </p>

                    <div class="mb-5 px-3">
                        <p class="text-disclaimer small">
                            <span class="fw-bold">Please note:</span> If we’re not able to successfully verify your information, your policy will be cancelled and we’ll refund any premiums you’ve already paid.
                        </p>
                    </div>

                    <div class="mt-4 text-secondary">
                        <p class="mb-1">Questions about your application?</p>
                        <p class="fw-bold" style="color: #6c757d;">Call 0821122233</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>