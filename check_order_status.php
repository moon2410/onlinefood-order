<?php
// Start session
session_start();

// Include database connection
include('config/constants.php');

// Check if order_id is passed
if (isset($_GET['order_id'])) {
    // Get the order ID
    $order_id = $_GET['order_id'];

    // Fetch the current status of the order from the database
    $sql = "SELECT status FROM tbl_order WHERE id = '$order_id' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    // Check if the order exists
    if (mysqli_num_rows($res) > 0) {
        // Get the status of the order
        $row = mysqli_fetch_assoc($res);
        $status = $row['status'];

        // Prepare the response
        $response = array(
            'status' => $status
        );

        // Return the response as JSON
        echo json_encode($response);
    } else {
        // Order not found
        echo json_encode(array('status' => 'Order not found'));
    }
} else {
    // If no order_id is provided
    echo json_encode(array('status' => 'No order ID provided'));
}
?>
