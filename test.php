<?php

// Database connection parameters
$host = "172.16.85.11"; // Or your database host
$username = "remote10"; // Your database username
$password = "B1d5_11!"; // Your database password
$dbname = "blu_insure"; // Your database name

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4"; // Data Source Name

try {
    $pdo = new PDO($dsn, $username, $password);

    // Set PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connection to database successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    // Optionally, log the error for debugging purposes
    // error_log("PDO Connection Error: " . $e->getMessage());
}

?>