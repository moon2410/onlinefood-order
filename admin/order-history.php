<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="heading">Order History</h1>
        <br />

        <!-- Display the total statistics (Total Orders, Delivered Orders, Total Earnings) -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <?php
                // Get total orders count
                $total_orders_sql = "SELECT COUNT(*) AS total_orders FROM tbl_ad_order";
                $total_orders_res = mysqli_query($conn, $total_orders_sql);
                $total_orders_row = mysqli_fetch_assoc($total_orders_res);
                $total_orders = $total_orders_row['total_orders'];

                // Get delivered orders count
                $delivered_orders_sql = "SELECT COUNT(*) AS delivered_orders FROM tbl_ad_order WHERE status='Delivered'";
                $delivered_orders_res = mysqli_query($conn, $delivered_orders_sql);
                $delivered_orders_row = mysqli_fetch_assoc($delivered_orders_res);
                $delivered_orders = $delivered_orders_row['delivered_orders'];

                // Get total earnings for delivered orders only
                $total_earnings_sql = "SELECT SUM(total) AS total_earnings FROM tbl_ad_order WHERE status='Delivered'";
                $total_earnings_res = mysqli_query($conn, $total_earnings_sql);
                $total_earnings_row = mysqli_fetch_assoc($total_earnings_res);
                $total_earnings = $total_earnings_row['total_earnings'];

                // Display the statistics
                echo "<p><strong>Total Orders:</strong> $total_orders</p>";
                echo "<p><strong>Delivered Orders:</strong> $delivered_orders</p>";
                echo "<p><strong>Total Earnings:</strong> TK $total_earnings</p>";
            ?>

            <!-- Button for history page -->
            <br><br>
            <a href="order-management.php" class="btn btn-primary">Back to Manage Orders</a>
        </div>

        <br /><br />

        <!-- Date Filter to Show Orders and Earnings -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <label for="date">Filter by Date:</label>
            <input type="date" name="date" id="date" required>
            <input type="submit" value="Filter" class="btn-primary">
            <button type="button" id="clear-filter" class="btn-secondary">Clear Filter</button> <!-- Clear Filter Button -->
        </form>

    
        <?php
            // Check if the user is filtering by date
            if (isset($_GET['date'])) {
                $date = $_GET['date'];

                // Get earnings for the selected date (only delivered orders)
                $earnings_date_sql = "SELECT SUM(total) AS total_earnings FROM tbl_ad_order WHERE DATE(created_at) = '$date' AND status='Delivered'";
                $earnings_date_res = mysqli_query($conn, $earnings_date_sql);
                $earnings_date_row = mysqli_fetch_assoc($earnings_date_res);
                $earnings_date = $earnings_date_row['total_earnings'];

                echo "<h3>Total Earnings on $date: TK $earnings_date</h3>";
            }
        ?>

        <table class="tbl-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Date</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Food Items</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Print</th>
                </tr>
            </thead>

            <tbody>
            <?php 
                // Fetch orders based on the selected date if available
                if (isset($_GET['date'])) {
                    $date = $_GET['date'];
                    $sql = "SELECT * FROM tbl_ad_order WHERE DATE(created_at) = '$date' ORDER BY created_at DESC"; // Filter by date
                } else {
                    $sql = "SELECT * FROM tbl_ad_order ORDER BY created_at DESC"; // Display all orders
                }

                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);

                $sn = 1;

                if($count > 0) {
                    // Orders are available
                    while($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $order_date = $row['created_at'];
                        $status = $row['status'];
                        $customer_name = $row['name'];
                        $phone = $row['phone'];
                        $address = $row['address'];
                        $total_price = $row['total'];

                        // Fetch food items for the current order
                        $food_sql = "SELECT * FROM tbl_ad_order_items WHERE order_id='$id'";
                        $food_res = mysqli_query($conn, $food_sql);
                        $food_items = '';
                        while ($food_row = mysqli_fetch_assoc($food_res)) {
                            $food_id = $food_row['food_id'];
                            $quantity = $food_row['quantity'];
                            $food_name_sql = "SELECT title FROM tbl_food WHERE id='$food_id'";
                            $food_name_res = mysqli_query($conn, $food_name_sql);
                            $food_name_row = mysqli_fetch_assoc($food_name_res);
                            $food_items .= $food_name_row['title'] . " (Qty: $quantity), ";
                        }
                        $food_items = rtrim($food_items, ', ');

                        // Set the status class based on order status
                        switch($status) {
                            case 'Pending':
                                $status_class = 'status-pending';
                                break;
                            case 'Confirmed':
                                $status_class = 'status-confirmed';
                                break;
                            case 'Processing':
                                $status_class = 'status-processing';
                                break;
                            case 'Delivered':
                                $status_class = 'status-delivered';
                                break;
                            case 'Cancelled':
                                $status_class = 'status-cancelled';
                                break;
                            default:
                                $status_class = '';
                        }

                        ?>
                        <tr>
                            <td><?php echo $sn++; ?> </td>
                            <td><?php echo $order_date; ?></td>
                            <td><?php echo $customer_name; ?></td>
                            <td><?php echo $phone; ?></td>
                            <td><?php echo $address; ?></td>
                            <td><?php echo $food_items; ?></td>
                            <td><?php echo 'TK' . $total_price; ?></td>
                            <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                            <td>
                                <form action="update-ad-order.php" method="POST">
                                    <select name="status">
                                        <option <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option <?php if($status == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                                        <option <?php if($status == 'Processing') echo 'selected'; ?>>Processing</option>
                                        <option <?php if($status == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                        <option <?php if($status == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="order_id" value="<?php echo $id; ?>">
                                    <input type="submit" value="Update Status" class="btn-secondary">
                                </form>
                            </td>
                            <td><button onclick="printOrder('<?php echo $id; ?>')" class="btn-primary">Print</button></td>
                        </tr>
                    <?php
                    }
                } else {
                    echo "<tr><td colspan='10' class='error'>No Orders Available</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Function to print the order details
    function printOrder(orderId) {
        window.open('print_order.php?id=' + orderId, '_blank', 'width=600,height=600');
    }
    // Clear the filter and reload the page
    document.getElementById('clear-filter').addEventListener('click', function() {
        document.getElementById('date').value = ''; // Reset the date field
        window.location.href = 'order-history.php'; // Reload the page without filter
    });


</script>

<?php include('partials/footer.php'); ?>

<style>
    .btn-primary {
        background-color: rgb(107, 207, 160);
        color: white;
        padding: 10px 20px;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .btn-secondary {
        background-color: rgb(119, 76, 175);
        color: white;
        padding: 10px 20px;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .tbl-full {
        width: 100%;
        border-collapse: collapse;
    }

    .tbl-full th, .tbl-full td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
        font-size: 16px;
    }

    .tbl-full th {
        background-color: #4CAF50;
        color: white;
    }

    .tbl-full td {
        font-size: 14px;
    }

    /* Status Color Coding */
    .status-pending {
        color: orange;
        font-weight: bold;
    }

    .status-confirmed {
        color: blue;
        font-weight: bold;
    }

    .status-processing {
        color: yellow;
        font-weight: bold;
    }

    .status-delivered {
        color: green;
        font-weight: bold;
    }

    .status-cancelled {
        color: red;
        font-weight: bold;
    }

    /* Order Summary Styles */
    .order-summary {
        text-align: right;
        margin-bottom: 20px;
    }

    .order-summary h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .order-summary p {
        font-size: 16px;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }
</style>
