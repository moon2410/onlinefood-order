<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('partials-front/header.php');

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch the current delivery charge from the database
$sql_dc = "SELECT charge FROM tbl_delivery_charge WHERE id = 1";
$res_dc = mysqli_query($conn, $sql_dc);
if ($res_dc && mysqli_num_rows($res_dc) > 0) {
    $row_dc = mysqli_fetch_assoc($res_dc);
    $delivery_charge = $row_dc['charge'];
} else {
    $delivery_charge = 5.00;
}

// Calculate the total price from the cart
$order_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $order_total += $item['price'] * $item['quantity'];
}

// If 'Home Delivery' is selected, add the delivery charge
if (isset($_POST['delivery_option']) && $_POST['delivery_option'] == 'delivery') {
    $order_total_with_delivery = $order_total + $delivery_charge;
} else {
    $order_total_with_delivery = $order_total;
}

// Handle form submission (checkout)
if (isset($_POST['place_order'])) {
    // Get order details from POST data
    $user_id = $_SESSION['user']['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $account_holder = $_SESSION['user']['name'];
    $delivery_option = $_POST['delivery_option'];

    // Insert the order into tbl_order
    $sql = "INSERT INTO tbl_order (user_id, total, address, status, name, phone, account_holder, delivery_charge, delivery_option) 
            VALUES ('$user_id', '$order_total_with_delivery', '$address', 'Wait For Confirmation', '$name', '$phone', '$account_holder', '$delivery_charge', '$delivery_option')";
    $res = mysqli_query($conn, $sql);

    if ($res == TRUE) {
        $order_id = mysqli_insert_id($conn);

        // Insert order items into tbl_order_items
        foreach ($_SESSION['cart'] as $item) {
            $food_id = $item['food_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $sql2 = "INSERT INTO tbl_order_items (order_id, food_id, quantity, price) 
                     VALUES ('$order_id', '$food_id', '$quantity', '$price')";
            mysqli_query($conn, $sql2);
        }

        // Clear the cart after placing the order
        unset($_SESSION['cart']);

        $_SESSION['order'] = "Order placed successfully! You will be notified soon.";
        echo "<script>
                alert('Order placed successfully!');
                window.location.href = 'myorders.php';
              </script>";
        exit();
    } else {
        $_SESSION['error'] = "Failed to place the order. Please try again.";
        header('location: checkout.php');
    }
}
?>

<!-- Checkout Page Content -->
<div class="main-content">
    <div class="wrapper">
        <h1>Checkout</h1>

        <?php
        // Display session error messages if any
        if (isset($_SESSION['error'])) {
            echo "<div class='error'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }

        // Display cart items and total price
        if (count($_SESSION['cart']) > 0) {
            echo "<form action='checkout.php' method='POST' id='checkout-form'>";
            echo "<table class='tbl-full'>";
            echo "<tr>
                    <th>Food Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                  </tr>";

            $total_price = 0;
            foreach ($_SESSION['cart'] as $item) {
                $price = floatval($item['price']);
                $quantity = intval($item['quantity']);
                $item_total = $price * $quantity;
                $total_price += $item_total;

                echo "<tr>
                        <td>{$item['title']}</td>
                        <td>TK {$price}</td>
                        <td>{$quantity}</td>
                        <td>TK {$item_total}</td>
                      </tr>";
            }

            echo "</table>";
            echo "<h3>Total: TK <span id='total-price'>{$total_price}</span></h3>";
        } else {
            echo "<p>Your cart is empty. <a href='category-foods.php'>Continue shopping</a>.</p>";
        }
        ?>

        <!-- Shipping Information Form -->
        <form action="checkout.php" method="POST" id="checkout-form">
            <label for="name">Full Name:</label>
            <input type="text" name="name" value="<?php echo $_SESSION['user']['name']; ?>" required><br><br>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo $_SESSION['user']['phone']; ?>" required><br><br>

            <label for="account_holder">Account Holder Name:</label>
            <input type="text" name="account_holder" value="<?php echo $_SESSION['user']['name']; ?>" readonly><br><br>

            <!-- Radio buttons for Delivery or Pickup -->
            <label for="delivery_option">Select Delivery Option:</label><br>
            <input type="radio" name="delivery_option" value="delivery" id="delivery"> Home Delivery (TK <?php echo $delivery_charge; ?>)<br>
            <input type="radio" name="delivery_option" value="pickup" id="pickup" checked> Restaurant Pickup (No charge)<br><br>

            <!-- Address field is hidden by default and only shows when Delivery is selected -->
            <div id="address-field" style="display: none;">
                <label for="address">Enter Shipping Address:</label>
                <textarea name="address" rows="4" required></textarea><br><br>
            </div>

            <h3>Total Price: TK <span id="final-total"><?php echo $total_price; ?></span></h3>

            <input type="submit" name="place_order" value="Place Order" class="btn-primary">
        </form>
    </div>
</div>

<script>
    // Toggle address field and update final total based on delivery option
    const deliveryOptions = document.querySelectorAll('input[name="delivery_option"]');
    const finalTotal = document.getElementById('final-total');
    const totalPrice = <?php echo $total_price; ?>;
    const addressField = document.getElementById('address-field');

    deliveryOptions.forEach(option => {
        option.addEventListener('change', function() {
            let deliveryCharge = 0;
            if (this.value === 'delivery') {
                deliveryCharge = <?php echo $delivery_charge; ?>;
                addressField.style.display = 'block';
            } else {
                addressField.style.display = 'none';
                document.querySelector('textarea[name="address"]').value = "Pickup";
            }
            const newTotal = totalPrice + deliveryCharge;
            finalTotal.innerText = newTotal.toFixed(2);
        });
    });

    // Set default value for address field on page load if Pickup is selected
    if (document.querySelector('input[name="delivery_option"]:checked').value === 'pickup') {
        document.querySelector('textarea[name="address"]').value = "Pickup";
    }

    // Add validation for the phone number on form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        var phone = document.querySelector('input[name="phone"]').value.trim();
        // Validate phone number to be exactly 11 digits (adjust regex as needed)
        if (!/^\d{11}$/.test(phone)) {
            alert("Please enter a valid 11-digit phone number.");
            e.preventDefault();
        }
    });
</script>

<?php include('partials-front/footer.php'); ?>
