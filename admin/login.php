<?php
session_start();

// If already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Database Connection
include("../includes/config.php");

$error = "";

// Login Form Submitted
if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($username) || empty($password)) {

        $error = "Please enter username/email and password.";

    } else {

        // Find Admin
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, username, email, password
             FROM admins
             WHERE username=? OR email=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $username,
            $username
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {

            $admin = mysqli_fetch_assoc($result);

            // Verify Password
            if (password_verify($password, $admin['password'])) {

                // Create Session

                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_username'] = $admin['username'];

                header("Location: dashboard.php");
                exit();

            } else {

                $error = "Invalid password.";

            }

        } else {

            $error = "Username or Email not found.";

        }

    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Digital Library Management System</title>

    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Font Awesome -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Custom CSS -->

    <link rel="stylesheet"
          href="../assets/css/admin.css">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center align-items-center"
     style="min-height:100vh;">

<div class="col-md-5">

<div class="card shadow-lg border-0">

<div class="card-header bg-primary text-white text-center">

<h3>

<i class="fas fa-book-reader"></i>

Digital Library

</h3>

<p class="mb-0">

Admin Login

</p>

</div>

<div class="card-body p-4">

<!-- Error Message -->

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<!-- Username / Email -->

<div class="mb-3">

<label class="form-label">

Username or Email

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fas fa-user"></i>

</span>

<input
type="text"
name="username"
class="form-control"
placeholder="Enter Username or Email"
required>

</div>

</div>

<!-- Password -->

<div class="mb-3">

<label class="form-label">

Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fas fa-lock"></i>

</span>

<input
type="password"
name="password"
id="password"
class="form-control"
placeholder="Enter Password"
required>

<button
class="btn btn-outline-secondary"
type="button"
id="togglePassword">

<i class="fas fa-eye"></i>

</button>

</div>

</div>

<!-- Login Button -->

<div class="d-grid">

<button
type="submit"
name="login"
class="btn btn-primary btn-lg">

<i class="fas fa-sign-in-alt"></i>

Login

</button>

</div>

</form>

<hr>

<div class="text-center">

<p class="text-muted">

Digital Library Management System

</p>

</div>

</div>

</div>

</div>

</div>

</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// =====================================
// Show / Hide Password
// =====================================

const togglePassword =
document.getElementById("togglePassword");

const password =
document.getElementById("password");

togglePassword.addEventListener("click", function(){

    const type = password.getAttribute("type") === "password"
        ? "text"
        : "password";

    password.setAttribute("type", type);

    this.innerHTML =
        type === "password"
        ? '<i class="fas fa-eye"></i>'
        : '<i class="fas fa-eye-slash"></i>';

});

// =====================================
// Form Validation
// =====================================

document.querySelector("form")
.addEventListener("submit", function(e){

    const username =
    document.querySelector(
        'input[name="username"]'
    ).value.trim();

    const pass =
    password.value.trim();

    if(username === ""){

        alert("Please enter your username or email.");

        e.preventDefault();

        return;

    }

    if(pass === ""){

        alert("Please enter your password.");

        e.preventDefault();

        return;

    }

});

</script>

</body>
</html>