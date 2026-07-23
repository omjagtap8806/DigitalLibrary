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

// Check Book ID
if (!isset($_GET['id'])) {
    header("Location: view_books.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch Book Details
$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: view_books.php");
    exit();
}

$book = mysqli_fetch_assoc($result);

// Update Book
if (isset($_POST['updateBook'])) {

    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $isbn = trim($_POST['isbn']);
    $publisher = trim($_POST['publisher']);
    $publication_year = trim($_POST['publication_year']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    // Keep Existing Files
    $pdfName = $book['book_file'];
    $imageName = $book['cover_image'];

    // ==========================
    // Upload New PDF
    // ==========================

    if (!empty($_FILES['book_file']['name'])) {

        $pdfExtension = strtolower(
            pathinfo($_FILES['book_file']['name'], PATHINFO_EXTENSION)
        );

        if ($pdfExtension == "pdf") {

            // Delete Old PDF
            $oldPDF = "../uploads/books/" . $book['book_file'];

            if (file_exists($oldPDF)) {
                unlink($oldPDF);
            }

            // Upload New PDF
            $pdfName = time() . "_" . $_FILES['book_file']['name'];

            move_uploaded_file(
                $_FILES['book_file']['tmp_name'],
                "../uploads/books/" . $pdfName
            );

        } else {

            $error = "Only PDF files are allowed.";

        }

    }

    // ==========================
    // Upload New Cover Image
    // ==========================

    if (!empty($_FILES['cover_image']['name'])) {

        $imgExtension = strtolower(
            pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION)
        );

        if (
            $imgExtension == "jpg" ||
            $imgExtension == "jpeg" ||
            $imgExtension == "png"
        ) {

            // Delete Old Image
            $oldImage = "../uploads/covers/" . $book['cover_image'];

            if (file_exists($oldImage)) {
                unlink($oldImage);
            }

            // Upload New Image
            $imageName = time() . "_" . $_FILES['cover_image']['name'];

            move_uploaded_file(
                $_FILES['cover_image']['tmp_name'],
                "../uploads/covers/" . $imageName
            );

        } else {

            $error = "Only JPG, JPEG or PNG images are allowed.";

        }

    }

    // ==========================
    // Update Database
    // ==========================

    if (empty($error)) {

        $update = mysqli_prepare($conn,

        "UPDATE books SET

        title=?,
        author=?,
        category=?,
        isbn=?,
        publisher=?,
        publication_year=?,
        description=?,
        book_file=?,
        cover_image=?,
        status=?

        WHERE id=?"

        );

        mysqli_stmt_bind_param(

            $update,

            "ssssssssssi",

            $title,
            $author,
            $category,
            $isbn,
            $publisher,
            $publication_year,
            $description,
            $pdfName,
            $imageName,
            $status,
            $id

        );

        if (mysqli_stmt_execute($update)) {

            $success = "Book Updated Successfully.";

            // Reload Updated Data
            $stmt = mysqli_prepare($conn,
                "SELECT * FROM books WHERE id=?");

            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $book = mysqli_fetch_assoc($result);

        } else {

            $error = "Database Error.";

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

    <title>Edit Book</title>

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

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="card shadow-lg">

<div class="card-header bg-warning text-dark">

<h3>

<i class="fas fa-edit"></i>

Edit Book

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

<form method="POST" enctype="multipart/form-data">

<div class="row">

<!-- Book Title -->

<div class="col-md-6 mb-3">

<label class="form-label">Book Title</label>

<input
type="text"
name="title"
class="form-control"
value="<?php echo htmlspecialchars($book['title']); ?>"
required>

</div>

<!-- Author -->

<div class="col-md-6 mb-3">

<label class="form-label">Author</label>

<input
type="text"
name="author"
class="form-control"
value="<?php echo htmlspecialchars($book['author']); ?>"
required>

</div>

<!-- Category -->

<div class="col-md-6 mb-3">

<label class="form-label">Category</label>

<select
name="category"
class="form-select"
required>

<option <?php if($book['category']=="Programming") echo "selected"; ?>>Programming</option>

<option <?php if($book['category']=="Database") echo "selected"; ?>>Database</option>

<option <?php if($book['category']=="Networking") echo "selected"; ?>>Networking</option>

<option <?php if($book['category']=="Artificial Intelligence") echo "selected"; ?>>Artificial Intelligence</option>

<option <?php if($book['category']=="Machine Learning") echo "selected"; ?>>Machine Learning</option>

<option <?php if($book['category']=="Cyber Security") echo "selected"; ?>>Cyber Security</option>

<option <?php if($book['category']=="Operating System") echo "selected"; ?>>Operating System</option>

<option <?php if($book['category']=="Web Development") echo "selected"; ?>>Web Development</option>

</select>

</div>

<!-- ISBN -->

<div class="col-md-6 mb-3">

<label class="form-label">ISBN</label>

<input
type="text"
name="isbn"
class="form-control"
value="<?php echo htmlspecialchars($book['isbn']); ?>"
required>

</div>

<!-- Publisher -->

<div class="col-md-6 mb-3">

<label class="form-label">Publisher</label>

<input
type="text"
name="publisher"
class="form-control"
value="<?php echo htmlspecialchars($book['publisher']); ?>">

</div>

<!-- Publication Year -->

<div class="col-md-6 mb-3">

<label class="form-label">Publication Year</label>

<input
type="number"
name="publication_year"
class="form-control"
value="<?php echo htmlspecialchars($book['publication_year']); ?>">

</div>

<!-- Current Cover -->

<div class="col-md-6 mb-3">

<label class="form-label">Current Cover</label>

<br>

<img
src="../uploads/covers/<?php echo htmlspecialchars($book['cover_image']); ?>"
width="120"
class="img-thumbnail"
id="preview">

</div>

<!-- Upload New Cover -->

<div class="col-md-6 mb-3">

<label class="form-label">

Upload New Cover (Optional)

</label>

<input
type="file"
name="cover_image"
class="form-control"
accept=".jpg,.jpeg,.png">

</div>

<!-- Upload New PDF -->

<div class="col-md-12 mb-3">

<label class="form-label">

Upload New PDF (Optional)

</label>

<input
type="file"
name="book_file"
class="form-control"
accept=".pdf">

</div>

<!-- Status -->

<div class="col-md-6 mb-3">

<label class="form-label">Status</label>

<select
name="status"
class="form-select">

<option value="Available"
<?php if($book['status']=="Available") echo "selected"; ?>>

Available

</option>

<option value="Unavailable"
<?php if($book['status']=="Unavailable") echo "selected"; ?>>

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
class="form-control"><?php echo htmlspecialchars($book['description']); ?></textarea>

</div>

<!-- Buttons -->

<div class="col-md-12 text-center">

<button
type="submit"
name="updateBook"
class="btn btn-warning btn-lg">

<i class="fas fa-save"></i>

Update Book

</button>

<a
href="view_books.php"
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

// ===============================
// Cover Image Preview
// ===============================

const coverInput = document.querySelector('input[name="cover_image"]');
const preview = document.getElementById("preview");

coverInput.addEventListener("change", function () {

    const file = this.files[0];

    if(file){

        // Check image type
        const allowed = ["image/jpeg","image/jpg","image/png"];

        if(!allowed.includes(file.type)){

            alert("Only JPG, JPEG and PNG images are allowed.");

            this.value = "";

            return;

        }

        // Preview image
        const reader = new FileReader();

        reader.onload = function(e){

            preview.src = e.target.result;

        }

        reader.readAsDataURL(file);

    }

});


// ===============================
// PDF Validation
// ===============================

const pdfInput = document.querySelector('input[name="book_file"]');

pdfInput.addEventListener("change", function(){

    const file = this.files[0];

    if(file){

        if(file.type !== "application/pdf"){

            alert("Only PDF files are allowed.");

            this.value = "";

            return;

        }

        // Maximum Size = 20 MB

        if(file.size > 20 * 1024 * 1024){

            alert("PDF must be smaller than 20 MB.");

            this.value = "";

        }

    }

});


// ===============================
// Confirm Update
// ===============================

document.querySelector("form")
.addEventListener("submit", function(e){

    if(!confirm("Are you sure you want to update this book?")){

        e.preventDefault();

    }

});

</script>

</body>
</html>
