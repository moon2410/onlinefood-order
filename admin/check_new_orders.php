<?php
// Start session
session_start();

// Include database connection
include('../config/constants.php');

// Initialize response array
$response = array();

// Fetch orders with 'Wait For Confirmation' status
$sql = "SELECT * FROM tbl_order WHERE status = 'Wait For Confirmation' ORDER BY created_at DESC LIMIT 1";
$res = mysqli_query($conn, $sql);
$count = mysqli_num_rows($res);

if ($count > 0) {
    // Get the latest order details
    $row = mysqli_fetch_assoc($res);
    $response['newOrder'] = true;
    $response['order_id'] = $row['id'];
    $response['order_name'] = $row['name'];
    $response['order_status'] = $row['status'];
    $response['order_time'] = $row['created_at'];

    // Send a notification sound and visual message
    $response['notificationMessage'] = "New Order Received - Wait For Confirmation!";
    $response['sound'] = 'notify.mp3'; // You can add a sound file for notifications
} else {
    $response['newOrder'] = false;
    $response['notificationMessage'] = "No New Orders";
    $response['sound'] = '';
}

// Return the response as JSON
echo json_encode($response);
?>
