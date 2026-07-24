<?php
include("../includes/config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Categories</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body style="background:#f5f7fb;">

<div class="container mt-5">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white">

            <h3>

                <i class="fas fa-layer-group"></i>

                Book Categories

            </h3>

        </div>

        <div class="card-body">
            <form method="GET" class="mb-3">

    <div class="input-group">

        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search Category..."
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

        <button class="btn btn-primary" type="submit">
            <i class="fas fa-search"></i>
            Search
        </button>

    </div>

</form>
            <?php
if(isset($_POST['add_category']))
{
    $category = mysqli_real_escape_string($conn, $_POST['category_name']);

    if($category != "")
    {
        mysqli_query($conn,"INSERT INTO categories(category_name) VALUES('$category')");

        echo "<div class='alert alert-success'>
                Category Added Successfully!
              </div>";
    }
}
?>

<form method="POST" class="row mb-4">

    <div class="col-md-10">
        <input
            type="text"
            name="category_name"
            class="form-control"
            placeholder="Enter New Category"
            required>
    </div>

    <div class="col-md-2">
        <button
            type="submit"
            name="add_category"
            class="btn btn-success w-100">

            <i class="fas fa-plus"></i> Add

        </button>
    </div>

</form>

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Category Name</th>
                        <th>Total Books</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>
                    <?php

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = mysqli_query(
    $conn,
    "SELECT * FROM categories
     WHERE category_name LIKE '%$search%'
     ORDER BY category_name"
);

while($row = mysqli_fetch_assoc($query))
{

?>

<tr>

    <td><?php echo $row['id']; ?></td>

    <td>
    <a href="view_books.php?category=<?php echo urlencode($row['category_name']); ?>" class="text-decoration-none fw-bold">
        <?php echo $row['category_name']; ?>
    </a>
</td>
<td>

<?php

$count = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM books
     WHERE category='".$row['category_name']."'"
);

$total = mysqli_fetch_assoc($count);

echo $total['total'];

?>

</td>

<td>

    <a href="view_books.php?category=<?php echo urlencode($row['category_name']); ?>"
       class="btn btn-sm btn-primary">

        <i class="fas fa-eye"></i> View

    </a>

    <a href="edit_category.php?id=<?php echo $row['id']; ?>"
   class="btn btn-sm btn-warning">

    <i class="fas fa-edit"></i> Edit

</a>

    <a href="delete_category.php?id=<?php echo $row['id']; ?>"
   class="btn btn-sm btn-danger"
   onclick="return confirm('Are you sure you want to delete this category?');">

    <i class="fas fa-trash"></i> Delete

</a>

</td>

</tr>

<?php

}

?>
                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>