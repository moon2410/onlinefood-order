<?php
// Start session
session_start();
include('../config/constants.php');


// Get blog ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the blog
    $sql = "DELETE FROM tbl_blog WHERE id='$id'";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['message'] = "Blog deleted successfully!";
        header('location: manage-blog.php');
    } else {
        $_SESSION['error'] = "Failed to delete blog!";
        header('location: manage-blog.php');
    }
}
?>
