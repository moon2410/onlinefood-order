<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="heading">Manage Food Orders</h1>

        <br />

        <?php 
            if(isset($_SESSION['update'])){
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
        ?>

        <!-- Button to open Change Delivery Charge pop-up -->
        <a href="javascript:void(0);" class="btn btn-secondary" onclick="openDeliveryPopup()">Change Delivery Charge</a>

        <script>
            // Function to open delivery charge popup
            function openDeliveryPopup() {
                window.open('change_delivery_charge.php', 'ChangeDeliveryCharge', 'width=400,height=300,resizable=yes,scrollbars=yes');
            }

            



// Function to check for new orders
function checkNewOrders() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_new_orders.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.newOrder) {
                // Show the new order notification
                showNotification(response.notificationMessage);

                // Play the notification sound if available
                if (response.sound) {
                    var audio = new Audio(response.sound);
                    audio.play();
                }

                // You can add a logic to dynamically update the dashboard with the new order details
               // alert("New Order Received: " + response.order_name);
            }
        }
    };
    xhr.send();
}

// Function to show a notification on the screen
function showNotification(message) {
    var notification = document.createElement('div');
    notification.classList.add('alert', 'alert-info');
    notification.innerText = message;
    document.body.appendChild(notification);

    // Slide in the notification
    notification.classList.add('slide-in');

    // Remove the notification after a few seconds
    setTimeout(function() {
        notification.classList.add('slide-out');
    }, 3000);

    setTimeout(function() {
        notification.remove();
    }, 3500);
}

// Check for new orders every 5 seconds
setInterval(checkNewOrders, 5000);








            // Auto-refresh the page every 30 seconds
            setInterval(function() {
                location.reload(); // This will reload the page
            }, 30000);

        </script>

<!-- CSS for Notification Message -->
<style>
/* Styling for the notification message */
.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    z-index: 9999;
}

.alert.slide-in {
    animation: slideIn 0.5s forwards;
}

.alert.slide-out {
    animation: slideOut 0.5s forwards;
}

@keyframes slideIn {
    from {
        right: -100%;
        opacity: 0;
    }
    to {
        right: 20px;
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        right: 20px;
        opacity: 1;
    }
    to {
        right: -100%;
        opacity: 0;
    }
}

</style>





        <!-- Optional CSS for the button -->
        <style>
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #6a5acd;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .btn:hover {
                background-color: #483d8b;
            }

            .btn-secondary {
                background-color: #6a5acd;
            }

            .notification {
                position: fixed;
                top: -50px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #28a745;
                color: white;
                padding: 10px;
                border-radius: 5px;
                font-size: 16px;
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 9999;
            }
        </style>

        <br />





        <!-- Display the total statistics (Total Orders, Delivered Orders, Total Earnings) -->
        <div class="order-summary" id="order-summary">
            <h3>Today's Order Summary</h3>
            <?php
                // Get Today's Date
                $today = date('Y-m-d'); // This will give you today's date in 'YYYY-MM-DD' format

                // Total Orders for Today
                $total_orders_sql = "SELECT COUNT(*) AS total_orders FROM tbl_order WHERE DATE(created_at) = CURDATE()";
                $total_orders_res = mysqli_query($conn, $total_orders_sql);
                $total_orders_row = mysqli_fetch_assoc($total_orders_res);
                $total_orders = $total_orders_row['total_orders'];

                // Delivered Orders for Today
                $delivered_orders_sql = "SELECT COUNT(*) AS delivered_orders FROM tbl_order WHERE status='Delivered' AND DATE(created_at) = CURDATE()";
                $delivered_orders_res = mysqli_query($conn, $delivered_orders_sql);
                $delivered_orders_row = mysqli_fetch_assoc($delivered_orders_res);
                $delivered_orders = $delivered_orders_row['delivered_orders'];

                // Total Earnings for Delivered Orders Today
                $total_earnings_sql = "SELECT SUM(total) AS total_earnings FROM tbl_order WHERE status='Delivered' AND DATE(created_at) = CURDATE()";
                $total_earnings_res = mysqli_query($conn, $total_earnings_sql);
                $total_earnings_row = mysqli_fetch_assoc($total_earnings_res);
                $total_earnings = $total_earnings_row['total_earnings'];

                // Display the statistics
                echo "<p><strong>Total Orders Today:</strong> $total_orders</p>";
                echo "<p><strong>Delivered Orders Today:</strong> $delivered_orders</p>";
                echo "<p><strong>Total Earnings Today:</strong> TK $total_earnings</p>";
            ?>

            <!-- Button for history page -->
             <br>
            <a href="view-all-order.php" class="btn btn-primary">View All Order </a>
            
        </div>
