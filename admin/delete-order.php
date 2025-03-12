<?php
    include('partials/menu.php');

    // Check if the id is passed in the URL
    if (isset($_GET['id'])) {
        // Get the order ID from URL
        $order_id = $_GET['id'];

        // First, delete the related items from the tbl_order_items table
        $sql = "DELETE FROM tbl_order_items WHERE order_id='$order_id'";
        $res = mysqli_query($conn, $sql);

        // Check if the items are deleted successfully
        if ($res == TRUE) {
            // Now delete the order from the tbl_order table
            $sql2 = "DELETE FROM tbl_order WHERE id='$order_id'";
            $res2 = mysqli_query($conn, $sql2);

            // Check if the order is deleted successfully
            if ($res2 == TRUE) {
                $_SESSION['delete'] = "Order deleted successfully!";
                header('location: manageorder.php');
                exit();
            } else {
                $_SESSION['delete'] = "Failed to delete the order. Please try again.";
                header('location: manageorder.php');
                exit();
            }
        } else {
            $_SESSION['delete'] = "Failed to delete the order items. Please try again.";
            header('location: manageorder.php');
            exit();
        }
    } else {
        // If order ID is not provided
        $_SESSION['delete'] = "No order ID provided.";
        header('location: manageorder.php');
        exit();
    }
?>

