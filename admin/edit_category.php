<?php
include("../includes/config.php");

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM categories WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $category = mysqli_real_escape_string($conn, $_POST['category_name']);

    mysqli_query(
        $conn,
        "UPDATE categories
         SET category_name='$category'
         WHERE id='$id'"
    );

    header("Location: categories.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Edit Category</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body style="background:#f5f7fb;">

<div class="container mt-5">

<div class="card shadow-lg">

<div class="card-header bg-warning text-dark">

<h3>

<i class="fas fa-edit"></i>

Edit Category

</h3>

</div>

<div class="card-body">
    <form method="POST">

<div class="mb-3">

<label class="form-label">

Category Name

</label>

<input
type="text"
name="category_name"
class="form-control"
value="<?php echo $row['category_name']; ?>"
required>

</div>

<button
type="submit"
name="update"
class="btn btn-success">

<i class="fas fa-save"></i>

Update Category

</button>

<a href="categories.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</body>

</html>