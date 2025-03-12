<?php
// Start session
session_start();

// Include database connection
include('config/constants.php');

// Check if food_id is passed
if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];

    // Initialize the cart if not already initialized
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Fetch food details from the database
    $sql = "SELECT * FROM tbl_food WHERE id = '$food_id' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $food = mysqli_fetch_assoc($res);

        // Create item to add to cart
        $item = [
            'food_id' => $food['id'],
            'title' => $food['title'],
            'price' => $food['price'],
            'quantity' => 1 // default quantity is 1
        ];

        // Check if the item already exists in the cart
        $exists = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['food_id'] == $food_id) {
                $cart_item['quantity'] += 1; // Increase quantity if item exists
                $exists = true;
                break;
            }
        }

        // If the item does not exist, add it to the cart
        if (!$exists) {
            $_SESSION['cart'][] = $item;
        }

        // Return the updated cart count
        echo count($_SESSION['cart']);
    } else {
        echo "Food item not found!";
    }
}
?>
