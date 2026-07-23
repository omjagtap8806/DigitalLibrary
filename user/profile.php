<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/config.php");

$user_id = $_SESSION['user_id'];
$message = "";

// Update Profile
if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $update = mysqli_query($conn,"
        UPDATE users SET
        name='$name',
        phone='$phone',
        address='$address'
        WHERE id='$user_id'
    ");

    if($update){
        $message="<div class='alert alert-success'>Profile Updated Successfully.</div>";
    }else{
        $message="<div class='alert alert-danger'>Failed to Update Profile.</div>";
    }
}

$user=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'"));
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#eef2f7;
}

.profile-box{
    max-width:750px;
    margin:40px auto;
    background:#fff;
    padding:35px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.15);
}

.profile-icon{
    text-align:center;
    font-size:90px;
    color:#0d6efd;
    margin-bottom:20px;
}

.form-control{
    margin-bottom:15px;
}

</style>

</head>

<body>

<div class="container">

<div class="profile-box">

<div class="profile-icon">
<i class="fas fa-user-circle"></i>
</div>

<h2 class="text-center mb-4">
My Profile
</h2>

<?php echo $message; ?>
<form method="POST">

<div class="row">

<div class="col-md-6">

<label class="form-label">
<i class="fas fa-user"></i>
Full Name
</label>

<input
type="text"
name="name"
class="form-control"
value="<?php echo $user['name']; ?>"
required>

</div>

<div class="col-md-6">

<label class="form-label">
<i class="fas fa-envelope"></i>
Email Address
</label>

<input
type="email"
class="form-control"
value="<?php echo $user['email']; ?>"
readonly>

</div>

</div>

<div class="row mt-3">

<div class="col-md-6">

<label class="form-label">
<i class="fas fa-phone"></i>
Phone Number
</label>

<input
type="text"
name="phone"
class="form-control"
value="<?php echo $user['phone']; ?>">

</div>

<div class="col-md-6">

<label class="form-label">
<i class="fas fa-check-circle"></i>
Account Status
</label>

<input
type="text"
class="form-control"
value="<?php echo $user['status']; ?>"
readonly>

</div>

</div>

<div class="mt-3">

<label class="form-label">
<i class="fas fa-map-marker-alt"></i>
Address
</label>

<textarea
name="address"
class="form-control"
rows="4"><?php echo $user['address']; ?></textarea>

</div>

<div class="d-grid mt-4">

<button
type="submit"
name="update"
class="btn btn-primary btn-lg">

<i class="fas fa-save"></i>

Update Profile

</button>

</div>

</form>
<hr class="my-4">

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