<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/config.php");

$message = "";

// Issue Book
if (isset($_POST['issue_book'])) {

    $user_id = (int)$_POST['user_id'];
    $book_id = (int)$_POST['book_id'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    // Check if book exists
    $bookCheck = mysqli_query($conn,
        "SELECT * FROM books WHERE id='$book_id'");

    if (mysqli_num_rows($bookCheck) == 0) {

        $message = "<div class='alert alert-danger'>
                        Book not found.
                    </div>";

    } else {

        // Check if already issued
        $alreadyIssued = mysqli_query(
            $conn,
            "SELECT * FROM issued_books
             WHERE book_id='$book_id'
             AND status='Issued'"
        );

        if (mysqli_num_rows($alreadyIssued) > 0) {

            $message = "<div class='alert alert-warning'>
                            This book is already issued.
                        </div>";

        } else {

            $sql = "INSERT INTO issued_books
                    (user_id,book_id,issue_date,return_date,status)
                    VALUES
                    ('$user_id','$book_id',
                     '$issue_date','$return_date',
                     'Issued')";

            if (mysqli_query($conn, $sql)) {

    // Update book status
    mysqli_query($conn,
    "UPDATE books
     SET status='Unavailable'
     WHERE id='$book_id'");

    $message = "<div class='alert alert-success'>
                    Book issued successfully.
                </div>";

} else {

                $message = "<div class='alert alert-danger'>
                                ".mysqli_error($conn)."
                            </div>";
            }
        }
    }
}

// Load Users
$users = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE status='Active'
     ORDER BY name ASC"
);

// Load Books
$books = mysqli_query(
    $conn,
    "SELECT * FROM books
     ORDER BY title ASC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Issue Book</title>

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

<i class="fas fa-book"></i>

Issue Book

</h3>

</div>

<div class="card-body">

<?php echo $message; ?>

<form method="POST">
    <div class="mb-3">

    <label class="form-label">
        Select User
    </label>

    <select name="user_id" class="form-select" required>

        <option value="">
            -- Select User --
        </option>

        <?php
        while($user = mysqli_fetch_assoc($users)){
        ?>

        <option value="<?php echo $user['id']; ?>">

            <?php
            echo htmlspecialchars($user['name']);
            ?>
            (<?php echo htmlspecialchars($user['email']); ?>)

        </option>

        <?php
        }
        ?>

    </select>

</div>

<div class="mb-3">

    <label class="form-label">
        Select Book
    </label>

    <select name="book_id" class="form-select" required>

        <option value="">
            -- Select Book --
        </option>

        <?php
        while($book = mysqli_fetch_assoc($books)){
        ?>

        <option value="<?php echo $book['id']; ?>">

            <?php
            echo htmlspecialchars($book['title']);
            ?>
            -
            <?php
            echo htmlspecialchars($book['author']);
            ?>

        </option>

        <?php
        }
        ?>

    </select>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Issue Date
        </label>

        <input
            type="date"
            name="issue_date"
            class="form-control"
            value="<?php echo date('Y-m-d'); ?>"
            required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Return Date
        </label>

        <input
            type="date"
            name="return_date"
            class="form-control"
            required>

    </div>

</div>

<div class="text-center">

    <button
        type="submit"
        name="issue_book"
        class="btn btn-primary">

        <i class="fas fa-book"></i>

        Issue Book

    </button>

    <a
        href="dashboard.php"
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