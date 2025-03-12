<?php 
    session_start();

    // Check if cart session exists
    if (isset($_SESSION['cart'])) {
        $index = $_GET['index'];

        // Remove item from the cart based on index
        unset($_SESSION['cart'][$index]);

        // Reindex the array to fix the indexes after removing an item
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    // Redirect back to the cart page
    header('location: cart.php');
?>
