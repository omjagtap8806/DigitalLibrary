<?php

// Database Configuration
$host = "sql103.infinityfree.com";
$user = "if0_43040239";
$pass = "Omjagtap8806";
$dbname = "if0_43040239_library";

// Create Connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check Connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Set Character Encoding
mysqli_set_charset($conn, "utf8");

?>