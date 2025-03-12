<link rel="stylesheet" href="../css/admin.css">
<?php 

include('../config/constants.php'); 
// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $quantity = $_POST['quantity'];
    $sql_food = "SELECT * FROM tbl_food WHERE id='$food_id'";
    $res_food = mysqli_query($conn, $sql_food);
    $food = mysqli_fetch_assoc($res_food);

    $item = [
        'id' => $food['id'],
        'name' => $food['title'],
        'price' => $food['price'],
        'quantity' => $quantity
    ];

    // Check if item already exists in the cart, and update quantity
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $food['id']) {
            $cart_item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    // If item is not found, add it to the cart
    if (!$found) {
        $_SESSION['cart'][] = $item;
    }
    header("Location: create-order.php");
    exit();
}

// Handle Remove Item from Cart
if (isset($_GET['remove_item'])) {
    $index = $_GET['remove_item'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
    header("Location: create-order.php");
    exit();
}
?>

<div class="main-content">
<h1 class="heading">Create Order</h1>
    <div class="wrapper">

        <!-- Left Section: Food Items -->
        <div class="food-section">
            <h3>Available Food Items</h3>
            <!-- Search Bar -->
            <input type="text" id="food-search" placeholder="Search Food Items..." class="search-bar">
            <br><br>
            <table class="tbl-full" id="food-table">
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $sql = "SELECT * FROM tbl_food WHERE active='Yes'";
                        $res = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($res)) {
                            $food_id = $row['id'];
                            $food_name = $row['title'];
                            $food_price = $row['price'];

                            echo "<tr>
                                <td class='food-name'>{$food_name}</td>
                                <td>TK {$food_price}</td>
                                <td>
                                    <form action='create-order.php' method='POST'>
                                        <input type='hidden' name='food_id' value='{$food_id}'>
                                        <input type='number' name='quantity' value='1' min='1' required>
                                        <input type='submit' name='add_to_cart' value='Add to Cart' class='btn-primary'>
                                    </form>
                                </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Right Section: Cart -->
        <div class="cart-section">
            <h3>Your Cart</h3>
            <table id="cart-table">
                <thead>
                    <tr>
                        <th>Food</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
                        foreach ($_SESSION['cart'] as $index => $item) {
                            $item_total = $item['price'] * $item['quantity'];
                            echo "<tr>
                                <td>{$item['name']}</td>
                                <td>{$item['quantity']}</td>
                                <td>TK {$item['price']}</td>
                                <td>TK {$item_total}</td>
                                <td><a href='?remove_item={$index}' class='btn-danger'>Remove</a></td>
                            </tr>";
                            $total += $item_total;
                        }
                    }
                    ?>
                </tbody>
            </table>

            <h3>Total: TK <span id="total-price"><?php echo $total; ?></span></h3>

            <!-- Form to enter customer details -->
            <form action="place-order.php" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" name="name" required><br><br>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" required><br><br>

                <label for="address">Address:</label>
                <textarea name="address" required></textarea><br><br>

                <input type="submit" name="place_order" value="Place Order" class="btn-primary">
            </form>

            <?php
                if (isset($error_message)) {
                    echo "<div class='error'>{$error_message}</div>";
                }
            ?>
        </div>

    </div>

    <a href="order-management.php" class="btn btn-primary">Back to Manage Orders</a>
</div>

<?php include('partials/footer.php'); ?>

<script>
    // On-keyup search functionality for food items
    document.getElementById('food-search').addEventListener('keyup', function() {
        var filter = this.value.toLowerCase();
        var rows = document.getElementById('food-table').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        for (var i = 0; i < rows.length; i++) {
            var foodName = rows[i].querySelector('.food-name').textContent.toLowerCase();
            if (foodName.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
</script>

<style>
    /* Overall Layout */
    .wrapper {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
    }

    /* Food Items Section */
    .food-section {
        width: 60%;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .food-section h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Search Bar Styling */
    .search-bar {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    /* Table Styling */
    .tbl-full {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tbl-full th, .tbl-full td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .tbl-full th {
        background-color: #4CAF50;
        color: white;
    }

    /* Cart Section */
    .cart-section {
        width: 35%;
        padding: 20px;
        background-color: #f4f4f4;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .cart-section h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Buttons */
    .btn-primary {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #218838;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 8px 16px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    /* Slider Message */
    .slider-message {
        position: fixed;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        font-size: 16px;
        border-radius: 10px;
        z-index: 9999;
        opacity: 0.9;
        transition: all 0.5s ease;
    }
</style>
