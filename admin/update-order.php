<?php
// Ensure no output is sent before this point
include('../config/constants.php');

// Check if the id is passed in the URL
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Get the order details from the database
    $sql = "SELECT * FROM tbl_order WHERE id='$order_id' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($res == TRUE && mysqli_num_rows($res) > 0) {
        // Get the order details
        $order = mysqli_fetch_assoc($res);
        $status = $order['status'];
        $total = $order['total'];
        $customer_name = $order['name'];
        $customer_contact = $order['phone'];
        $customer_address = $order['address'];
        $order_date = $order['created_at'];
    } else {
        $_SESSION['error'] = "Order not found.";
        header('location: manage-order.php');
        exit();  // Ensure the script stops here
    }
} else {
    $_SESSION['error'] = "No order ID provided.";
    header('location: manage-order.php');
    exit();  // Ensure the script stops here
}

// Handle form submission (update order status)
if (isset($_POST['update_order'])) {
    // Get the new status from the form
    $new_status = $_POST['status'];

    // Update the status in the database
    $sql2 = "UPDATE tbl_order SET status='$new_status' WHERE id='$order_id'";
    $res2 = mysqli_query($conn, $sql2);

    if ($res2 == TRUE) {
        $_SESSION['update'] = "Order status updated successfully.";
        header('location: manage-order.php');
        exit();  // Ensure the script stops here
    } else {
        $_SESSION['error'] = "Failed to update the order status.";
        // No output before header call
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
</head>
<body>
    <div class="main-content">
        <div class="wrapper">
            <h1>Update Order</h1>

            <?php 
                // Display session messages
                if (isset($_SESSION['error'])) {
                    echo "<div class='error'>{$_SESSION['error']}</div>";
                    unset($_SESSION['error']);
                }
            ?>

            <form action="update-order.php?id=<?php echo $order_id; ?>" method="POST">
                <table class="tbl-full">
                    <tr>
                        <td><strong>Order Date:</strong></td>
                        <td><?php echo $order_date; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Customer Name:</strong></td>
                        <td><input type="text" name="name" value="<?php echo $customer_name; ?>" required></td>
                    </tr>
                    <tr>
                        <td><strong>Contact:</strong></td>
                        <td><input type="text" name="phone" value="<?php echo $customer_contact; ?>" required></td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td><textarea name="address" rows="4" required><?php echo $customer_address; ?></textarea></td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td>
                        <td><?php echo 'TK' . $total; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Order Status:</strong></td>
                        <td>
                            <select name="status" required>
                                <option value="Wait For Confirmation" <?php if ($status == "Wait For Confirmation") echo "selected"; ?>>Wait For Confirmation</option>
                                <option value="Accepted" <?php if ($status == "Accepted") echo "selected"; ?>>Accepted</option>
                                <option value="Processing" <?php if ($status == "Processing") echo "selected"; ?>>Processing</option>
                                <option value="Ready For Delivery" <?php if ($status == "Ready For Delivery") echo "selected"; ?>>Ready For Delivery</option>
                                <option value="Delivered" <?php if ($status == "Delivered") echo "selected"; ?>>Delivered</option>
                                <option value="Cancelled" <?php if ($status == "Cancelled") echo "selected"; ?>>Cancelled</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <input type="submit" name="update_order" value="Update Order" class="btn-primary">
            </form>
            <br>
            <a href="manage-order.php" class="btn btn-primary">Back to Manage Orders</a>
        </div>
    </div>
</body>
</html>

<?php include('partials/footer.php'); ?>

<!-- Custom Styles and Effects -->
<style>
    .main-content {
        padding: 30px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 50px;
    }

    .wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    .heading {
        font-size: 32px;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }

    .tbl-full {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tbl-full td {
        padding: 15px;
        text-align: left;
    }

    .tbl-full td input, .tbl-full td select, .tbl-full td textarea {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .tbl-full td input:focus, .tbl-full td select:focus, .tbl-full td textarea:focus {
        border-color: #4CAF50;
    }

    .btn-primary {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        width: 100%;
        margin-top: 20px;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }

    .error {
        background-color: #ffdddd;
        color: #d8000c;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
    }

    textarea {
        resize: none;
    }

    select {
        padding: 10px;
        font-size: 14px;
    }
</style>

<script>
    // Add JS effects for form fields
    document.querySelectorAll('input, select, textarea').forEach(function(input) {
        input.addEventListener('focus', function() {
            input.style.borderColor = '#4CAF50';
        });
        input.addEventListener('blur', function() {
            input.style.borderColor = '#ccc';
        });
    });
</script>
