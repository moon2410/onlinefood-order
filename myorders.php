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

// Get the latest order for the user
$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM tbl_order WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 1";
$res = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($res);

?>

<div class="main-content">
    <div class="wrapper">
        <h1>Your Latest Order</h1>
        <?php
        if ($order) {
            $order_id = $order['id'];
            $sql2 = "SELECT * FROM tbl_order_items WHERE order_id='$order_id'";
            $res2 = mysqli_query($conn, $sql2);
            ?>
            <p><strong>Status:</strong> <span id="order-status" class="status <?php echo $order['status']; ?>"><?php echo $order['status']; ?></span></p>
            <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
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
                $total_price = 0; // Initialize total price variable
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
                        <td><?php echo '$' . $item['price']; ?></td>
                        <td><?php echo '$' . $item_total; ?></td>
                    </tr>
                <?php } ?>
            </table>

            <!-- If Delivery is selected, include delivery charge in the total -->
            <?php
                if ($order['delivery_option'] == 'delivery') {
                    $total_price += $order['delivery_charge']; // Add delivery charge to total if delivery is selected
                }
            ?>
            <h3>Total Price: $<?php echo $total_price; ?></h3> <!-- Show total price with or without delivery charge -->
        <?php
        } else {
            echo "<p>You have no orders.</p>";
        }

        // Show all previous orders
        $sql_all_orders = "SELECT * FROM tbl_order WHERE user_id='$user_id' ORDER BY created_at DESC";
        $res_all_orders = mysqli_query($conn, $sql_all_orders);

        if (mysqli_num_rows($res_all_orders) > 0) {
            echo "<h2>Previous Orders:</h2>";
            echo "<table class='tbl-full'>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Delivery Option</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>";

            while ($row = mysqli_fetch_assoc($res_all_orders)) {
                // Get order details
                $order_id = $row['id'];
                $status = $row['status'];
                $order_total = $row['total']; // Order total includes the item prices (delivery charge should not be added again here)
                $delivery_option = $row['delivery_option'];
                $order_date = $row['created_at'];

                // Get food details for this order
                $food_sql = "SELECT food_id, quantity FROM tbl_order_items WHERE order_id='$order_id'";
                $food_res = mysqli_query($conn, $food_sql);
                $food_list = '';
                while ($food_item = mysqli_fetch_assoc($food_res)) {
                    $food_id = $food_item['food_id'];
                    $quantity = $food_item['quantity'];

                    $food_name_sql = "SELECT title FROM tbl_food WHERE id='$food_id'";
                    $food_name_res = mysqli_query($conn, $food_name_sql);
                    $food_name_row = mysqli_fetch_assoc($food_name_res);
                    $food_name = $food_name_row['title'];

                    $food_list .= $food_name . " (Qty: " . $quantity . "), ";
                }
                $food_list = rtrim($food_list, ', ');  // Remove last comma

                // Total price in previous orders: Don't add delivery charge again, use the total stored in the database
                echo "<tr>
                        <td>{$order_id}</td>
                        <td><span class='status {$status}'>{$status}</span></td>
                        <td>$ {$order_total}</td> <!-- Use the order total which already includes delivery charge -->
                        <td>{$delivery_option}</td>
                        <td>{$order_date}</td>
                        <td><a href='view-order.php?id={$order_id}' class='btn-primary'>View Order</a></td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No previous orders found.</p>";
        }
        ?>
    </div>
</div>

<!-- CSS to make it more visually appealing -->
<style>
    .status {
        font-weight: bold;
        padding: 5px;
        border-radius: 3px;
        text-transform: capitalize;
    }

    .status.delivered {
        background-color: #4CAF50;
        color: white;
    }

    .status.cancelled {
        background-color: #f44336;
        color: white;
    }

    .status.waiting {
        background-color: #ff9800;
        color: white;
    }

    .status.accepted {
        background-color: #2196F3;
        color: white;
    }

    .status.processing {
        background-color: #FFC107;
        color: white;
    }

    .tbl-full {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .tbl-full th, .tbl-full td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .tbl-full th {
        background-color: #f1f1f1;
    }

    .tbl-full td {
        background-color: #fff;
    }

    .tbl-full tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .btn-primary {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }

    .error {
        color: red;
        font-size: 18px;
    }

    .btn-primary:focus {
        outline: none;
    }
</style>

<script>
    // Function to check and update order status dynamically
    function checkOrderStatus() {
        var orderId = <?php echo $order['id']; ?>; // Get the order ID
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'check_order_status.php?order_id=' + orderId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                var newStatus = response.status;
                var statusElement = document.getElementById('order-status');

                // Update status text and color dynamically based on the status
                statusElement.innerText = newStatus;

                if (newStatus === 'Delivered') {
                    statusElement.className = 'status delivered';
                } else if (newStatus === 'Cancelled') {
                    statusElement.className = 'status cancelled';
                } else if (newStatus === 'Wait For Confirmation') {
                    statusElement.className = 'status waiting';
                } else if (newStatus === 'Accepted') {
                    statusElement.className = 'status accepted';
                } else if (newStatus === 'Processing') {
                    statusElement.className = 'status processing';
                }
            }
        };
        xhr.send();
    }

    // Run the checkOrderStatus function every 5 seconds to check for updates
    setInterval(checkOrderStatus, 5000);
</script>

<?php include('partials-front/footer.php'); ?>
