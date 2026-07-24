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
$category = "";

if(isset($_GET['category']))
{
    $category = $_GET['category'];
}

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
// Count Total Records
// ==============================

if($category != "")
{
    $countSQL = "SELECT COUNT(*) AS total
                 FROM books
                 WHERE category=? AND
                 (
                    title LIKE ?
                    OR author LIKE ?
                    OR isbn LIKE ?
                 )";
}
else
{
    $countSQL = "SELECT COUNT(*) AS total
                 FROM books
                 WHERE
                 title LIKE ?
                 OR author LIKE ?
                 OR category LIKE ?
                 OR isbn LIKE ?";
}

$stmt = mysqli_prepare($conn, $countSQL);

$keyword = "%".$search."%";

if($category != "")
{
    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $category,
        $keyword,
        $keyword,
        $keyword
    );
}
else
{
    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $keyword,
        $keyword,
        $keyword,
        $keyword
    );
}

mysqli_stmt_execute($stmt);

$countResult = mysqli_stmt_get_result($stmt);

$totalRow = mysqli_fetch_assoc($countResult);

$totalBooks = $totalRow['total'];

$totalPages = ceil($totalBooks / $limit);

// ==============================
// Get Books
// ==============================

if($category != "")
{
    $sql = "SELECT *
            FROM books
            WHERE category=?
            AND
            (
                title LIKE ?
                OR author LIKE ?
                OR isbn LIKE ?
            )
            ORDER BY id DESC
            LIMIT ?, ?";
}
else
{
    $sql = "SELECT *
            FROM books
            WHERE
            title LIKE ?
            OR author LIKE ?
            OR category LIKE ?
            OR isbn LIKE ?
            ORDER BY id DESC
            LIMIT ?, ?";
}

$stmt = mysqli_prepare($conn, $sql);

if($category != "")
{
    mysqli_stmt_bind_param(
        $stmt,
        "ssssii",
        $category,
        $keyword,
        $keyword,
        $keyword,
        $offset,
        $limit
    );
}
else
{
    mysqli_stmt_bind_param(
        $stmt,
        "ssssii",
        $keyword,
        $keyword,
        $keyword,
        $keyword,
        $offset,
        $limit
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

    <title>View Books</title>

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

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="fas fa-book"></i>

Manage Books

</h2>

<a href="add_book.php" class="btn btn-primary">

<i class="fas fa-plus"></i>

Add New Book

</a>

</div>

<!-- Search Form -->

<form method="GET" class="mb-4">

<div class="row g-2">

    <div class="col-md-4">

        <select name="category" class="form-select">

            <option value="">All Categories</option>

            <?php

            $catQuery = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name");

            while($cat = mysqli_fetch_assoc($catQuery))
            {
            ?>

            <option value="<?php echo $cat['category_name']; ?>"
            <?php if($category == $cat['category_name']) echo "selected"; ?>>

                <?php echo $cat['category_name']; ?>

            </option>

            <?php } ?>

        </select>

    </div>

    <div class="col-md-6">

        <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search Book..."
        value="<?php echo htmlspecialchars($search); ?>">

    </div>

    <div class="col-md-2">

        <button class="btn btn-success w-100" type="submit">

            <i class="fas fa-search"></i>

            Search

        </button>

    </div>

</div>

</form>

<!-- Books Table -->

<div class="card shadow">

<div class="card-body table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Cover</th>

<th>Title</th>

<th>Author</th>

<th>Category</th>

<th>ISBN</th>

<th>Status</th>

<th>PDF</th>

<th width="180">Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0){

while($book=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo $book['id']; ?>

</td>

<td>

<img
src="../uploads/covers/<?php echo htmlspecialchars($book['cover_image']); ?>"
width="60"
height="80"
class="img-thumbnail">

</td>

<td>

<?php echo htmlspecialchars($book['title']); ?>

</td>

<td>

<?php echo htmlspecialchars($book['author']); ?>

</td>

<td>

<?php echo htmlspecialchars($book['category']); ?>

</td>

<td>

<?php echo htmlspecialchars($book['isbn']); ?>

</td>

<td>

<?php

if($book['status']=="Available"){

echo '<span class="badge bg-success">Available</span>';

}else{

echo '<span class="badge bg-danger">Unavailable</span>';

}

?>

</td>

<td>

<a
href="../uploads/books/<?php echo htmlspecialchars($book['book_file']); ?>"
target="_blank"
class="btn btn-info btn-sm">

<i class="fas fa-file-pdf"></i>

View

</a>

</td>

<td>

<a
href="edit_book.php?id=<?php echo $book['id']; ?>"
class="btn btn-warning btn-sm">

<i class="fas fa-edit"></i>

Edit

</a>

<a
href="delete_book.php?id=<?php echo $book['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure you want to delete this book?');">

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

<td colspan="9" class="text-center text-danger">

No Books Found

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

<li class="page-item <?php if($page<=1) echo 'disabled'; ?>">

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

<li class="page-item <?php if($page>=$totalPages) echo 'disabled'; ?>">

<a class="page-link"
href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page+1; ?>">

Next

</a>

</li>

</ul>

</nav>

<?php } ?>

<!-- Total Books -->

<div class="text-center mt-3">

<h6>

Total Books :

<span class="badge bg-primary">

<?php echo $totalBooks; ?>

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