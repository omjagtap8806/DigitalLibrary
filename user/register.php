<?php
session_start();
include("../config/config.php");

$message = "";

if(isset($_POST['register']))
{
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if($password != $confirm_password)
    {
        $message = "<div class='alert alert-danger'>Passwords do not match.</div>";
    }
    else
    {
        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0)
        {
            $message = "<div class='alert alert-warning'>Email already registered.</div>";
        }
        else
        {
            $password = md5($password);

            $insert = mysqli_query($conn,"
                INSERT INTO users
                (name,email,phone,address,password,status)
                VALUES
                ('$name','$email','$phone','$address','$password','Active')
            ");

            if($insert)
            {
                echo "<script>
                alert('Registration Successful');
                window.location='login.php';
                </script>";
                exit();
            }
            else
            {
                $message = "<div class='alert alert-danger'>Registration Failed.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

body{
    background:linear-gradient(135deg,#4e73df,#1cc88a);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Arial,Helvetica,sans-serif;
}

.register-box{
    width:500px;
    background:#fff;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.25);
}

</style>

</head>

<body>

<div class="register-box">

<h2 class="text-center mb-4">
<i class="fas fa-user-plus"></i>
User Registration
</h2>

<?php echo $message; ?>

<form method="POST">
    <div class="mb-3">

<label class="form-label">

<i class="fas fa-user"></i>

Full Name

</label>

<input
type="text"
name="name"
class="form-control"
placeholder="Enter Full Name"
required>

</div>

<div class="mb-3">

<label class="form-label">

<i class="fas fa-envelope"></i>

Email Address

</label>

<input
type="email"
name="email"
class="form-control"
placeholder="Enter Email Address"
required>

</div>

<div class="mb-3">

<label class="form-label">

<i class="fas fa-phone"></i>

Phone Number

</label>

<input
type="text"
name="phone"
class="form-control"
placeholder="Enter Phone Number"
required>

</div>

<div class="mb-3">

<label class="form-label">

<i class="fas fa-location-dot"></i>

Address

</label>

<textarea
name="address"
class="form-control"
rows="3"
placeholder="Enter Your Address"
required></textarea>

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
placeholder="Enter Password"
required>

</div>

<div class="mb-4">

<label class="form-label">

<i class="fas fa-lock"></i>

Confirm Password

</label>

<input
type="password"
name="confirm_password"
class="form-control"
placeholder="Confirm Password"
required>

</div>

<div class="d-grid">

<button
type="submit"
name="register"
class="btn btn-success btn-lg">

<i class="fas fa-user-plus"></i>

Register

</button>

</div>

<div class="text-center mt-3">

Already have an account?

<a href="login.php">

Login Here

</a>

</div>

<hr>

<div class="text-center">

<a href="../index.php" class="btn btn-secondary">

<i class="fas fa-home"></i>

Back to Home

</a>

</div>
</form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>