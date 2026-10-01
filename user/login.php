<?php
session_start();
include("../config/config.php");


$error = "";

if(isset($_SESSION['user_id']))
{
    header("Location: dashboard.php");
    exit();
}

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
$password = md5(trim($_POST['password']));



    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND password='$password'
            AND status='Active'";

    $result = mysqli_query($conn, $sql);


    if(mysqli_num_rows($result) == 1)
    {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        header("Location: dashboard.php");
        exit();
    }
    else
    {
        $error = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Login | Digital Library Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    background:linear-gradient(135deg,#4e73df,#224abe);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    width:420px;
    background:#fff;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.3);
}

.login-box h2{
    text-align:center;
    margin-bottom:25px;
}

</style>

</head>

<body>

<div class="login-box">

<h2>
<i class="fas fa-user-graduate"></i>
Student Login
</h2>
<?php
if($error != "")
{
?>
<div class="alert alert-danger text-center">
    <?php echo $error; ?>
</div>
<?php
}
?>

<form method="POST">

    <div class="mb-3">

        <label class="form-label">
            <i class="fas fa-envelope"></i>
            Email Address
        </label>

       <input
    type="text"
    name="email"
    id="email"
    class="form-control"
    required>

<p id="show"></p>



    </div>

    <div class="mb-3">

        <label class="form-label">
            <i class="fas fa-lock"></i>
            Password
        </label>

        <input
            type="password"
            name="password"
            class="form-control"
            placeholder="Enter your password"
            required>

    </div>

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

    <a href="../index.php" class="text-decoration-none">

        <i class="fas fa-home"></i>
        Back to Home

    </a>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>