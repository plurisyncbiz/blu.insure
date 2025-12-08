<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $names = $_POST['beneficiary_name'];
    $surnames = $_POST['beneficiary_surname'];
    $dob_days = $_POST['beneficiary_dob_day'];
    $dob_months = $_POST['beneficiary_dob_month'];
    $dob_years = $_POST['beneficiary_dob_year'];

    // Ensure arrays have the same number of elements to avoid errors
    if (count($names) === count($surnames)) {
        for ($i = 0; $i < count($names); $i++) {
            $currentName = htmlspecialchars($names[$i]);
            $currentSurname = htmlspecialchars($surnames[$i]);
            $currentDob = htmlspecialchars($dob_days[$i] . '-' . $dob_months[$i] . '-' . $dob_years[$i]);

            // Process each entry (e.g., save to database, send email)
            echo "Entry " . ($i + 1) . ": Name = " . $currentName . ", Email = " . $currentSurname .", DOB = " . $currentDob . "<br>";
            // Example: Insert into a database
            // $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            // $stmt->execute([$currentName, $currentEmail]);
        }

        print_r($_POST);

    } else {
        echo "Error: Mismatched number of names and emails.";
    }
}
?>