<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

include("../config/config.php");

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $sql = "SELECT * FROM books
            WHERE title LIKE '%$search%'
            OR author LIKE '%$search%'
            OR category LIKE '%$search%'
            ORDER BY id DESC";
}
else
{
    $sql = "SELECT * FROM books ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Search Books</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

body{
    background:#eef2f7;
}

.container-box{
    background:#fff;
    margin-top:30px;
    padding:30px;
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
<i class="fas fa-search"></i>
Search Books
</h2>

<form method="GET" class="row mb-4">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search by Title, Author or Category"
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-2 d-grid">

<button class="btn btn-primary">

<i class="fas fa-search"></i>

Search

</button>

</div>

</form>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>ID</th>

<th>Cover</th>

<th>Title</th>

<th>Author</th>

<th>Category</th>

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

<td><?php echo $row['category']; ?></td>

<td>

<?php
if($row['status'] == "Available")
{
    echo "<span class='badge bg-success'>Available</span>";
}
else
{
    echo "<span class='badge bg-danger'>Issued</span>";
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

<td colspan="6" class="text-center text-danger">

No books found.

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

Total Books Found :

<span class="badge bg-primary">

<?php echo mysqli_num_rows($result); ?>

</span>

</h5>

<a href="dashboard.php" class="btn btn-secondary">

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