<?php

// Database Configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "library";

// Create Connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check Connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Set Character Encoding
mysqli_set_charset($conn, "utf8");

?>