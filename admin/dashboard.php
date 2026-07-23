<?php
session_start();

include("../includes/config.php");

// Check Admin Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

/* -----------------------------
   Dashboard Statistics
------------------------------*/

// Total Books
$bookQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM books");
$bookData = mysqli_fetch_assoc($bookQuery);
$totalBooks = $bookData['total'];

// Total Users
$userQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$userData = mysqli_fetch_assoc($userQuery);
$totalUsers = $userData['total'];

// Total Categories
$categoryQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories");
$categoryData = mysqli_fetch_assoc($categoryQuery);
$totalCategories = $categoryData['total'];

// Total Issued Books
$issueQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM issued_books");
$issueData = mysqli_fetch_assoc($issueQuery);
$totalIssued = $issueData['total'];

// Recent Books
$recentBooks = mysqli_query($conn,"
SELECT *
FROM books
ORDER BY id DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{

background:#f4f7fb;

font-family:'Segoe UI',sans-serif;

}

.sidebar{

position:fixed;

left:0;

top:0;

width:260px;

height:100vh;

background:linear-gradient(180deg,#1e3a8a,#2563eb);

color:white;

overflow:auto;

}

.logo{

padding:25px;

text-align:center;

border-bottom:1px solid rgba(255,255,255,.2);

}

.logo h3{

font-weight:bold;

}

.sidebar ul{

list-style:none;

padding:20px;

}

.sidebar ul li{

margin-bottom:10px;

}

.sidebar ul li a{

display:block;

padding:14px;

color:white;

text-decoration:none;

border-radius:10px;

transition:.3s;

}

.sidebar ul li a:hover{

background:rgba(255,255,255,.15);

padding-left:22px;

}

.sidebar i{

width:25px;

}

.main{
    margin-left:260px;
    min-height:100vh;
    background:url('../assets/images/dashboard-bg.png') center/cover no-repeat fixed;
}

.topbar{

background:white;

padding:20px 30px;

display:flex;

justify-content:space-between;

align-items:center;

box-shadow:0 2px 15px rgba(0,0,0,.08);

}

.content{
    padding:30px;
    min-height:100vh;
    background:url('../assets/images/dashboard-bg.png') center center/cover no-repeat fixed;
}
.content{
    position:relative;
}

.content::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,.82);
    backdrop-filter:blur(2px);
}

.content>*{
    position:relative;
    z-index:1;
}

.card-box{

border:none;

border-radius:20px;

color:white;

transition:.3s;

box-shadow:0 10px 25px rgba(0,0,0,.15);

}

.card-box:hover{

transform:translateY(-6px);

}

</style>

</head>

<body>
    <!-- ================= Sidebar ================= -->

<div class="sidebar">

    <div class="logo">

        <h3>📚 Digital Library</h3>

        <small>Admin Panel</small>

    </div>

    <ul>

        <li>
            <a href="dashboard.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="add_book.php">
                <i class="fas fa-plus-circle"></i> Add Book
            </a>
        </li>

        <li>
            <a href="view_books.php">
                <i class="fas fa-book"></i> View Books
            </a>
        </li>

        <li>
            <a href="categories.php">
                <i class="fas fa-layer-group"></i> Categories
            </a>
        </li>

        <li>
            <a href="manage_users.php">
                <i class="fas fa-users"></i> Manage Users
            </a>
        </li>

        <li>
            <a href="issue_book.php">
                <i class="fas fa-book-reader"></i> Issue Book
            </a>
        </li>

        <li>
            <a href="return_book.php">
                <i class="fas fa-undo"></i> Return Book
            </a>
        </li>

        <li>
            <a href="reports.php">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>

        <li>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>

    </ul>

</div>

<!-- ================= Main ================= -->

