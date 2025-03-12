
<link rel="stylesheet" href="../css/admin.css">
<?php 
include('../config/constants.php'); 
//include('partials/menu.php'); 
?>
<?php




// Handle form submission
if (isset($_POST['add_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Handle image upload
    if (isset($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "../images/blog/" . $image_name;
        move_uploaded_file($image_tmp, $image_path);
    } else {
        $image_name = "";
    }

    // Insert the blog into the database
    $sql = "INSERT INTO tbl_blog (title, description, image_name, status) VALUES ('$title', '$description', '$image_name', '$status')";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['message'] = "Blog added successfully!";
        header('location: manage-blog.php');
    } else {
        $_SESSION['error'] = "Failed to add blog!";
    }
}
?>

<section class="add-blog">
    <div class="container">
        <h2 class="text-center">Add New Blog</h2>
        <form action="add-blog.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" required><br>

            <label for="description">Description:</label>
            <textarea name="description" rows="5" required></textarea><br>

            <label for="image">Image:</label>
            <input type="file" name="image"><br>

            <label for="status">Status:</label>
            <select name="status" required><br>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <input type="submit" name="add_blog" value="Add Blog" class="btn btn-success">
        </form>
    </div>
</section>
<br><br>
<a href="manage-blog.php" class="btn btn-primary">Back to Manage Blog</a>
<br><br>
<?php include('partials/footer.php'); ?>
