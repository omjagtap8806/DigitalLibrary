<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/config.php");

$message = "";

if (isset($_POST['add_user'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {

        $message = "<div class='alert alert-danger'>
                        Email already exists.
                    </div>";

    } else {

        

        $sql = "INSERT INTO users
        (name,email,phone,address,status,created_at)
        VALUES
        ('$name','$email','$phone','$address',
        'Active',NOW())";

        if (mysqli_query($conn, $sql)) {

            $message = "<div class='alert alert-success'>
                            User Added Successfully.
                        </div>";

        } else {

            $message = "<div class='alert alert-danger'>
                            Error : ".mysqli_error($conn)."
                        </div>";

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

<title>Add User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet"
href="../assets/css/admin.css">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

<i class="fas fa-user-plus"></i>

Add New User

</h3>

</div>

<div class="card-body">

<?php echo $message; ?>

<form method="POST">
    <div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Full Name
        </label>

        <input type="text"
               name="name"
               class="form-control"
               placeholder="Enter Full Name"
               required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email Address
        </label>

        <input type="email"
               name="email"
               class="form-control"
               placeholder="Enter Email"
               required>

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Phone Number
        </label>

        <input type="text"
               name="phone"
               class="form-control"
               placeholder="Enter Phone Number"
               required>

    </div>

    <div class="col-md-6 mb-3">

        

    </div>

</div>

<div class="mb-3">

    <label class="form-label">
        Address
    </label>

    <textarea name="address"
              class="form-control"
              rows="4"
              placeholder="Enter Address"
              required></textarea>

</div>

<div class="text-center">

    <button type="submit"
            name="add_user"
            class="btn btn-success">

        <i class="fas fa-user-plus"></i>

        Add User

    </button>

    <a href="manage_users.php"
       class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>

        Back

    </a>

</div>
</form>

</div>

</div>

</div>

</div>

</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>