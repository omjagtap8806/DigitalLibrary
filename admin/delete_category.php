<?php
include("../includes/config.php");

$id = $_GET['id'];

// Get category name
$result = mysqli_query($conn, "SELECT * FROM categories WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

$category = $row['category_name'];
// Count books in this category
$count = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM books
     WHERE category='$category'"
);

$data = mysqli_fetch_assoc($count);

if($data['total'] > 0)
{
    echo "<script>
            alert('Cannot delete this category because it contains books.');
            window.location='categories.php';
          </script>";

    exit();
}
mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");

echo "<script>
        alert('Category deleted successfully.');
        window.location='categories.php';
      </script>";
?>