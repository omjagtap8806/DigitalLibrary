<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Digital Library Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
}

.hero{

    height:100vh;

    background:linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
url('assets/images/dashboard-bg.png');
    background-size:cover;

    background-position:center;

    display:flex;

    justify-content:center;

    align-items:center;

    text-align:center;

    color:white;

}

.btn-custom{

    width:220px;

    margin:10px;

    padding:12px;

    font-size:18px;

}

/* Back To Top Button */

#topBtn{
    display:none;
    position:fixed;
    bottom:30px;
    right:30px;
    z-index:999;
    border:none;
    outline:none;
    background:#0d6efd;
    color:#fff;
    cursor:pointer;
    padding:15px;
    border-radius:50%;
    font-size:20px;
    width:55px;
    height:55px;
    box-shadow:0 5px 15px rgba(0,0,0,.3);
    transition:.3s;
}

#topBtn:hover{
    background:#084298;
    transform:scale(1.1);
}
/* Feature Card Animation */

.card{
    transition:all .3s ease;
}

.card:hover{
    transform:translateY(-10px);
    box-shadow:0 15px 35px rgba(0,0,0,.2);
}</style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">

    <div class="container">

        <a class="navbar-brand fw-bold" href="#">

            📚 Digital Library

        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="#">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="user/login.php">User Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="user/register.php">Register</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="admin/login.php">Admin</a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<div class="hero">

<div>

<h1 class="display-3 fw-bold">

<i class="fas fa-book"></i>

Digital Library

</h1>

<h3 class="mb-4">

Management System

</h3>

<p class="lead">

Read • Learn • Grow

</p>

<a href="user/login.php" class="btn btn-primary btn-custom">

<i class="fas fa-user"></i>

User Login

</a>

<br>

<a href="user/register.php" class="btn btn-success btn-custom">

<i class="fas fa-user-plus"></i>

Register

</a>

<br>

<a href="admin/login.php" class="btn btn-danger btn-custom">

<i class="fas fa-user-shield"></i>

Admin Login

</a>

</div>

</div>
<!-- Features Section -->

<section class="container py-5">

    <div class="text-center mb-5">

        <h2 class="fw-bold text-primary">
            Why Choose Our Digital Library?
        </h2>

        <p class="text-muted">
            A modern library management solution for students and administrators.
        </p>

    </div>

    <div class="row">

        <div class="col-md-3 mb-4">

            <div class="card shadow text-center p-4">

                <i class="fas fa-book fa-3x text-primary mb-3"></i>

                <h5>1000+ Books</h5>

                <p>Large collection of books.</p>

            </div>

        </div>

        <div class="col-md-3 mb-4">

            <div class="card shadow text-center p-4">

                <i class="fas fa-search fa-3x text-success mb-3"></i>

                <h5>Smart Search</h5>

                <p>Search by title or author.</p>

            </div>

        </div>

        <div class="col-md-3 mb-4">

            <div class="card shadow text-center p-4">

                <i class="fas fa-user-graduate fa-3x text-warning mb-3"></i>

                <h5>Student Friendly</h5>

                <p>Simple and easy interface.</p>

            </div>

        </div>

        <div class="col-md-3 mb-4">

            <div class="card shadow text-center p-4">

                <i class="fas fa-lock fa-3x text-danger mb-3"></i>

                <h5>Secure</h5>

                <p>Safe login for all users.</p>

            </div>

        </div>

    </div>

</section>
<!-- ================= About Section ================= -->

<section class="bg-light py-5">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <img src="https://images.unsplash.com/photo-1526243741027-444d633d7365?auto=format&fit=crop&w=900&q=80"
                     class="img-fluid rounded shadow"
                     alt="Library">

            </div>

            <div class="col-lg-6">

                <h2 class="fw-bold text-primary mb-4">

                    About Our Digital Library

                </h2>

                <p class="lead">

                    The Digital Library Management System is a web-based application
                    developed using PHP and MySQL.

                </p>

                <p>

                    It helps students and administrators manage books, issue and return
                    books, search books, and maintain borrowing records through a
                    simple and user-friendly interface.

                </p>

                <div class="row mt-4">

                    <div class="col-6">

                        <h3 class="text-primary">1000+</h3>

                        <p>Books</p>

                    </div>

                    <div class="col-6">

                        <h3 class="text-success">500+</h3>

                        <p>Students</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= Footer ================= -->

<footer class="bg-dark text-white pt-5 pb-3">

    <div class="container">

        <div class="row">

            <!-- Library -->

            <div class="col-md-4">

                <h4>

                    <i class="fas fa-book-reader"></i>

                    Digital Library

                </h4>

                <p>

                    A modern Digital Library Management System developed
                    using PHP, MySQL, HTML, CSS and Bootstrap.

                </p>

            </div>

            <!-- Quick Links -->

            <div class="col-md-4">

                <h4>Quick Links</h4>

                <ul class="list-unstyled">

                    <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>

                    <li><a href="user/login.php" class="text-white text-decoration-none">User Login</a></li>

                    <li><a href="user/register.php" class="text-white text-decoration-none">Register</a></li>

                    <li><a href="admin/login.php" class="text-white text-decoration-none">Admin Login</a></li>

                </ul>

            </div>

            <!-- Contact -->

            <div class="col-md-4">

                <h4>Contact</h4>

                <p>

                    <i class="fas fa-map-marker-alt"></i>

                    Pune, Maharashtra

                </p>

                <p>

                    <i class="fas fa-envelope"></i>

                    library@gmail.com

                </p>

                <p>

                    <i class="fas fa-phone"></i>

                    +91 9876543210

                </p>

            </div>

        </div>

        <hr class="bg-light">

        <div class="text-center">

            <p class="mb-0">

                © <?php echo date("Y"); ?>

                Digital Library Management System

                | Developed by <strong>Om Jagtap</strong>

            </p>

        </div>

    </div>

</footer>
<!-- Back To Top Button -->

<button onclick="topFunction()" id="topBtn" title="Go to top">
    <i class="fas fa-arrow-up"></i>
</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

let mybutton=document.getElementById("topBtn");

window.onscroll=function(){
    scrollFunction();
};

function scrollFunction(){

    if(document.body.scrollTop>200 || document.documentElement.scrollTop>200){

        mybutton.style.display="block";

    }else{

        mybutton.style.display="none";

    }

}

function topFunction(){

    document.body.scrollTop=0;
    document.documentElement.scrollTop=0;

}

</script>
</body>

</html>