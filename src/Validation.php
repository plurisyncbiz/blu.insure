<?php
namespace App\Validation;
class Validation {

    public static function parseSAID($idNumber) {
        // 1. Basic Format
        if (strlen($idNumber) != 13 || !ctype_digit($idNumber)) {
            return ["valid" => false, "error" => "Invalid length or characters"];
        }

        // 2. Date of Birth logic
        $yearPart  = substr($idNumber, 0, 2);
        $month     = substr($idNumber, 2, 2);
        $day       = substr($idNumber, 4, 2);

        $currentYear = (int)date("Y");
        $fullYear    = (int)("20" . $yearPart);
        if ($fullYear > $currentYear) { $fullYear -= 100; } // Handle 1900s vs 2000s

        if (!checkdate((int)$month, (int)$day, $fullYear)) {
            return ["valid" => false, "error" => "Invalid Date of Birth"];
        }

        $dob = sprintf("%04d-%02d-%02d", $fullYear, $month, $day);

        // 3. Gender & Citizenship
        $gender = ((int)substr($idNumber, 6, 4) >= 5000) ? "Male" : "Female";
        $citizen = ((int)substr($idNumber, 10, 1) === 0) ? "SA Citizen" : "Permanent Resident";

        // 4. Luhn Checksum
        $sum = 0;
        $idArray = str_split($idNumber);
        for ($i = 0; $i < 12; $i++) {
            $num = (int)$idArray[$i];
            if ($i % 2 === 0) { $sum += $num; }
            else { $sum += array_sum(str_split($num * 2)); }
        }
        $checkDigit = (10 - ($sum % 10)) % 10;

        if ($checkDigit !== (int)$idArray[12]) {
            return ["valid" => false, "error" => "Invalid Checksum"];
        }

        return [
            "valid"         => true,
            "date_of_birth" => $dob,
            "gender"        => $gender,
            "citizenship"   => $citizen
        ];
    }
}