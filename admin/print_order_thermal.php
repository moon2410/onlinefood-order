<?php

include('../config/constants.php');

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Fetch order details
    $sql = "SELECT * FROM tbl_order WHERE id='$order_id'";
    $res = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($res);

    // Fetch items for the order
    $sql_items = "SELECT * FROM tbl_order_items WHERE order_id='$order_id'";
    $res_items = mysqli_query($conn, $sql_items);
    $items = [];
    while ($row = mysqli_fetch_assoc($res_items)) {
        $food_id = $row['food_id'];
        $quantity = $row['quantity'];
        $food_name_sql = "SELECT title FROM tbl_food WHERE id='$food_id'";
        $food_name_res = mysqli_query($conn, $food_name_sql);
        $food_name_row = mysqli_fetch_assoc($food_name_res);
        $items[] = [
            'food_name' => $food_name_row['title'],
            'quantity' => $quantity,
            'price' => $row['price'],
            'total' => $quantity * $row['price']
        ];
    }

    // Calculate total price already stored in order (which should include delivery charge if applicable)
    $total_price = $order['total'];
    if ($order['delivery_option'] == 'delivery') {
        $delivery_charge = $order['delivery_charge'];
    } else {
        $delivery_charge = 0;
    }

    // Generate token number (e.g., based on order date and order ID)
    $order_date = new DateTime($order['created_at']);
    $token_number = 'ORD-' . $order_date->format('Ymd') . '-' . $order['id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        /* Thermal Printer Receipt Styles */
        @media print {
            @page {
                size: 80mm auto; /* Set page width for thermal printer */
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                font-size: 10pt;
            }
            .btn-print {
                display: none; /* Hide print button when printing */
            }
        }
        body {
            font-family: "Courier New", Courier, monospace;
            margin: 0;
            padding: 10px;
            max-width: 80mm;
            margin: auto;
            background: #fff;
            color: #000;
        }
        .receipt {
            width: 100%;
            padding: 5px;
            text-align: center;
        }
        .receipt-header {
            margin-bottom: 10px;
        }
        .receipt-header h1 {
            margin: 0;
            font-size: 16pt;
        }
        .receipt-header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .receipt-details {
            margin: 10px 0;
            text-align: left;
            width: 100%;
        }
        .receipt-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-details th, .receipt-details td {
            padding: 3px;
            border-bottom: 1px dashed #000;
            font-size: 10pt;
        }
        .receipt-details th {
            text-align: left;
        }
        .receipt-footer {
            margin-top: 10px;
            text-align: left;
            font-size: 10pt;
        }
        .receipt-footer p {
            margin: 3px 0;
        }
        .btn-print {
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px;
            width: 100%;
            cursor: pointer;
            font-size: 12pt;
        }
        .btn-print:hover {
            background-color: #45a049;
        }


        /* Style for logo image (for thermal printer) */
.logo img {
    width: 200px;   /* Adjust width to fit the thermal printer format */
    height: auto;   /* Maintain aspect ratio */
    max-width: 100%; /* Ensure the image doesn’t overflow */
    object-fit: contain; /* Ensure the image scales down without distorting */
}

    </style>
</head>
<body>
<div class="receipt">
    <div class="receipt-header">
    <div class="logo">

                    <img src="../images/logo.png" alt="Restaurant Logo" class="img-responsive">
            </div>
        <h1>RoadSide Cafe</h1>
        <p>Kamrabad, Sarishabari</p>
        <p>Order Receipt</p>
    </div>

    <div class="receipt-details">
        <h3>Order Details</h3>
        <table>
            <tr>
                <th>Food</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item['food_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>TK <?php echo $item['price']; ?></td>
                    <td>TK <?php echo $item['total']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="receipt-footer">
        <p><strong>Token Number:</strong> <?php echo $token_number; ?></p>
        <p><strong>Order Date:</strong> <?php echo $order_date->format('d-m-Y'); ?></p>
        <p><strong>Order Time:</strong> <?php echo $order_date->format('H:i:s'); ?></p>
        <p><strong>Customer:</strong> <?php echo $order['name']; ?></p>
        <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
        <p><strong>Delivery Option:</strong> <?php echo $order['delivery_option']; ?></p>
        <p><strong>Delivery Charge:</strong> TK <?php echo $delivery_charge; ?></p>
        <p><strong>Total Price:</strong> TK <?php echo $total_price; ?></p>
    </div>

    <button class="btn-print" onclick="window.print()">Print Receipt</button>
</div>
</body>
</html>
