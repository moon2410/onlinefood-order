<?php 
include('partials/menu.php');

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if form is submitted
if (isset($_POST['place_order'])) {
    // Get user details from form
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $total_price = 0;

    // Check if cart is empty
    if (empty($_SESSION['cart'])) {
        header("Location: create-order.php");
        exit();
    }

    // Insert Order Data into tbl_ad_order
    $sql_order = "INSERT INTO tbl_ad_order (name, phone, address, total, status) VALUES ('$name', '$phone', '$address', '$total_price', 'Pending')";
    $res_order = mysqli_query($conn, $sql_order);

    if ($res_order) {
        // Get Last Inserted Order ID
        $order_id = mysqli_insert_id($conn);

        // Insert Cart Items into tbl_ad_order_items
        foreach ($_SESSION['cart'] as $item) {
            $food_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total_item_price = $price * $quantity;

            // Insert item into tbl_ad_order_items
            $sql_item = "INSERT INTO tbl_ad_order_items (order_id, food_id, quantity, price) VALUES ('$order_id', '$food_id', '$quantity', '$total_item_price')";
            mysqli_query($conn, $sql_item);

            // Update the total price of the order
            $total_price += $total_item_price;
        }

        // Update total price in tbl_ad_order
        $sql_update_total = "UPDATE tbl_ad_order SET total = '$total_price' WHERE id = '$order_id'";
        mysqli_query($conn, $sql_update_total);

        // Clear cart session after placing the order
        $_SESSION['cart'] = [];

        // Redirect to the order management page
        header("Location: order-management.php");
        exit();
    } else {
        $error_message = "Failed to place the order. Please try again.";
    }
}
?>

