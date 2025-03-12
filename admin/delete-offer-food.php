<?php
// Include database connection
include('../config/constants.php');

// Check if the id is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the image name for deletion
    if (isset($_GET['image_name'])) {
        $image_name = $_GET['image_name'];

        // Remove the image from the folder
        if ($image_name != "") {
            $path = "../images/offer/" . $image_name;
            $remove = unlink($path);
            if ($remove == false) {
                $_SESSION['upload'] = "Failed to remove the image";
                header('location:' . SITEURL . 'admin/manage-offer-food.php');
                die();
            }
        }
    }

    // Delete the offer food from the database
    $sql = "DELETE FROM tbl_offer WHERE id=$id";
    $res = mysqli_query($conn, $sql);

    if ($res == true) {
        $_SESSION['delete'] = "Offer Food Deleted Successfully";
        header('location:' . SITEURL . 'admin/manage-offer-food.php');
    } else {
        $_SESSION['delete'] = "Failed to Delete Offer Food";
        header('location:' . SITEURL . 'admin/manage-offer-food.php');
    }
}
?>
