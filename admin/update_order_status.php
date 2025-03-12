<?php 
// Start session
session_start();

// Include database connection
include('../config/constants.php');

// Initialize the response array
$response = array();

// Prepare the query to get all counts in a single query
$sql = "
    SELECT 
        (SELECT COUNT(*) FROM tbl_order WHERE status = 'Wait For Confirmation') AS wait_for_confirmation,
        (SELECT COUNT(*) FROM tbl_order WHERE status = 'Accepted') AS accepted_orders,
        (SELECT COUNT(*) FROM tbl_order WHERE status = 'Processing') AS processing_orders,
        (SELECT COUNT(*) FROM tbl_order) AS total_orders,
        (SELECT COUNT(*) FROM tbl_order WHERE status = 'Delivered') AS completed_orders,
        (SELECT COUNT(*) FROM tbl_order WHERE status = 'Ready For Delivery') AS ready_for_delivery,
        (SELECT COUNT(*) FROM tbl_order WHERE delivery_option = 'delivery') AS total_home_delivery_orders,
        (SELECT COUNT(*) FROM tbl_order WHERE delivery_option = 'pickup') AS total_pickup_orders,
        (SELECT SUM(total) FROM tbl_order WHERE status = 'Delivered') AS total_revenue,
        (SELECT SUM(total) - SUM(delivery_charge) FROM tbl_order WHERE status = 'Delivered') AS total_revenue_without_delivery_charge,
        (SELECT COUNT(*) FROM tbl_order WHERE status = 'Cancelled') AS cancelled_orders,
        (SELECT COUNT(*) FROM tbl_user) AS total_users,
        (SELECT COUNT(*) FROM tbl_food) AS total_foods,
        (SELECT COUNT(*) FROM tbl_category) AS food_categories
";

// Execute the query
$res = mysqli_query($conn, $sql);

// Check if the query is successful and fetch the results
if ($res) {
    $row = mysqli_fetch_assoc($res);

    // Add the fetched data to the response array
    $response['waitForConfirmation'] = $row['wait_for_confirmation'];
    $response['acceptedOrders'] = $row['accepted_orders'];
    $response['processingOrders'] = $row['processing_orders'];
    $response['totalOrders'] = $row['total_orders'];
    $response['completedOrders'] = $row['completed_orders'];
    $response['readyForDelivery'] = $row['ready_for_delivery'];
    $response['totalHomeDeliveryOrders'] = $row['total_home_delivery_orders'];
    $response['totalPickupOrders'] = $row['total_pickup_orders'];
    $response['totalRevenue'] = $row['total_revenue'];
    $response['totalRevenueWithoutDeliveryCharge'] = $row['total_revenue_without_delivery_charge'];
    $response['cancelledOrders'] = $row['cancelled_orders'];
    $response['totalUsers'] = $row['total_users'];
    $response['totalFoods'] = $row['total_foods'];
    $response['foodCategories'] = $row['food_categories'];
} else {
    // If query fails, return an error message
    $response['error'] = 'Failed to fetch data from the database.';
}

// Return the response as JSON
echo json_encode($response);

?>
