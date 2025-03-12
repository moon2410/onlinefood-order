<?php
include('../config/constants.php');

if(isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update status in the database
    $sql = "UPDATE tbl_ad_order SET status='$status' WHERE id='$order_id'";
    $res = mysqli_query($conn, $sql);

    if($res == TRUE) {
        $_SESSION['update'] = "<div class='success'>Order status updated successfully.</div>";
    } else {
        $_SESSION['update'] = "<div class='error'>Failed to update the order status.</div>";
    }
    header('location:' . SITEURL . 'admin/order-management.php');
}
?>
