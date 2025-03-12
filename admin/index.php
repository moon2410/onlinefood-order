<?php include('partials/menu.php'); ?>

<!-- Main Content Section Starts -->
<div class="main-content">
    <div class="wrapper">
<!--        <h1>Administrator Dashboard</h1>
        <br><br>-->
        <?php 
            if(isset($_SESSION['login'])) {
                echo $_SESSION['login'];
                unset($_SESSION['login']);
            }
        ?>
        <br><br>

        <!-- Order Statistics Section -->
        <div class="order-stats">
            <div class="col-4 text-center" id="admin-count">
                <?php 
                    $sql9 = "SELECT * FROM tbl_admin";
                    $res9 = mysqli_query($conn, $sql9);
                    $count9 = mysqli_num_rows($res9);
                ?>
                <h1><?php echo $count9; ?></h1>
                <br />
                System Administrator
            </div>

            <div class="col-4 text-center" id="total-users">
                <?php 
                    $sql10 = "SELECT * FROM tbl_user";
                    $res10 = mysqli_query($conn, $sql10);
                    $count10 = mysqli_num_rows($res10);
                ?>
                <h1><?php echo $count10; ?></h1>
                <br />
                Total Users
            </div>

            <div class="col-4 text-center" id="food-categories">
                <?php 
                    $sql = "SELECT * FROM tbl_category";
                    $res = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($res);
                ?>
                <h1><?php echo $count; ?></h1>
                <br />
                Food Categories
            </div>



            <div class="clearfix"></div>
        </div>

        <!-- Order Status Sections -->
        <div class="order-status">
        <div class="col-4 text-center" id="total-foods">
                <?php 
                    $sql2 = "SELECT * FROM tbl_food";
                    $res2 = mysqli_query($conn, $sql2);
                    $count2 = mysqli_num_rows($res2);
                ?>
                <h1><?php echo $count2; ?></h1>
                <br />
                Total Foods
            </div>
            <div class="col-4 text-center" id="total-orders">
                <?php 
                    $sql3 = "SELECT * FROM tbl_order";
                    $res3 = mysqli_query($conn, $sql3);
                    $count3 = mysqli_num_rows($res3);
                ?>
                <h1><?php echo $count3; ?></h1>
                <br />
                Total Order Received
            </div>

            <!-- Wait For Confirmation Section -->
            <div class="col-4 text-center" id="wait-for-confirmation" style="background-color: #f44336;">
                <?php 
                    $sql6 = "SELECT * FROM tbl_order WHERE status = 'Wait For Confirmation'";
                    $res6 = mysqli_query($conn, $sql6);
                    $count6 = mysqli_num_rows($res6);
                ?>
                <h1><?php echo $count6; ?></h1>
                <br />
                Wait For Confirmation Orders 
            </div>

            <div class="col-4 text-center" id="accepted-orders">
                <?php 
                    $sql7 = "SELECT * FROM tbl_order WHERE status = 'Accepted'";
                    $res7 = mysqli_query($conn, $sql7);
                    $count7 = mysqli_num_rows($res7);
                ?>
                <h1><?php echo $count7; ?></h1>
                <br />
                Accepted Orders
            </div>

            <div class="col-4 text-center" id="processing-orders">
                <?php 
                    $sql8 = "SELECT * FROM tbl_order WHERE status = 'Processing'";
                    $res8 = mysqli_query($conn, $sql8);
                    $count8 = mysqli_num_rows($res8);
                ?>
                <h1><?php echo $count8; ?></h1>
                <br />
                Processing Orders
            </div>

            <div class="clearfix">
        </div>

        <!-- New Sections -->
        <div class="col-4 text-center" id="ready-for-delivery">
            <?php 
                $sql12 = "SELECT * FROM tbl_order WHERE status = 'Ready For Delivery'";
                $res12 = mysqli_query($conn, $sql12);
                $count12 = mysqli_num_rows($res12);
            ?>
            <h1><?php echo $count12; ?></h1>
            <br />
            Ready For Delivery Orders
        </div>

        <div class="col-4 text-center" id="total-home-delivery">
            <?php 
                $sql13 = "SELECT * FROM tbl_order WHERE delivery_option = 'delivery'";
                $res13 = mysqli_query($conn, $sql13);
                $count13 = mysqli_num_rows($res13);
            ?>
            <h1><?php echo $count13; ?></h1>
            <br />
            Total Home Delivery Orders
        </div>

        <div class="col-4 text-center" id="total-pickup">
            <?php 
                $sql14 = "SELECT * FROM tbl_order WHERE delivery_option = 'pickup'";
                $res14 = mysqli_query($conn, $sql14);
                $count14 = mysqli_num_rows($res14);
            ?>
            <h1><?php echo $count14; ?></h1>
            <br />
            Total Pickup Orders
        </div>

        <div class="col-4 text-center" id="total-revenue">
            <?php 
                $sql15 = "SELECT SUM(total) AS total_revenue FROM tbl_order WHERE status = 'Delivered'";
                $res15 = mysqli_query($conn, $sql15);
                $row15 = mysqli_fetch_assoc($res15);
                $total_revenue = $row15['total_revenue'];
            ?>
            <h1>$<?php echo $total_revenue; ?></h1>
            <br />
            Total Revenue (Delivered)
        </div>

   <!--     <div class="col-4 text-center" id="total-revenue-without-delivery">
            <?php 
                $sql16 = "SELECT SUM(total) - SUM(delivery_charge) AS total_revenue_without_delivery FROM tbl_order WHERE status = 'Delivered'";
                $res16 = mysqli_query($conn, $sql16);
                $row16 = mysqli_fetch_assoc($res16);
                $total_revenue_without_delivery = $row16['total_revenue_without_delivery'];
            ?>
            <h1>$<?php echo $total_revenue_without_delivery; ?></h1>
            <br />
            Total Revenue Without Delivery Charge
        </div>
        -->
        <div class="col-4 text-center" id="cancelled-orders">
            <?php 
                $sql17 = "SELECT * FROM tbl_order WHERE status = 'Cancelled'";
                $res17 = mysqli_query($conn, $sql17);
                $count17 = mysqli_num_rows($res17);
            ?>
            <h1><?php echo $count17; ?></h1>
            <br />
            Cancelled Orders
        </div>

        <div class="col-4 text-center" id="completed-orders">
            <?php 
                $sql18 = "SELECT * FROM tbl_order WHERE status = 'Delivered'";
                $res18 = mysqli_query($conn, $sql18);
                $count18 = mysqli_num_rows($res18);
            ?>
            <h1><?php echo $count18; ?></h1>
            <br />
            Completed Orders
        </div>

        <div class="clearfix"></div>

    </div>
