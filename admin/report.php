<?php
session_start();

// ==============================
// Check Admin Login
// ==============================

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ==============================
// Database Connection
// ==============================

include("../includes/config.php");

// ==============================
// Dashboard Statistics
// ==============================

// Total Books
$totalBooks = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM books")
)['total'];

// Total Users
$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];

// Total Issued Books
$totalIssued = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM issued_books
         WHERE status='Issued'"
    )
)['total'];

// Total Returned Books
$totalReturned = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM issued_books
         WHERE status='Returned'"
    )
)['total'];

// Available Books
$totalAvailable = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM books
         WHERE status='Available'"
    )
)['total'];

// Unavailable Books
$totalUnavailable = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM books
         WHERE status='Unavailable'"
    )
)['total'];


// ==============================
// Search & Filters
// ==============================

$search = "";
$issueDate = "";
$returnDate = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

if(isset($_GET['issue_date'])){
    $issueDate = $_GET['issue_date'];
}

if(isset($_GET['return_date'])){
    $returnDate = $_GET['return_date'];
}


// ==============================
// Build Query
// ==============================

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

WHERE 1=1

";

$params = [];
$types = "";

if($search != ""){

    $query .= "

    AND

    (

    users.name LIKE ?

    OR

    books.title LIKE ?

    )

    ";

    $keyword = "%".$search."%";

    $params[] = $keyword;
    $params[] = $keyword;

    $types .= "ss";

}

if($issueDate != ""){

    $query .= "

    AND issue_date = ?

    ";

    $params[] = $issueDate;

    $types .= "s";

}

if($returnDate != ""){

    $query .= "

    AND return_date = ?

    ";

    $params[] = $returnDate;

    $types .= "s";

}

$query .= "

ORDER BY

issued_books.id DESC

";

// Prepare Statement

$stmt = mysqli_prepare($conn, $query);

if(!empty($params)){

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

    <title>Library Reports</title>

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

    <!-- Page Title -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            <i class="fas fa-chart-bar"></i>
            Library Reports
        </h2>

        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i>
            Print Report
        </button>

    </div>

    <!-- Statistics Cards -->

    <div class="row mb-4">

        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h3><?php echo $totalBooks; ?></h3>
                    <p>Total Books</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h3><?php echo $totalUsers; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h3><?php echo $totalIssued; ?></h3>
                    <p>Issued</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h3><?php echo $totalReturned; ?></h3>
                    <p>Returned</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h3><?php echo $totalAvailable; ?></h3>
                    <p>Available</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h3><?php echo $totalUnavailable; ?></h3>
                    <p>Unavailable</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Search & Filter -->

    <div class="card shadow mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search User or Book..."
                            value="<?php echo htmlspecialchars($search); ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="date"
                            name="issue_date"
                            class="form-control"
                            value="<?php echo $issueDate; ?>">

                    </div>

                    <div class="col-md-3 mb-3">

                        <input
                            type="date"
                            name="return_date"
                            class="form-control"
                            value="<?php echo $returnDate; ?>">

                    </div>

                    <div class="col-md-2 mb-3">

                        <button
                            type="submit"
                            class="btn btn-success w-100">

                            <i class="fas fa-search"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- Report Table -->

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

                </tr>

                </thead>

                <tbody>

                <?php

                if(mysqli_num_rows($result) > 0){

                    while($row = mysqli_fetch_assoc($result)){

                ?>

                <tr>

                    <td><?php echo $row['id']; ?></td>

                    <td><?php echo htmlspecialchars($row['name']); ?></td>

                    <td><?php echo htmlspecialchars($row['title']); ?></td>

                    <td><?php echo date("d M Y", strtotime($row['issue_date'])); ?></td>

                    <td><?php echo date("d M Y", strtotime($row['return_date'])); ?></td>

                    <td>

                        <?php

                        if($row['status'] == "Issued"){

                            echo '<span class="badge bg-warning text-dark">Issued</span>';

                        }else{

                            echo '<span class="badge bg-success">Returned</span>';

                        }

                        ?>

                    </td>

                </tr>

                <?php

                    }

                }else{

                ?>

                <tr>

                    <td colspan="6" class="text-center text-danger">

                        No Report Data Found

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>
    <!-- Report Summary -->

<div class="text-center mt-4">

    <h6>

        Total Report Records :

        <span class="badge bg-primary">

            <?php echo mysqli_num_rows($result); ?>

        </span>

    </h6>

</div>

</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// Auto Focus Search Box

document.querySelector("input[name='search']").focus();


// Print Function

function printReport(){

    window.print();

}

</script>

</body>

</html>