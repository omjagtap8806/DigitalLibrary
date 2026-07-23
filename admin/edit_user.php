<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/config.php");

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");

if (mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);

$message = "";

if (isset($_POST['update_user'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $status = trim($_POST['status']);

    $sql = "UPDATE users SET
            name='$name',
            email='$email',
            phone='$phone',
            address='$address',
            status='$status'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {

        $message = "<div class='alert alert-success'>
                        User updated successfully.
                    </div>";

        $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
        $user = mysqli_fetch_assoc($result);

    } else {

        $message = "<div class='alert alert-danger'>
                        ".mysqli_error($conn)."
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit User</title>

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

<div class="card-header bg-warning">

<h3>

<i class="fas fa-user-edit"></i>

Edit User

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
               value="<?php echo htmlspecialchars($user['name']); ?>"
               required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email Address
        </label>

        <input type="email"
               name="email"
               class="form-control"
               value="<?php echo htmlspecialchars($user['email']); ?>"
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
               value="<?php echo htmlspecialchars($user['phone']); ?>"
               required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Status
        </label>

        <select name="status" class="form-select" required>

            <option value="Active"
                <?php if($user['status']=="Active") echo "selected"; ?>>
                Active
            </option>

            <option value="Inactive"
                <?php if($user['status']=="Inactive") echo "selected"; ?>>
                Inactive
            </option>

        </select>

    </div>

</div>

<div class="mb-3">

    <label class="form-label">
        Address
    </label>

    <textarea
        name="address"
        class="form-control"
        rows="4"
        required><?php echo htmlspecialchars($user['address']); ?></textarea>

</div>

<div class="text-center">

    <button type="submit"
            name="update_user"
            class="btn btn-warning">

        <i class="fas fa-save"></i>

        Update User

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