</div>
<!-- Main Content Section Ends -->

<?php include('partials/footer.php'); ?>

<!-- JS to handle Dynamic Updates and Notification -->
<script>
    // Function to update the order status dynamically
    function updateOrderStats() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'update_order_status.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);

                // Update order counts in the Dashboard dynamically
                document.getElementById('wait-for-confirmation').children[0].innerText = response.waitForConfirmation;
                document.getElementById('accepted-orders').children[0].innerText = response.acceptedOrders;
                document.getElementById('processing-orders').children[0].innerText = response.processingOrders;
                document.getElementById('total-orders').children[0].innerText = response.totalOrders;
                document.getElementById('completed-orders').children[0].innerText = response.completedOrders;
                document.getElementById('total-users').children[0].innerText = response.totalUsers;
                document.getElementById('total-foods').children[0].innerText = response.totalFoods;
                document.getElementById('food-categories').children[0].innerText = response.foodCategories;
                document.getElementById('ready-for-delivery').children[0].innerText = response.readyForDelivery;
                document.getElementById('total-home-delivery').children[0].innerText = response.totalHomeDeliveryOrders;
                document.getElementById('total-pickup').children[0].innerText = response.totalPickupOrders;
                document.getElementById('total-revenue').children[0].innerText = response.totalRevenue;
                document.getElementById('total-revenue-without-delivery').children[0].innerText = response.totalRevenueWithoutDeliveryCharge;
                document.getElementById('cancelled-orders').children[0].innerText = response.cancelledOrders;
            }
        };
        xhr.send();
    }

    // Trigger Update every 5 seconds
    setInterval(updateOrderStats, 5000);




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


