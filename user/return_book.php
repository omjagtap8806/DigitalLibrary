<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../config/config.php");

if (!isset($_GET['id']))
{
    die("Invalid Request.");
}

$issue_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
// Check if the issued book record exists
$sql = "SELECT * FROM issued_books
        WHERE id='$issue_id'
        AND user_id='$user_id'
        AND status='Issued'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0)
{
    echo "<script>
    alert('Invalid request or book already returned.');
    window.location='my_books.php';
    </script>";
    exit();
}

$issue = mysqli_fetch_assoc($result);

$book_id = $issue['book_id'];
// Update issued_books table
$update_issue = mysqli_query($conn,
"UPDATE issued_books
SET
    status='Returned',
    return_date=CURDATE()
WHERE id='$issue_id'");

// Update books table
$update_book = mysqli_query($conn,
"UPDATE books
SET status='Available'
WHERE id='$book_id'");

// Check if both updates were successful
if($update_issue && $update_book)
{
    echo "<script>
    alert('Book returned successfully!');
    window.location='my_books.php';
    </script>";
}
else
{
    echo "<script>
    alert('Error while returning the book.');
    window.location='my_books.php';
    </script>";
}
?>