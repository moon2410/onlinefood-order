<link rel="stylesheet" href="../css/admin.css">


<?php
// Start session


include('../config/constants.php'); 


// Get the blog ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch blog details
    $sql = "SELECT * FROM tbl_blog WHERE id='$id'";
    $res = mysqli_query($conn, $sql);
    $blog = mysqli_fetch_assoc($res);
}

if (isset($_POST['update_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Handle image upload
    if (isset($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "images/blog/" . $image_name;
        move_uploaded_file($image_tmp, $image_path);
    } else {
        $image_name = $blog['image_name']; // Use the existing image if no new one is uploaded
    }

    // Update blog details in the database
    $sql = "UPDATE tbl_blog SET title='$title', description='$description', image_name='$image_name', status='$status' WHERE id='$id'";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['message'] = "Blog updated successfully!";
        header('location: manage-blog.php');
    } else {
        $_SESSION['error'] = "Failed to update blog!";
    }
}
?>

<section class="edit-blog">
    <div class="container">
        <h2 class="text-center">Edit Blog</h2>
        <form action="update-blog.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $blog['title']; ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" rows="5" required><?php echo $blog['description']; ?></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image">

            <label for="status">Status:</label>
            <select name="status" required>
                <option value="Active" <?php if ($blog['status'] == 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($blog['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>

            <input type="submit" name="update_blog" value="Update Blog" class="btn btn-success">
        </form>
    </div>
</section>

<?php include('partials/footer.php'); ?>
