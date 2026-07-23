<?php
session_start();
include("../config/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $sql = "SELECT * FROM books
            WHERE status='Available'
            AND (
                title LIKE '%$search%'
                OR author LIKE '%$search%'
                OR category LIKE '%$search%'
            )
            ORDER BY id DESC";
}
else
{
    $sql = "SELECT * FROM books
            WHERE status='Available'
            ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Available Books</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

body{
    background:#f4f7fc;
}

.navbar{
    background:#0d6efd;
}

.navbar-brand{
    color:#fff;
    font-weight:bold;
}

.nav-link{
    color:#fff !important;
}

.card{
    border:none;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.15);
}

.table th{
    background:#0d6efd;
    color:white;
    text-align:center;
}

.table td{
    vertical-align:middle;
    text-align:center;
}

.book-img{
    width:70px;
    height:90px;
    object-fit:cover;
    border-radius:8px;
}

.search-box{
    max-width:500px;
}

.badge{
    font-size:14px;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand">

<i class="fas fa-book-open"></i>

Digital Library

</a>

<div class="ms-auto">

<span class="text-white me-3">

<i class="fas fa-user"></i>

<?php echo $userName; ?>

</span>

<a href="dashboard.php"
class="btn btn-light btn-sm">

<i class="fas fa-arrow-left"></i>

Dashboard

</a>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="card p-4">

<h3 class="mb-4">

<i class="fas fa-book"></i>

Available Books

</h3>

<form method="GET">

<div class="input-group search-box mb-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search by Title, Author or Category"
value="<?php echo $search; ?>">

<button
class="btn btn-primary">

<i class="fas fa-search"></i>

Search

</button>

</div>

</form>

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>ID</th>

<th>Book Cover</th>

<th>Book Title</th>

<th>Author</th>

<th>Category</th>

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

<td>

<?php echo $row['id']; ?>

</td>

<td>



<?php
if (!empty($row['cover_image']))
{
?>
<img
src="../uploads/covers/<?php echo $row['cover_image']; ?>"
class="book-img">
<?php
}
else
{
?>
<img
src="../assets/images/no-book.png"
class="book-img">
<?php
}
?>



</td>

<td>

<strong>

<?php echo $row['title']; ?>

</strong>

</td>

<td>

<?php echo $row['author']; ?>

</td>

<td>

<?php echo $row['category']; ?>

</td>

<td>
    <span class="badge bg-success">
        <?php echo $row['status']; ?>
    </span>
</td>

<td>
    <a href="book_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
        View
    </a>
</td>

</tr>

<?php
    }

    
}
else
{

?>

<tr>

<td colspan="6">

<div class="alert alert-warning mb-0">

<i class="fas fa-exclamation-circle"></i>

No Available Books Found.

</div>

</td>

</tr>

<?php

}

?>
</tbody>

</table>

<hr>

<div class="row mt-4">

    <div class="col-md-6">

        <?php

        $countQuery = mysqli_query($conn,"SELECT COUNT(*) AS total FROM books WHERE status='Available'");
        $countData = mysqli_fetch_assoc($countQuery);

        ?>

        <h5>

            <i class="fas fa-book text-primary"></i>

            Total Available Books :

            <span class="badge bg-primary">

                <?php echo $countData['total']; ?>

            </span>

        </h5>

    </div>

    <div class="col-md-6 text-end">

        <a href="dashboard.php" class="btn btn-primary">

            <i class="fas fa-arrow-left"></i>

            Back to Dashboard

        </a>

    </div>

</div>

</div>

<div class="text-center mt-5 mb-4">

    <hr>

    <h5 class="text-primary">

        <i class="fas fa-book-reader"></i>

        Digital Library Management System

    </h5>

    <p class="text-muted">

        Browse thousands of books anytime, anywhere.

    </p>

    <small class="text-muted">

        © <?php echo date("Y"); ?> Digital Library. All Rights Reserved.

    </small>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>