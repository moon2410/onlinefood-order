<link rel="stylesheet" href="../css/admin.css">
<?php
// Start session

include('../config/constants.php'); 
//include('partials/menu.php'); 


// Fetch current settings from the database
$sql = "SELECT * FROM tbl_blog_settings WHERE id = 1";
$res = mysqli_query($conn, $sql);
$settings = mysqli_fetch_assoc($res);

// Update settings if the form is submitted
if (isset($_POST['update_settings'])) {
    $header_text = mysqli_real_escape_string($conn, $_POST['header_text']);
    $is_visible = mysqli_real_escape_string($conn, $_POST['is_visible']);

    $sql_update = "UPDATE tbl_blog_settings SET header_text='$header_text', is_visible='$is_visible' WHERE id = 1";
    if (mysqli_query($conn, $sql_update)) {
        $_SESSION['message'] = "Settings updated successfully!";
        header('location: manage-blog.php');
    } else {
        $_SESSION['error'] = "Failed to update settings!";
    }
}
?>

<section class="blog-settings">
    <div class="container">
        <h2 class="text-center">Manage Blog Section Settings</h2>
        
        <!-- Display success or error message -->
        <?php if (isset($_SESSION['message'])) { ?>
            <div class="success"><?php echo $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="error"><?php echo $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>

        <form action="manage-blog-settings.php" method="POST">
            <label for="header_text">Header Text:</label>
            <input type="text" name="header_text" value="<?php echo $settings['header_text']; ?>" required><br>

            <label for="is_visible">Is Blog Section Visible?</label>
            <select name="is_visible" required>
                <option value="Yes" <?php if ($settings['is_visible'] == 'Yes') echo 'selected'; ?>>Yes</option>
                <option value="No" <?php if ($settings['is_visible'] == 'No') echo 'selected'; ?>>No</option>
            </select><br>

            <input type="submit" name="update_settings" value="Update Settings" class="btn btn-success">
        </form>
    </div>
</section>
<br><br>
<a href="manage-blog.php" class="btn btn-primary">Back to Manage Blog</a>
<br><br>
<?php include('partials/footer.php'); ?>
