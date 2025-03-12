<?php 
include('partials/menu.php'); 

// Initialize cart if not already set (this comes from the create-order page)
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the form is submitted to place the order
if (isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Calculate total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

    // Insert the order into the database
    $sql = "INSERT INTO tbl_ad_order (name, phone, address, total, delivery_option, status, created_at) 
            VALUES ('$name', '$phone', '$address', '$total_price', 'delivery', 'Wait For Confirmation', NOW())";
    $res = mysqli_query($conn, $sql);

    if ($res == TRUE) {
        // Get the last inserted order ID
        $order_id = mysqli_insert_id($conn);

        // Insert order items into tbl_order_items
        foreach ($_SESSION['cart'] as $item) {
            $food_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $sql2 = "INSERT INTO tbl_ad_order_items (order_id, food_id, quantity, price) 
                     VALUES ('$order_id', '$food_id', '$quantity', '$price')";
            mysqli_query($conn, $sql2);
        }

        // Clear the cart after placing the order
        unset($_SESSION['cart']);
        $_SESSION['order'] = "Order placed successfully!";
        header('location: order-management.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to place the order. Please try again.";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="heading">Order Details</h1>

        <?php
        // Display any session messages
        if (isset($_SESSION['error'])) {
            echo "<div class='error'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['order'])) {
            echo "<div class='success'>{$_SESSION['order']}</div>";
            unset($_SESSION['order']);
        }
        ?>

        <h3>Your Cart</h3>
        <table id="cart-table">
            <thead>
                <tr>
                    <th>Food</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_price = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $item_total = $item['price'] * $item['quantity'];
                    echo "<tr>
                        <td>{$item['name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>TK {$item['price']}</td>
                        <td>TK {$item_total}</td>
                    </tr>";
                    $total_price += $item_total;
                }
                ?>
            </tbody>
        </table>

        <h3>Total Price: TK <span id="total-price"><?php echo $total_price; ?></span></h3>

        <h3>Customer Details</h3>
        <form action="order-details.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" name="name" required><br><br>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" required><br><br>

            <label for="address">Address:</label>
            <textarea name="address" required></textarea><br><br>

            <input type="submit" name="place_order" value="Place Order" class="btn-primary">
        </form>

    </div>
</div>


<?php include('partials/footer.php'); ?>

<script>
    // If you need to calculate or update anything based on cart changes in this page, you can do it here.
    // This page assumes that the cart is managed from the create-order.php page.
</script>

<style>
    .btn-primary {
        background-color: #28a745;
        color: white;
        padding: 8px 20px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        width: 100%;
    }

    .btn-primary:hover {
        opacity: 0.8;
    }

    #cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    #cart-table th, #cart-table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    #cart-table th {
        background-color: #4CAF50;
        color: white;
    }

    #cart-table td {
        font-size: 14px;
    }

    .error, .success {
        text-align: center;
        padding: 10px;
        margin-bottom: 20px;
    }

    .error {
        background-color: #f44336;
        color: white;
    }

    .success {
        background-color: #4CAF50;
        color: white;
    }
</style>
