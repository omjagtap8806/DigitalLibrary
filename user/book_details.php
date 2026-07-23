<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../config/config.php");

if (!isset($_GET['id']))
{
    header("Location: available_books.php");
    exit();
}

$book_id = intval($_GET['id']);

$query = "SELECT * FROM books WHERE id='$book_id'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0)
{
    die("Book not found.");
}

$book = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Book Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#eef2f7;
}

.container-box{
    max-width:900px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,.1);
}

.book-cover{
    width:250px;
    height:330px;
    object-fit:cover;
    border-radius:8px;
    border:1px solid #ddd;
}

</style>

</head>

<body>

<div class="container">

<div class="container-box">
    <div class="row">

    <!-- Book Cover -->

    <div class="col-md-4 text-center">

        <?php
        if(!empty($book['cover_image']))
        {
        ?>
            <img src="../uploads/covers/<?php echo $book['cover_image']; ?>" class="book-cover">
        <?php
        }
        else
        {
        ?>
            <img src="../assets/images/no-book.png" class="book-cover">
        <?php
        }
        ?>

    </div>

    <!-- Book Details -->

    <div class="col-md-8">

        <h2 class="mb-4 text-primary">

            <i class="fas fa-book"></i>

            <?php echo $book['title']; ?>

        </h2>

        <table class="table table-bordered">

            <tr>

                <th width="35%">Author</th>

                <td><?php echo $book['author']; ?></td>

            </tr>

            <tr>

                <th>Category</th>

                <td><?php echo $book['category']; ?></td>

            </tr>

            <tr>

                <th>ISBN</th>

                <td><?php echo $book['isbn']; ?></td>

            </tr>

            <tr>

                <th>Publisher</th>

                <td><?php echo $book['publisher']; ?></td>

            </tr>

            <tr>

                <th>Publication Year</th>

                <td><?php echo $book['publication_year']; ?></td>

            </tr>

            <tr>

                <th>Status</th>

                <td>

                    <?php
                    if($book['status']=="Available")
                    {
                        echo "<span class='badge bg-success'>Available</span>";
                    }
                    else
                    {
                        echo "<span class='badge bg-danger'>Issued</span>";
                    }
                    ?>

                </td>

            </tr>

            <tr>

                <th>Description</th>

                <td><?php echo nl2br($book['description']); ?></td>

            </tr>

        </table>

    </div>

</div>
<hr>

<div class="d-flex justify-content-between mt-4">

    <a href="available_books.php" class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>

        Back to Available Books

    </a>
    <?php
if($book['status'] == "Available")
{
?>
<a href="issue_book.php?book_id=<?php echo $book['id']; ?>"
   class="btn btn-primary"
   onclick="return confirm('Do you want to issue this book?');">

    <i class="fas fa-book"></i>
    Issue Book

</a>
<?php
}
else
{
?>
<button class="btn btn-danger" disabled>

    <i class="fas fa-times-circle"></i>
    Already Issued

</button>
<?php
}
?>

    <?php
    if(!empty($book['book_file']))
    {
    ?>

    <a href="../uploads/books/<?php echo $book['book_file']; ?>"
       class="btn btn-success"
       target="_blank">

        <i class="fas fa-download"></i>

        Download Book

    </a>

    <?php
    }
    else
    {
    ?>

    <button class="btn btn-danger" disabled>

        <i class="fas fa-times-circle"></i>

        Book File Not Available

    </button>

    <?php
    }
    ?>

</div>

<hr>

<div class="text-center mt-4">

    <h5 class="text-primary">

        <i class="fas fa-book-reader"></i>

        Digital Library Management System

    </h5>

    <p class="text-muted">

        Read • Learn • Grow

    </p>

    <small class="text-muted">

        © <?php echo date("Y"); ?> Digital Library. All Rights Reserved.

    </small>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>