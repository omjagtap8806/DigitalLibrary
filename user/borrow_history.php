<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/config.php");

$user_id = $_SESSION['user_id'];

$sql = "
SELECT
issued_books.id,
books.title,
books.author,
books.category,
issued_books.issue_date,
issued_books.return_date,
issued_books.status
FROM issued_books
INNER JOIN books
ON issued_books.book_id = books.id
WHERE issued_books.user_id='$user_id'
ORDER BY issued_books.id DESC
";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Borrow History</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

body{
background:#eef2f7;
}

.box{
background:#fff;
padding:30px;
margin-top:40px;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,.15);
}

table th{
background:#0d6efd;
color:white;
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

<div class="box">

<h2 class="mb-4">

<i class="fas fa-history"></i>

Borrow History

</h2>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>ID</th>

<th>Book</th>

<th>Author</th>

<th>Category</th>

<th>Issue Date</th>

<th>Return Date</th>

<th>Status</th>

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
<strong><?php echo $row['title']; ?></strong>
</td>

<td><?php echo $row['author']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo $row['issue_date']; ?></td>

<td>

<?php

if(!empty($row['return_date']))
{
    echo $row['return_date'];
}
else
{
    echo "<span class='text-danger'>Not Returned</span>";
}

?>

</td>

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

</tr>

<?php

    }
}
else
{

?>

<tr>

<td colspan="7" class="text-center text-danger">

No Borrow History Found.

</td>

</tr>

<?php

}

?>
</tbody>

</table>

</div>

<hr>

<div class="d-flex justify-content-between align-items-center">

<h5>

<i class="fas fa-book"></i>

Total Borrowed Books :

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>