<!-- Date Filter to Show Orders and Earnings 
<form method="GET" action="" style="margin-bottom: 20px;">
            <label for="date">Filter by Date:</label>
            <input type="date" name="date" id="date" required>
            <input type="submit" value="Filter" class="btn-primary">
            <button type="button" id="clear-filter" class="btn-secondary">Clear Filter</button> 
        </form>
        -->
        <?php
            // Check if the user is filtering by date
            if (isset($_GET['date'])) {
                $date = $_GET['date'];

                // Get earnings for the selected date (only delivered orders)
                $earnings_date_sql = "SELECT SUM(total) AS total_earnings FROM tbl_order WHERE DATE(created_at) = '$date' AND status='Delivered'";
                $earnings_date_res = mysqli_query($conn, $earnings_date_sql);
                $earnings_date_row = mysqli_fetch_assoc($earnings_date_res);
                $earnings_date = $earnings_date_row['total_earnings'];

                echo "<h3>Total Earnings on $date: TK $earnings_date</h3>";
            }
        ?>
        <br /><br />







        
        <table class="tbl-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Date</th>
                    <th>Food</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Delivery Option</th>
                    <th>Total Price</th>
                    <th>Order</th>
                </tr>
            </thead>

            <tbody>
            <?php 
                                // Fetch today's orders (or filtered date orders)
                if (isset($_GET['date'])) {
                    $date = $_GET['date'];
                    $sql = "SELECT * FROM tbl_order WHERE DATE(created_at) = '$date' ORDER BY created_at DESC"; // Filter by date
                } else {
                    // Fetch only today's orders
                    $sql = "SELECT * FROM tbl_order WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC"; // Display today's orders
                }


                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);

                $sn = 1;

                if($count > 0) {
                    // Orders are available
                    while($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $total = $row['total'];
                        $order_date = $row['created_at'];
                        $status = $row['status'];
                        $customer_name = $row['name'];
                        $customer_contact = $row['phone'];
                        $customer_address = $row['address'];
                        $delivery_option = $row['delivery_option'];  
                        $delivery_charge = $row['delivery_charge']; 

                        // Get food details
                        $food_sql = "SELECT * FROM tbl_order_items WHERE order_id='$id'";
                        $food_res = mysqli_query($conn, $food_sql);
                        $food_data = [];
                        while ($food_row = mysqli_fetch_assoc($food_res)) {
                            $food_id = $food_row['food_id'];
                            $quantity = $food_row['quantity'];
                            $food_name_sql = "SELECT title, price FROM tbl_food WHERE id='$food_id'";
                            $food_name_res = mysqli_query($conn, $food_name_sql);
                            $food_name_row = mysqli_fetch_assoc($food_name_res);
                            $food_data[] = [
                                'food_name' => $food_name_row['title'],
                                'price' => $food_name_row['price'],
                                'quantity' => $quantity,
                                'item_total' => $quantity * $food_name_row['price'] // Calculate item total price
                            ];
                        }

                        // Prepare food list and quantities
                        $food_list = '';
                        foreach ($food_data as $item) {
                            $food_list .= $item['food_name'] . " (Qty: " . $item['quantity'] . "), ";
                        }
                        $food_list = rtrim($food_list, ', ');  

                        // Calculate the total price after adding delivery charge for the final total
                        $total_price = $total; // Final total price is fetched from the database directly

                        ?>

                        <tr>
                            <td><?php echo $sn++; ?> </td>
                            <td><?php echo $order_date; ?></td>
                            <td><?php echo $food_list; ?></td>

                            <!-- Price Column showing Quantity x Item Price = Item Total -->
                            <td>
                                <?php
                                foreach ($food_data as $item) {
                                    $item_total = $item['item_total'];
                                    echo $item['quantity'] . " x " . $item['price'] . " = " . $item_total . "<br>";
                                }
                                ?>
                            </td>

                            <!-- Quantity -->
                            <td>
                                <?php 
                                    $qty_sql = "SELECT SUM(quantity) as qty FROM tbl_order_items WHERE order_id='$id'";
                                    $qty_res = mysqli_query($conn, $qty_sql);
                                    $qty_row = mysqli_fetch_assoc($qty_res);
                                    echo $qty_row['qty'];
                                ?>
                            </td>

                           <td>
                                <?php 
                                    if ($status == "Accepted") {
                                        echo "<label class='status accepted'>$status</label>";
                                    } elseif ($status == "Wait For Confirmation") {
                                        echo "<label class='status confirm'>$status</label>";
                                    }
                                     elseif ($status == "Processing") {
                                        echo "<label class='status processing'>$status</label>";
                                    }
                                     elseif ($status == "Ready For Delivery") {
                                        echo "<label class='status ready'>$status</label>";
                                    } elseif ($status == "Delivered") {
                                        echo "<label class='status delivered'>$status</label>";
                                    } elseif ($status == "Cancelled") {
                                        echo "<label class='status cancelled'>$status</label>";
                                    }
                                ?>
                            </td>

                            <td><?php echo $customer_name; ?></td>
                            <td><?php echo $customer_contact; ?></td>
                            <td><?php echo $customer_address; ?></td>
                            <td><?php echo $delivery_option; ?></td>
                            <td><?php echo 'TK' . $total_price; ?></td> <!-- Final Total Price (Including Delivery Charge) -->

                            <td>
                                <a href="<?php echo SITEURL; ?>admin/update-order.php?id=<?php echo $id; ?>" class="btn-secondary">Status_Up</a>
                                <button onclick="printOrder('<?php echo $id; ?>')" class="btn-primary">Print</button>
                            </td>
                        </tr>

                    <?php
                    }
                } else {
                    echo "<tr><td colspan='13' class='error'>No Orders Available</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>

<script>
    // Function to print the order details
    function printOrder(orderId) {
        window.open('print_order_thermal.php?id=' + orderId, '_blank', 'width=600,height=600');
    }

// Clear the filter and reload the page
document.getElementById('clear-filter').addEventListener('click', function() {
        document.getElementById('date').value = ''; // Reset the date field
        window.location.href = 'manage-order.php'; // Reload the page without filter
    });



</script>

<style>
    /* Overall page layout */
    .wrapper {
        width: 95%;
        margin: 0 auto;
    }

    .heading {
        font-size: 32px;
        text-align: center;
        color: #4CAF50;
        margin-bottom: 30px;
    }

    .tbl-full {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
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
    .btn-primary {
        background-color:rgb(107, 207, 160);
        color: white;
        padding: 10px 20px;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }
    .btn-secondary {
        background-color:rgb(119, 76, 175);
        color: white;
        padding: 10px 20px;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }
    .btn-secondary:hover {
        background-color:rgb(140, 69, 160);
    }
    .status {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .status.confirm {
        background-color:rgb(76, 168, 175);
        color: white;
    }
    .status.accepted {
        background-color:rgb(151, 219, 153);
        color: white;
    }

    .status.processing {
        background-color:rgb(81, 137, 189);
        color: white;
    }
    .status.ready {
        background-color:rgb(238, 191, 104);
        color: white;
    }

    .status.delivered {
        background-color: #28a745;
        color: white;
    }

    .status.cancelled {
        background-color: #dc3545;
        color: white;
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







</style>