<style>
            /* Overall Body and Layout */
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 0;
            }

            .wrapper {
                width: 90%;
                margin: 20px auto;
            }

            /* Header Style */
            h1 {
                text-align: center;
                font-size: 36px;
                color: #333;
                margin-bottom: 30px;
                font-weight: 700;
            }

            /* Order Statistics Section */
            .order-stats {
                display: flex;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 40px;
            }

            .col-4 {
                background-color: #fff;
                padding: 30px;
                text-align: center;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                flex-basis: 22%;
                margin: 10px;
            }

            .col-4:hover {
                transform: scale(1.05);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            }

            .col-4 h1 {
                font-size: 40px;
                color: #007bff;
                margin-bottom: 10px;
            }

            .col-4:hover h1 {
                color: #28a745;
            }

            /* Order Status Section */
            .order-status {
                display: flex;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 40px;
            }

            .order-status .col-4 {
                background-color: #fff;
                padding: 30px;
                text-align: center;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                flex-basis: 22%;
                margin: 10px;
            }

            /* Order Status Colors */
            #admin-count { background-color:rgb(54, 124, 244); }
            #total-users { background-color:rgb(78, 166, 175); }
            #total-foods { background-color:rgb(78, 166, 175); }
            #food-categories { background-color:rgb(78, 166, 175); }
            #total-orders { background-color:rgb(195, 138, 179); }
            #total-revenue { background-color:rgb(148, 231, 138); }
            
            #wait-for-confirmation { background-color: #f44336; }
            #accepted-orders { background-color: #007bff; }
            #processing-orders { background-color: #ffc107; }
            #ready-for-delivery { background-color:rgb(185, 229, 160); }
            #total-home-delivery { background-color: #dc3545; }
            #total-pickup { background-color: #17a2b8; }
            #cancelled-orders { background-color: #dc3545; }
            #completed-orders { background-color:rgb(171, 252, 190); }

            .col-4 h1 {
                font-size: 40px;
                margin-bottom: 10px;
                color: white;
                font-weight: bold;
            }

            /* Notification Pop-up */
            .alert {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #4CAF50;
                color: white;
                padding: 15px 25px;
                border-radius: 8px;
                font-size: 16px;
                z-index: 9999;
                animation: slideIn 0.5s ease-in-out;
            }

            .alert-slide-out {
                animation: slideOut 0.5s ease-in-out;
            }

            @keyframes slideIn {
                from { right: -300px; opacity: 0; }
                to { right: 20px; opacity: 1; }
            }

            @keyframes slideOut {
                from { right: 20px; opacity: 1; }
                to { right: -300px; opacity: 0; }
            }

            /* Buttons */
            .btn-primary {
                background-color: #28a745;
                color: white;
                padding: 10px 20px;
                font-size: 16px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .btn-primary:hover {
                background-color: #218838;
            }

            .btn-secondary {
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                font-size: 16px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .btn-secondary:hover {
                background-color: #0056b3;
            }

            /* Mobile Responsiveness */
            @media (max-width: 768px) {
                .order-stats, .order-status {
                    flex-direction: column;
                    align-items: center;
                }

                .col-4 {
                    width: 80%;
                    margin-bottom: 20px;
                }
            }

        </style>