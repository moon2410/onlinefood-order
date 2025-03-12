<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('partials-front/header.php');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

// Get the order ID from the URL parameter
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
} else {
    echo "<p>Order not found.</p>";
    exit();
}

// Get the order details from the database
$sql = "SELECT * FROM tbl_order WHERE id='$order_id' AND user_id='{$_SESSION['user']['id']}'";
$res = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($res);

if ($order) {
    // Get the order items
    $order_id = $order['id'];
    $sql2 = "SELECT * FROM tbl_order_items WHERE order_id='$order_id'";
    $res2 = mysqli_query($conn, $sql2);
    ?>

    <div class="main-content">
        <div class="wrapper order-details">
            <h1>Order Details</h1>
            <p><strong>Status:</strong> <span class="status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span></p>
            <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['created_at']; ?></p>
            <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
            <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
            <p><strong>Account Holder Name:</strong> <?php echo $order['account_holder']; ?></p>
            <p><strong>Delivery Option:</strong> <?php echo $order['delivery_option']; ?></p>

            <h3>Ordered Items:</h3>
            <table class="tbl-full">
                <tr>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php
                    $total_price = 0;
                    while ($item = mysqli_fetch_assoc($res2)) {
                        // Fetch food name using food_id from the tbl_food table
                        $food_id = $item['food_id'];
                        $food_sql = "SELECT title FROM tbl_food WHERE id='$food_id'";
                        $food_res = mysqli_query($conn, $food_sql);
                        $food_row = mysqli_fetch_assoc($food_res);
                        $food_name = $food_row['title'];

                        // Calculate total for this item
                        $item_total = $item['quantity'] * $item['price'];
                        $total_price += $item_total;
                ?>
                    <tr>
                        <td><?php echo $food_name; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo '$' . number_format($item['price'], 2); ?></td>
                        <td><?php echo '$' . number_format($item_total, 2); ?></td>
                    </tr>
                <?php } ?>
            </table>

            <!-- If Delivery is selected, include delivery charge in the total -->
            <?php
                $final_total = $total_price;  // Start with the total of items
                if ($order['delivery_option'] == 'delivery') {
                    $final_total += $order['delivery_charge']; // Add delivery charge to total if delivery is selected
                }
            ?>
            <h3>Total Price: $<?php echo number_format($final_total, 2); ?></h3>

            <a href="myorders.php" class="btn-primary">Back to My Orders</a>

        </div>
    </div>

    <?php
} else {
    echo "<p>Order not found.</p>";
}

include('partials-front/footer.php');
?>

<!-- CSS for dynamic UI -->
<style>
    .order-details {
        background-color: #f8f8f8;
        border-radius: 8px;
        padding: 30px;
        margin-top: 20px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .order-details h1 {
        color: #333;
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .order-details p {
        font-size: 18px;
        line-height: 1.6;
    }

    .status-processing {
        color: #ff9800;
        font-weight: bold;
    }

    .status-completed {
        color: #4CAF50;
        font-weight: bold;
    }

    .status-cancelled {
        color: #f44336;
        font-weight: bold;
    }

    .tbl-full {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .tbl-full th, .tbl-full td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .tbl-full th {
        background-color: #4CAF50;
        color: white;
    }

    .tbl-full tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .tbl-full tr:hover {
        background-color: #f1f1f1;
    }

    .btn-primary {
        display: inline-block;
        padding: 12px 24px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 5px;
        margin-top: 20px;
        text-align: center;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }

    .error {
        color: red;
        font-size: 18px;
        margin-top: 20px;
    }

    .btn-primary:focus {
        outline: none;
    }
</style>
