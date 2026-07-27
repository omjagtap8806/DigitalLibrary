<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database Connection
include("../includes/config.php");

// Check ID
if (!isset($_GET['id'])) {
    header("Location: view_books.php");
    exit();
}

$id = intval($_GET['id']);

// Get Book Details
$sql = "SELECT book_file, cover_image FROM books WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    header("Location: view_books.php");
    exit();

}

$book = mysqli_fetch_assoc($result);

// File Paths

$pdf = "../uploads/books/" . $book['book_file'];
$image = "../uploads/covers/" . $book['cover_image'];

// Delete Files

if (file_exists($pdf)) {
    unlink($pdf);
}

if (file_exists($image)) {
    unlink($image);
}

// Delete Database Record

$delete = "DELETE FROM books WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>
            alert('Book deleted successfully.');
            window.location='view_books.php';
          </script>";

} else {

    echo "<script>
            alert('Unable to delete book.');
            window.location='view_books.php';
          </script>";

}

?>
