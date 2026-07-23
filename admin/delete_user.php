<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/config.php");

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = (int)$_GET['id'];

// Check if user exists
$check = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");

if (mysqli_num_rows($check) == 0) {
    die("User not found.");
}

// Delete user
if (mysqli_query($conn, "DELETE FROM users WHERE id = $id")) {

    header("Location: manage_users.php?msg=deleted");
    exit();

} else {

    die("Error: " . mysqli_error($conn));

}
?>