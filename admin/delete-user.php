<?php
    include('partials/menu.php'); 

    // Start Session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the ID is provided
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Delete the user from the database
        $sql = "DELETE FROM tbl_user WHERE id=$id";
        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['delete'] = "<div class='success'>User deleted successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to delete user. Try again.</div>";
        }
    }

    // Redirect to manage-user.php after deletion
    header('location: manage-user.php');
    exit();
?>
