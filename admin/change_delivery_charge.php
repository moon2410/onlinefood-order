<?php
// Start session
session_start();

// Include database connection
include('../config/constants.php');

// Check if the order id is passed (if needed, but here we're updating the global delivery charge)
$sql = "SELECT * FROM tbl_delivery_charge WHERE id=1";
$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $current_charge = $row['charge'];
} else {
    $current_charge = 0;
}

// Handle form submission
if(isset($_POST['update_delivery'])) {
    $new_charge = mysqli_real_escape_string($conn, $_POST['new_charge']);
    $sql_update = "UPDATE tbl_delivery_charge SET charge='$new_charge' WHERE id=1";
    $res_update = mysqli_query($conn, $sql_update);
    if($res_update) {
        $_SESSION['update'] = "Delivery charge updated successfully!";
        header('location: manage-order.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to update delivery charge.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Delivery Charge</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .popup-container {
            width: 350px;
            margin: 100px auto;
            background: #fff;
            padding: 20px 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        .popup-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        .popup-container input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .popup-container .btn {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .popup-container .btn:hover {
            background: #45a049;
        }
        .popup-container .error {
            background: #ffdddd;
            color: #d8000c;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="popup-container">
    <h2>Change Delivery Charge</h2>
    <?php 
    if(isset($_SESSION['error'])) {
        echo "<div class='error'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
    ?>
    <form action="" method="POST">
        <label>Current Delivery Charge: TK <?php echo $current_charge; ?></label>
        <br><br>
        <input type="number" name="new_charge" value="<?php echo $current_charge; ?>" required min="0" step="0.01">
        <input type="submit" name="update_delivery" value="Update Charge" class="btn">
    </form>
</div>
</body>
</html>
