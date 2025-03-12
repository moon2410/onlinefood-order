<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['index'])) {
        $index = $_POST['index'];
        // Remove the item from the session
        unset($_SESSION['cart'][$index]);
        // Reindex the cart to prevent gaps in the indices
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
