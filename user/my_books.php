<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../config/config.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT
issued_books.*,
books.title,
books.author,
books.category,
books.cover_image
FROM issued_books
INNER JOIN books
ON issued_books.book_id = books.id
WHERE issued_books.user_id = '$user_id'
ORDER BY issued_books.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Issued Books</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#eef2f7;
}

.container-box{
    background:#fff;
    padding:30px;
    margin-top:30px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}

.book-img{
    width:70px;
    height:90px;
    object-fit:cover;
    border-radius:6px;
}

table th{
    background:#0d6efd;
    color:#fff;
    text-align:center;
}

table td{
    text-align:center;
    vertical-align:middle;
}

</style>

</head>

<body>

<div class="container">

<div class="container-box">

<h2 class="mb-4">

<i class="fas fa-book-reader"></i>

My Issued Books

</h2>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>
    <th>ID</th>
    <th>Book Cover</th>
    <th>Book Title</th>
    <th>Author</th>
    <th>Issue Date</th>
    <th>Return Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>

</thead>

<tbody>
    <?php

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>

<?php
if(!empty($row['cover_image']))
{
?>
<img src="../uploads/covers/<?php echo $row['cover_image']; ?>" class="book-img">
<?php
}
else
{
?>
<img src="../assets/images/no-book.png" class="book-img">
<?php
}
?>

</td>

<td>
<strong><?php echo $row['title']; ?></strong>
</td>

<td><?php echo $row['author']; ?></td>

<td><?php echo $row['issue_date']; ?></td>

<td><?php echo $row['return_date']; ?></td>

<td>

<?php
if($row['status']=="Issued")
{
    echo "<span class='badge bg-warning text-dark'>Issued</span>";
}
elseif($row['status']=="Returned")
{
    echo "<span class='badge bg-success'>Returned</span>";
}
else
{
    echo "<span class='badge bg-secondary'>".$row['status']."</span>";
}
?>

</td>
<td>

<?php
if($row['status']=="Issued")
{
?>

<a href="return_book.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure you want to return this book?');">

<i class="fas fa-undo"></i>

Return

</a>

<?php
}
else
{
?>

<button class="btn btn-success btn-sm" disabled>

Returned

</button>

<?php
}
?>

</td>

</tr>

<?php
    }
}
else
{
?>

<tr>

<td colspan="8" class="text-center text-danger">

No books have been issued yet.

</td>

</tr>

<?php
}
?>
</tbody>

</table>

</div>

<hr>

<div class="d-flex justify-content-between align-items-center mt-4">

<h5>

<i class="fas fa-book"></i>

Total Issued Books :

<span class="badge bg-primary">

<?php echo mysqli_num_rows($result); ?>

</span>

</h5>

<a href="dashboard.php" class="btn btn-primary">

<i class="fas fa-arrow-left"></i>

Back to Dashboard

</a>

</div>

</div>

<div class="text-center mt-5 mb-4">

<h5 class="text-primary">

<i class="fas fa-book-reader"></i>

Digital Library Management System

</h5>

<p class="text-muted">

Read • Learn • Grow

</p>

<small class="text-muted">

© <?php echo date("Y"); ?> Digital Library. All Rights Reserved.

</small>

</div>

</div>

</body>

</html>