<div class="main">

    <!-- Top Navbar -->

    <div class="topbar">

        <h3>

            <i class="fas fa-chart-line text-primary"></i>

            Admin Dashboard

        </h3>

        <div class="d-flex align-items-center">

            <span class="me-3 text-secondary">

                <?php echo date("d F Y"); ?>

            </span>

            <i class="fas fa-user-circle fa-2x text-primary"></i>

        </div>

    </div>

    <div class="content">

        <!-- Welcome Banner -->

        <div class="card border-0 shadow-lg mb-4"
             style="background:linear-gradient(135deg,#2563eb,#1e40af);border-radius:20px;">

            <div class="card-body text-white p-4">

                <div class="row align-items-center">

                    <div class="col-lg-8">

                        <h2 class="fw-bold">

                            👋 Welcome Back, Administrator

                        </h2>

                        <p class="mb-2">

                            Manage your Digital Library efficiently from one dashboard.

                        </p>

                        <p class="mb-0">

                            <i class="fas fa-calendar-alt"></i>

                            <?php echo date("l, d F Y"); ?>

                        </p>

                        <p>

                            <i class="fas fa-clock"></i>

                            <span id="clock"></span>

                        </p>

                    </div>

                    <div class="col-lg-4 text-end">

                        <i class="fas fa-book-reader"
                           style="font-size:110px;opacity:.15;"></i>

                    </div>

                </div>

            </div>

        </div>
        <!-- ================= Dashboard Statistics ================= -->

<div class="row g-4">

    <!-- Total Books -->

    <div class="col-lg-3 col-md-6">

        <div class="card card-box" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-uppercase">Total Books</small>

                        <h2 class="mt-2 fw-bold">

                            <?php echo $totalBooks; ?>

                        </h2>

                    </div>

                    <div class="bg-white rounded-circle p-3">

                        <i class="fas fa-book fa-2x text-primary"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Total Users -->

    <div class="col-lg-3 col-md-6">

        <div class="card card-box" style="background:linear-gradient(135deg,#10b981,#059669);">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-uppercase">Total Users</small>

                        <h2 class="mt-2 fw-bold">

                            <?php echo $totalUsers; ?>

                        </h2>

                    </div>

                    <div class="bg-white rounded-circle p-3">

                        <i class="fas fa-users fa-2x text-success"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Categories -->

    <div class="col-lg-3 col-md-6">

        <div class="card card-box" style="background:linear-gradient(135deg,#f59e0b,#d97706);">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-uppercase">Categories</small>

                        <h2 class="mt-2 fw-bold">

                            <?php echo $totalCategories; ?>

                        </h2>

                    </div>

                    <div class="bg-white rounded-circle p-3">

                        <i class="fas fa-layer-group fa-2x text-warning"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Issued Books -->

    <div class="col-lg-3 col-md-6">

        <div class="card card-box" style="background:linear-gradient(135deg,#ef4444,#dc2626);">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-uppercase">Issued Books</small>

                        <h2 class="mt-2 fw-bold">

                            <?php echo $totalIssued; ?>

                        </h2>

                    </div>

                    <div class="bg-white rounded-circle p-3">

                        <i class="fas fa-book-reader fa-2x text-danger"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- End Statistics -->
 <!-- =========================== -->
<!-- Row 1 -->
<!-- =========================== -->

