<?php
session_start();

// Check Admin Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database Connection
include("../includes/config.php");

// Success & Error Messages
$success = "";
$error = "";

// Check Form Submission
if (isset($_POST['addBook'])) {

    // Get Form Data
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $isbn = trim($_POST['isbn']);
    $publisher = trim($_POST['publisher']);
    $publication_year = trim($_POST['publication_year']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    // ===============================
    // Upload Book PDF
    // ===============================

    $bookFile = $_FILES['book_file']['name'];
    $bookTmp = $_FILES['book_file']['tmp_name'];
    $bookSize = $_FILES['book_file']['size'];

    // ===============================
    // Upload Cover Image
    // ===============================

    $coverImage = $_FILES['cover_image']['name'];
    $coverTmp = $_FILES['cover_image']['tmp_name'];
    $coverSize = $_FILES['cover_image']['size'];

    // Allowed Extensions
    $allowedPDF = ['pdf'];
    $allowedImage = ['jpg','jpeg','png'];

    $bookExtension = strtolower(pathinfo($bookFile, PATHINFO_EXTENSION));
    $coverExtension = strtolower(pathinfo($coverImage, PATHINFO_EXTENSION));

    // Validation
    if(empty($title) || empty($author) || empty($category))
    {
        $error = "Please fill all required fields.";
    }

    elseif(!in_array($bookExtension, $allowedPDF))
    {
        $error = "Only PDF books are allowed.";
    }

    elseif(!in_array($coverExtension, $allowedImage))
    {
        $error = "Cover image must be JPG, JPEG or PNG.";
    }

    elseif($bookSize > 20000000)
    {
        $error = "PDF size should be less than 20 MB.";
    }

    elseif($coverSize > 5000000)
    {
        $error = "Image size should be less than 5 MB.";
    }

    else
    {

        // Duplicate ISBN Check

        $check = mysqli_prepare($conn,
        "SELECT id FROM books WHERE isbn=?");

        mysqli_stmt_bind_param($check,"s",$isbn);

        mysqli_stmt_execute($check);

        mysqli_stmt_store_result($check);

        if(mysqli_stmt_num_rows($check)>0)
        {
            $error = "ISBN already exists.";
        }

        else
        {

            // Generate Unique File Names

            $pdfName = time()."_".$bookFile;
            $imageName = time()."_".$coverImage;

            $pdfDestination =
            "../uploads/books/".$pdfName;

            $imageDestination =
            "../uploads/covers/".$imageName;

            // Upload Files

            move_uploaded_file(
                $bookTmp,
                $pdfDestination
            );

            move_uploaded_file(
                $coverTmp,
                $imageDestination
            );

            // Insert Data

            $sql = mysqli_prepare($conn,

            "INSERT INTO books
            (
                title,
                author,
                category,
                isbn,
                publisher,
                publication_year,
                description,
                book_file,
                cover_image,
                status
            )

            VALUES

            (
                ?,?,?,?,?,?,?,?,?,?
            )"

            );

            mysqli_stmt_bind_param(

                $sql,

                "ssssssssss",

                $title,
                $author,
                $category,
                $isbn,
                $publisher,
                $publication_year,
                $description,
                $pdfName,
                $imageName,
                $status

            );

            if(mysqli_stmt_execute($sql))
            {
                $success =
                "Book Added Successfully.";
            }
            else
            {
                $error =
                "Database Error.";
            }

        }

    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add New Book</title>

    <!-- Bootstrap CSS -->

    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <!-- Font Awesome -->

    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Custom CSS -->

    <link
    rel="stylesheet"
    href="../assets/css/admin.css">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="card shadow-lg">

<div class="card-header bg-primary text-white">

<h3>

<i class="fas fa-book-medical"></i>

Add New Book

</h3>

</div>

<div class="card-body">

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

<form

method="POST"

enctype="multipart/form-data"

>

<div class="row">

<!-- Book Title -->

<div class="col-md-6 mb-3">

<label class="form-label">

Book Title

</label>

<input

type="text"

name="title"

class="form-control"

required>

</div>

<!-- Author -->

<div class="col-md-6 mb-3">

<label class="form-label">

Author

</label>

<input

type="text"

name="author"

class="form-control"

required>

</div>

<!-- Category -->

<div class="col-md-6 mb-3">

<label class="form-label">

Category

</label>

<select

name="category"

class="form-select"

required>

<option value="">Select Category</option>

<?php

$category_query = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");

while($category = mysqli_fetch_assoc($category_query))
{
?>

<option value="<?php echo $category['category_name']; ?>">
    <?php echo $category['category_name']; ?>
</option>

<?php
}
?>

</select>

</div>

<!-- ISBN -->

<div class="col-md-6 mb-3">

<label class="form-label">

ISBN

</label>

<input

type="text"

name="isbn"

class="form-control"

required>

</div>

<!-- Publisher -->

<div class="col-md-6 mb-3">

<label class="form-label">

Publisher

</label>

<input

type="text"

name="publisher"

class="form-control">

</div>

<!-- Publication Year -->

<div class="col-md-6 mb-3">

<label class="form-label">

Publication Year

</label>

<input

type="number"

name="publication_year"

class="form-control"

min="1900"

max="2100">

</div>

<!-- Upload PDF -->

<div class="col-md-6 mb-3">

<label class="form-label">

Upload PDF Book

</label>

<input

type="file"

name="book_file"

class="form-control"

accept=".pdf"

required>

</div>

<!-- Cover Image -->

<div class="col-md-6 mb-3">

<label class="form-label">

Book Cover

</label>

<input

type="file"

name="cover_image"

class="form-control"

accept=".jpg,.jpeg,.png"

required>

</div>

<!-- Status -->

<div class="col-md-6 mb-3">

<label class="form-label">

Status

</label>

<select

name="status"

class="form-select">

<option value="Available">

Available

</option>

<option value="Unavailable">

Unavailable

</option>

</select>

</div>

<!-- Description -->

<div class="col-md-12 mb-3">

<label class="form-label">

Description

</label>

<textarea

name="description"

rows="5"

class="form-control"

placeholder="Write Book Description..."></textarea>

</div>

<!-- Buttons -->

<div class="col-md-12 text-center">

<button

type="submit"

name="addBook"

class="btn btn-success btn-lg">

<i class="fas fa-save"></i>

Save Book

</button>

<a

href="dashboard.php"

class="btn btn-secondary btn-lg">

<i class="fas fa-arrow-left"></i>

Back

</a>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// Image Preview

document.querySelector('input[name="cover_image"]')
.addEventListener('change', function(){

    const file = this.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(e){

            let preview =
            document.getElementById("preview");

            preview.src = e.target.result;

            preview.style.display = "block";

        }

        reader.readAsDataURL(file);

    }

});

// PDF Validation

document.querySelector('input[name="book_file"]')
.addEventListener('change', function(){

    const file = this.files[0];

    if(file){

        if(file.type !== "application/pdf"){

            alert("Only PDF files are allowed.");

            this.value="";

        }

    }

});

</script>

</body>

</html>
<img id="preview" class="preview" src="" alt="Book Cover Preview">