<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/config.php");

$user_id = $_SESSION['user_id'];
$message = "";

if(isset($_POST['change_password']))
{
    $current_password = md5($_POST['current_password']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id' AND password='$current_password'");

    if(mysqli_num_rows($check) == 0)
    {
        $message = "<div class='alert alert-danger'>Current password is incorrect.</div>";
    }
    elseif($new_password != $confirm_password)
    {
        $message = "<div class='alert alert-warning'>New passwords do not match.</div>";
    }
    else
    {
        $new_password = md5($new_password);

        $update = mysqli_query($conn,
        "UPDATE users SET password='$new_password' WHERE id='$user_id'");

        if($update)
        {
            $message = "<div class='alert alert-success'>Password changed successfully.</div>";
        }
        else
        {
            $message = "<div class='alert alert-danger'>Failed to change password.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Change Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>

body{
background:#eef2f7;
}

.box{
max-width:600px;
margin:50px auto;
background:#fff;
padding:30px;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,.15);
}

</style>

</head>

<body>

<div class="container">

<div class="box">

<h2 class="text-center mb-4">
<i class="fas fa-key"></i>
Change Password
</h2>

<?php echo $message; ?>

<form method="POST">
    <div class="mb-3">
    <label class="form-label">
        <i class="fas fa-lock"></i> Current Password
    </label>
    <input type="password"
           name="current_password"
           class="form-control"
           placeholder="Enter Current Password"
           required>
</div>

<div class="mb-3">
    <label class="form-label">
        <i class="fas fa-key"></i> New Password
    </label>
    <input type="password"
           name="new_password"
           class="form-control"
           placeholder="Enter New Password"
           required>
</div>

<div class="mb-3">
    <label class="form-label">
        <i class="fas fa-check-circle"></i> Confirm New Password
    </label>
    <input type="password"
           name="confirm_password"
           class="form-control"
           placeholder="Confirm New Password"
           required>
</div>

<div class="d-grid gap-2">
    <button type="submit"
            name="change_password"
            class="btn btn-primary btn-lg">
        <i class="fas fa-save"></i>
        Change Password
    </button>
</div>

<hr class="my-4">
</form>

<hr>

<div class="d-flex justify-content-between">

<a href="dashboard.php" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Back to Dashboard
</a>

<a href="logout.php" class="btn btn-danger">
    <i class="fas fa-sign-out-alt"></i>
    Logout
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