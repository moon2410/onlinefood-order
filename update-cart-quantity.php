<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cart'])) {
        foreach ($_POST['cart'] as $index => $item) {
            if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                $_SESSION['cart'][$index]['quantity'] = intval($item['quantity']);
            }
        }
    }
}
