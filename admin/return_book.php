<?php
session_start();

// =====================================
// Check Admin Login
// =====================================

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// =====================================
// Database Connection
// =====================================

include("../includes/config.php");

$success = "";
$error = "";

// =====================================
// Return Book
// =====================================

if (isset($_GET['return_id'])) {

    $issue_id = (int)$_GET['return_id'];

    // Find issued book

    $stmt = mysqli_prepare(
        $conn,
        "SELECT book_id
         FROM issued_books
         WHERE id=? AND status='Issued'"
    );

    mysqli_stmt_bind_param($stmt, "i", $issue_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        $book_id = $row['book_id'];

        // Update issued_books table

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE issued_books
             SET status='Returned'
             WHERE id=?"
        );

        mysqli_stmt_bind_param($stmt, "i", $issue_id);

        mysqli_stmt_execute($stmt);

        // Update books table

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE books
             SET status='Available'
             WHERE id=?"
        );

        mysqli_stmt_bind_param($stmt, "i", $book_id);

        mysqli_stmt_execute($stmt);

        $success = "Book returned successfully.";

    } else {

        $error = "Invalid return request.";

    }

}

// =====================================
// Search
// =====================================

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// =====================================
// Get Issued Books
// =====================================

$query = "

SELECT

issued_books.id,

users.name,

books.title,

issued_books.issue_date,

issued_books.return_date,

issued_books.status

FROM issued_books

INNER JOIN users

ON users.id = issued_books.user_id

INNER JOIN books

ON books.id = issued_books.book_id

WHERE

issued_books.status='Issued'

";

$params = [];
$types = "";

if ($search != "") {

    $query .= "

    AND
    (
        users.name LIKE ?
        OR books.title LIKE ?
    )

    ";

    $keyword = "%" . $search . "%";

    $params[] = $keyword;
    $params[] = $keyword;

    $types .= "ss";
}

$query .= "

ORDER BY issued_books.id DESC

";

$stmt = mysqli_prepare($conn, $query);

if (!empty($params)) {

    mysqli_stmt_bind_param(
        $stmt,
        $types,
        ...$params
    );

}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Return Book</title>

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

<body>

<div class="container mt-5">

    <!-- Page Heading -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>

            <i class="fas fa-undo"></i>

            Return Book

        </h2>

        <a href="dashboard.php" class="btn btn-secondary">

            <i class="fas fa-home"></i>

            Dashboard

        </a>

    </div>

    <!-- Success Message -->

    <?php if($success!=""){ ?>

        <div class="alert alert-success">

            <?php echo $success; ?>

        </div>

    <?php } ?>

    <!-- Error Message -->

    <?php if($error!=""){ ?>

        <div class="alert alert-danger">

            <?php echo $error; ?>

        </div>

    <?php } ?>

    <!-- Search -->

    <form method="GET" class="mb-4">

        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by User or Book..."
                value="<?php echo htmlspecialchars($search); ?>">

            <button
                type="submit"
                class="btn btn-success">

                <i class="fas fa-search"></i>

                Search

            </button>

        </div>

    </form>

    <!-- Issued Books Table -->

    <div class="card shadow">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>User</th>

                    <th>Book</th>

                    <th>Issue Date</th>

                    <th>Return Date</th>

                    <th>Status</th>

                    <th width="140">Action</th>

                </tr>

                </thead>

                <tbody>

                <?php

                if(mysqli_num_rows($result) > 0){

                    while($row = mysqli_fetch_assoc($result)){

                ?>

                <tr>

                    <td>

                        <?php echo $row['id']; ?>

                    </td>

                    <td>

                        <?php echo htmlspecialchars($row['name']); ?>

                    </td>

                    <td>

                        <?php echo htmlspecialchars($row['title']); ?>

                    </td>

                    <td>

                        <?php echo date("d M Y", strtotime($row['issue_date'])); ?>

                    </td>

                    <td>

                        <?php echo date("d M Y", strtotime($row['return_date'])); ?>

                    </td>

                    <td>

                        <span class="badge bg-warning text-dark">

                            <?php echo $row['status']; ?>

                        </span>

                    </td>

                    <td>

                        <a
                            href="return_book.php?return_id=<?php echo $row['id']; ?>"
                            class="btn btn-success btn-sm"
                            onclick="return confirm('Are you sure you want to return this book?');">

                            <i class="fas fa-check"></i>

                            Return

                        </a>

                    </td>

                </tr>

                <?php

                    }

                }else{

                ?>

                <tr>

                    <td colspan="7" class="text-center text-danger">

                        No Issued Books Found

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>
    <!-- Total Issued Books -->

<div class="text-center mt-3">

    <h6>

        Total Issued Books :

        <span class="badge bg-primary">

            <?php echo mysqli_num_rows($result); ?>

        </span>

    </h6>

</div>

</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// ===============================
// Auto Focus Search Box
// ===============================

const searchBox = document.querySelector("input[name='search']");

if(searchBox){
    searchBox.focus();
}

// ===============================
// Highlight Search Text
// ===============================

if(searchBox && searchBox.value.trim() !== ""){

    searchBox.classList.add("border-success");

}

</script>

</body>

</html>