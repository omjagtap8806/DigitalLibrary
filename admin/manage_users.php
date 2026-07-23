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
// Search
// ==============================

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// ==============================
// Pagination
// ==============================

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

// ==============================
// Count Total Users
// ==============================

$countQuery = "
SELECT COUNT(*) AS total
FROM users
WHERE
name LIKE ?
OR email LIKE ?
OR phone LIKE ?
";

$stmt = mysqli_prepare($conn, $countQuery);

$keyword = "%" . $search . "%";

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $keyword,
    $keyword,
    $keyword
);

mysqli_stmt_execute($stmt);

$countResult = mysqli_stmt_get_result($stmt);

$totalRow = mysqli_fetch_assoc($countResult);

$totalUsers = $totalRow['total'];

$totalPages = ceil($totalUsers / $limit);

// ==============================
// Get Users
// ==============================

$query = "
SELECT *
FROM users
WHERE
name LIKE ?
OR email LIKE ?
OR phone LIKE ?
ORDER BY id DESC
LIMIT ?, ?
";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "sssii",
    $keyword,
    $keyword,
    $keyword,
    $offset,
    $limit
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Users</title>

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

    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            <i class="fas fa-users"></i>
            Manage Users
        </h2>

        <a href="register.php" class="btn btn-primary">
            <i class="fas fa-user-plus"></i>
            Add User
        </a>

    </div>

    <!-- Search -->

    <form method="GET" class="mb-4">

        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by Name, Email or Phone..."
                value="<?php echo htmlspecialchars($search); ?>">

            <button class="btn btn-success" type="submit">
                <i class="fas fa-search"></i>
                Search
            </button>

        </div>

    </form>

    <!-- Users Table -->

    <div class="card shadow">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Phone</th>

                    <th>Address</th>

                    <th>Status</th>

                    <th>Registered</th>

                    <th width="180">Action</th>

                </tr>

                </thead>

                <tbody>

                <?php
                if(mysqli_num_rows($result) > 0){

                    while($user = mysqli_fetch_assoc($result)){
                ?>

                <tr>

                    <td><?php echo $user['id']; ?></td>

                    <td><?php echo htmlspecialchars($user['name']); ?></td>

                    <td><?php echo htmlspecialchars($user['email']); ?></td>

                    <td><?php echo htmlspecialchars($user['phone']); ?></td>

                    <td><?php echo htmlspecialchars($user['address']); ?></td>

                    <td>

                        <?php
                        if($user['status']=="Active"){
                            echo '<span class="badge bg-success">Active</span>';
                        }else{
                            echo '<span class="badge bg-danger">Inactive</span>';
                        }
                        ?>

                    </td>

                    <td>

                        <?php
                        echo date(
                            "d M Y",
                            strtotime($user['created_at'])
                        );
                        ?>

                    </td>

                    <td>

                        <a href="edit_user.php?id=<?php echo $user['id']; ?>"
                           class="btn btn-warning btn-sm">

                            <i class="fas fa-edit"></i>
                            Edit

                        </a>

                        <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this user?');">

                            <i class="fas fa-trash"></i>
                            Delete

                        </a>

                    </td>

                </tr>

                <?php

                    }

                }else{

                ?>

                <tr>

                    <td colspan="8" class="text-center text-danger">

                        No Users Found

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>
        <!-- Pagination -->

    <?php if($totalPages > 1){ ?>

    <nav class="mt-4">

        <ul class="pagination justify-content-center">

            <!-- Previous -->

            <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">

                <a class="page-link"
                   href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page-1; ?>">

                    Previous

                </a>

            </li>

            <!-- Page Numbers -->

            <?php
            for($i=1; $i<=$totalPages; $i++){
            ?>

            <li class="page-item <?php if($page==$i) echo 'active'; ?>">

                <a class="page-link"
                   href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>">

                    <?php echo $i; ?>

                </a>

            </li>

            <?php } ?>

            <!-- Next -->

            <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">

                <a class="page-link"
                   href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page+1; ?>">

                    Next

                </a>

            </li>

        </ul>

    </nav>

    <?php } ?>

    <!-- Total Users -->

    <div class="text-center mt-3">

        <h6>

            Total Users :

            <span class="badge bg-primary">

                <?php echo $totalUsers; ?>

            </span>

        </h6>

    </div>

</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// Auto Focus Search Box

document.querySelector("input[name='search']").focus();

</script>

</body>

</html>