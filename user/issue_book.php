<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../config/config.php");

$user_id = $_SESSION['user_id'];

if (!isset($_GET['book_id']))
{
    die("Invalid Book ID.");
}

$book_id = intval($_GET['book_id']);

$check = mysqli_query($conn,
"SELECT * FROM books
WHERE id='$book_id'
AND status='Available'");

if(mysqli_num_rows($check)==0)
{
    echo "<script>
    alert('This book is not available.');
    window.location='available_books.php';
    </script>";
    exit();
}
?>
<?php

$issue_date = date("Y-m-d");
$return_date = date("Y-m-d", strtotime("+7 days"));

$insert = mysqli_query($conn, "
INSERT INTO issued_books
(user_id, book_id, issue_date, return_date, status)
VALUES
('$user_id', '$book_id', '$issue_date', '$return_date', 'Issued')
");

if($insert)
{
    mysqli_query($conn, "
    UPDATE books
    SET status='Unavailable'
    WHERE id='$book_id'
    ");

    echo "<script>
    alert('Book Issued Successfully!');
    window.location='my_books.php';
    </script>";
}
else
{
    echo "<script>
    alert('Error while issuing book.');
    window.location='available_books.php';
    </script>";
}

?>