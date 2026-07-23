<?php
session_start();
include("../config/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$userEmail = $_SESSION['user_email'];

/* Total Books */
$bookQuery = mysqli_query($conn,"SELECT COUNT(*) AS total FROM books");
$bookData = mysqli_fetch_assoc($bookQuery);
$totalBooks = $bookData['total'];

/* Available Books */
$availableQuery = mysqli_query($conn,"SELECT COUNT(*) AS total FROM books WHERE status='Available'");
$availableData = mysqli_fetch_assoc($availableQuery);
$availableBooks = $availableData['total'];

/* Issued Books */
$issuedQuery = mysqli_query($conn,"SELECT COUNT(*) AS total FROM books WHERE status='Issued'");
$issuedData = mysqli_fetch_assoc($issuedQuery);
$issuedBooks = $issuedData['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Student Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

body{
    background: url('../assets/images/dashboard-bg.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
.container{
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(3px);
    border-radius: 15px;
    padding: 20px;
    margin-top: 20px;
}

.navbar{
    background:#0d6efd;
}

.navbar-brand{
    color:#fff;
    font-weight:bold;
}

.nav-link{
    color:white !important;
}

.card{
    border:none;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.stat-card{
    color:white;
    text-align:center;
    padding:25px;
}

.bg1{
    background:#0d6efd;
}

.bg2{
    background:#198754;
}

.bg3{
    background:#dc3545;
}

.quick-btn{
    width:100%;
    margin-bottom:15px;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand" href="#">
<i class="fas fa-book"></i>
Digital Library
</a>

<div class="ms-auto">

<span class="text-white me-3">

<i class="fas fa-user"></i>

<?php echo $userName; ?>

</span>

<a href="logout.php" class="btn btn-light btn-sm">

<i class="fas fa-sign-out-alt"></i>

Logout

</a>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="card p-4 mb-4">

<h3>

Welcome,
<?php echo $userName; ?>

</h3>

<p>

Email :
<strong>

<?php echo $userEmail; ?>

</strong>

</p>

</div>

<div class="row">
    <!-- Statistics Cards -->

<div class="col-md-4 mb-4">

    <div class="card stat-card bg1">

        <i class="fas fa-book fa-3x mb-3"></i>

        <h2><?php echo $totalBooks; ?></h2>

        <h5>Total Books</h5>

    </div>

</div>

<div class="col-md-4 mb-4">

    <div class="card stat-card bg2">

        <i class="fas fa-check-circle fa-3x mb-3"></i>

        <h2><?php echo $availableBooks; ?></h2>

        <h5>Available Books</h5>

    </div>

</div>

<div class="col-md-4 mb-4">

    <div class="card stat-card bg3">

        <i class="fas fa-book-reader fa-3x mb-3"></i>

        <h2><?php echo $issuedBooks; ?></h2>

        <h5>Issued Books</h5>

    </div>

</div>

</div>

<!-- Quick Actions -->

<div class="row">

<div class="col-md-4">

<div class="card p-4">

<h4 class="mb-4">

<i class="fas fa-bolt"></i>

Quick Actions

</h4>

<a href="change_password.php" class="btn btn-warning quick-btn">
    <i class="fas fa-key"></i>
    Change Password
</a>
<a href="borrow_history.php" class="btn btn-dark quick-btn">
    <i class="fas fa-history"></i>
    Borrow History
</a>
<a href="search_book.php" class="btn btn-primary">
    <i class="fas fa-search"></i>
    Search Books
</a>

<a href="available_books.php" class="btn btn-success quick-btn">

<i class="fas fa-book-open"></i>

Available Books

</a>

<a href="issued_books.php" class="btn btn-warning quick-btn">

<i class="fas fa-book-reader"></i>

Issued Books

</a>

<a href="profile.php" class="btn btn-info quick-btn">

<i class="fas fa-user-circle"></i>

My Profile

</a>

<a href="logout.php" class="btn btn-danger quick-btn">

<i class="fas fa-sign-out-alt"></i>

Logout

</a>

</div>

</div>

<div class="col-md-8">

<div class="card p-4">

<h4 class="mb-4">

<i class="fas fa-book"></i>

Latest Books

</h4>

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>

<th>ID</th>

<th>Book Name</th>

<th>Author</th>

<th>Category</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$books = mysqli_query($conn,"SELECT * FROM books ORDER BY id DESC LIMIT 8");

while($row = mysqli_fetch_assoc($books))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['title']; ?></td>

<td><?php echo $row['author']; ?></td>

<td><?php echo $row['category']; ?></td>

<td>

<?php

if($row['status']=="Available")
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

?>

</tbody>

</table>

</div>

</div>

</div>

<div class="mt-4">
    <div class="row">

    <!-- Library Information -->

    <div class="col-md-6 mb-4">

        <div class="card p-4">

            <h4 class="mb-3">

                <i class="fas fa-info-circle text-primary"></i>

                Library Information

            </h4>

            <p>
                Welcome to the <strong>Digital Library Management System</strong>.
                Here you can search books, view available books,
                and manage your library activities easily.
            </p>

            <ul class="list-group">

                <li class="list-group-item">
                    📚 Browse Thousands of Books
                </li>

                <li class="list-group-item">
                    🔍 Search Books by Title or Author
                </li>

                <li class="list-group-item">
                    📖 Check Book Availability
                </li>

                <li class="list-group-item">
                    👤 Manage Your Profile
                </li>

                <li class="list-group-item">
                    📅 View Borrow History
                </li>

            </ul>

        </div>

    </div>

    <!-- Student Information -->

    <div class="col-md-6 mb-4">

        <div class="card p-4">

            <h4 class="mb-3">

                <i class="fas fa-user-graduate text-success"></i>

                Student Information

            </h4>

            <table class="table table-bordered">

                <tr>

                    <th width="35%">Student Name</th>

                    <td><?php echo $userName; ?></td>

                </tr>

                <tr>

                    <th>Email</th>

                    <td><?php echo $userEmail; ?></td>

                </tr>

                <tr>

                    <th>Account Status</th>

                    <td>

                        <span class="badge bg-success">

                            Active

                        </span>

                    </td>

                </tr>

                <tr>

                    <th>Login Time</th>

                    <td>

                        <?php echo date("d-m-Y h:i A"); ?>

                    </td>

                </tr>

            </table>

        </div>

    </div>

</div>

<hr>

<div class="text-center mb-4">

    <h5 class="text-secondary">

        <i class="fas fa-book-reader"></i>

        Digital Library Management System

    </h5>

    <p class="text-muted">

        Developed using PHP, MySQL, HTML, CSS & Bootstrap

    </p>

    <small class="text-muted">

        © <?php echo date("Y"); ?> Digital Library. All Rights Reserved.

    </small>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>