<div class="row mt-4">

    <!-- Recent Books -->

    <div class="col-lg-8">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="fas fa-book"></i>

                    Recently Added Books

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>ID</th>

                                <th>Book</th>

                                <th>Author</th>

                                <th>Category</th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php

                        while($book=mysqli_fetch_assoc($recentBooks)){

                        ?>

                        <tr>

                            <td><?php echo $book['id']; ?></td>

                            <td><?php echo $book['title']; ?></td>

                            <td><?php echo $book['author']; ?></td>

                            <td><?php echo $book['category']; ?></td>

                            <td>

                                <?php

                                if($book['status']=="Available"){

                                    echo '<span class="badge bg-success">Available</span>';

                                }else{

                                    echo '<span class="badge bg-danger">Issued</span>';

                                }

                                ?>

                            </td>

                        </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
    <!-- Quick Actions -->

    <div class="col-lg-4">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-success text-white">

                <h5>

                    <i class="fas fa-bolt"></i>

                    Quick Actions

                </h5>

            </div>

            <div class="card-body">

                <div class="d-grid gap-3">

                    <a href="add_book.php" class="btn btn-primary">

                        <i class="fas fa-plus-circle"></i>

                        Add Book

                    </a>

                    <a href="view_books.php" class="btn btn-info text-white">

                        <i class="fas fa-book"></i>

                        View Books

                    </a>

                    <a href="manage_users.php" class="btn btn-warning">

                        <i class="fas fa-users"></i>

                        Manage Users

                    </a>

                    <a href="issue_book.php" class="btn btn-danger">

                        <i class="fas fa-book-reader"></i>

                        Issue Book

                    </a>

                    <a href="return_book.php" class="btn btn-secondary">

                        <i class="fas fa-undo"></i>

                        Return Book

                    </a>

                    <a href="reports.php" class="btn btn-dark">

                        <i class="fas fa-chart-line"></i>

                        Reports

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- =========================== -->
<!-- Library Statistics -->
<!-- =========================== -->

<div class="row mt-4">

<div class="col-lg-12">

<div class="card border-0 shadow-lg">

<div class="card-header bg-dark text-white">

<h5>

<i class="fas fa-chart-pie"></i>

Library Overview

</h5>

</div>

<div class="card-body">

<div class="row text-center">

<div class="col-md-3">

<h1 class="text-primary">

<?php echo $totalBooks; ?>

</h1>

<p>Total Books</p>

</div>

<div class="col-md-3">

<h1 class="text-success">

<?php echo $totalUsers; ?>

</h1>

<p>Total Users</p>

</div>

<div class="col-md-3">

<h1 class="text-warning">

<?php echo $totalCategories; ?>

</h1>

<p>Categories</p>

</div>

<div class="col-md-3">

<h1 class="text-danger">

<?php echo $totalIssued; ?>

</h1>

<p>Issued Books</p>

</div>

</div>

</div>

</div>

</div>

</div>
<!-- ===================================================== -->
<!-- Chart Section -->
<!-- ===================================================== -->

<div class="row mt-4">

    <div class="col-lg-8">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="fas fa-chart-pie"></i>

                    Library Summary

                </h5>

            </div>

            <div class="card-body">

                <canvas id="libraryChart" height="120"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-success text-white">

                <h5>

                    <i class="fas fa-info-circle"></i>

                    Dashboard Info

                </h5>

            </div>

            <div class="card-body">

                <p>

                    <strong>Total Books :</strong>

                    <?php echo $totalBooks; ?>

                </p>

                <p>

                    <strong>Total Users :</strong>

                    <?php echo $totalUsers; ?>

                </p>

                <p>

                    <strong>Categories :</strong>

                    <?php echo $totalCategories; ?>

                </p>

                <p>

                    <strong>Issued Books :</strong>

                    <?php echo $totalIssued; ?>

                </p>

                <hr>

                <h6>

                    Current Time

                </h6>

                <h4 id="clock" class="text-primary"></h4>

            </div>

        </div>

    </div>

</div>

<!-- ===================================================== -->
<!-- Footer -->
<!-- ===================================================== -->

<footer class="mt-5">

    <div class="card border-0 shadow">

        <div class="card-body text-center">

            <h5>

                📚 Digital Library Management System

            </h5>

            <p class="text-muted">

                Internship Project using PHP, MySQL & Bootstrap 5

            </p>

            <small>

                © <?php echo date("Y"); ?>

                All Rights Reserved.

            </small>

        </div>

    </div>

</footer>

</div>

<!-- End Content -->

</div>

<!-- End Main -->

<!-- ===================================================== -->
<!-- Bootstrap -->
<!-- ===================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ===================================================== -->
<!-- Live Clock -->
<!-- ===================================================== -->

<script>

function updateClock(){

const now=new Date();

const options={

hour:'2-digit',

minute:'2-digit',

second:'2-digit'

};

document.getElementById("clock").innerHTML=

now.toLocaleTimeString('en-US',options);

}

setInterval(updateClock,1000);

updateClock();

</script>

<!-- ===================================================== -->
<!-- Chart -->
<!-- ===================================================== -->

<script>

const ctx=document.getElementById('libraryChart');

new Chart(ctx,{

type:'doughnut',

data:{

labels:[

'Books',

'Users',

'Categories',

'Issued'

],

datasets:[{

data:[

<?php echo $totalBooks;?>,

<?php echo $totalUsers;?>,

<?php echo $totalCategories;?>,

<?php echo $totalIssued;?>

],

backgroundColor:[

'#2563eb',

'#10b981',

'#f59e0b',

'#ef4444'

],

borderWidth:2

}]

},

options:{

responsive:true,

plugins:{

legend:{

position:'bottom'

}

}

}

});

</script>

</body>

</html>