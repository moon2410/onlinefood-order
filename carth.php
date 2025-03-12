<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('partials-front/menu.php');

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize the cart as an empty array if not set
}

// Handle add to cart action
if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];

    // Fetch food details from the database
    $sql = "SELECT * FROM tbl_food WHERE id='$food_id' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($res == TRUE && mysqli_num_rows($res) > 0) {
        $food = mysqli_fetch_assoc($res);

        // Add item to the cart
        $_SESSION['cart'][] = [
            'food_id' => $food['id'],
            'title' => $food['title'],
            'price' => floatval($food['price']), // Ensure price is treated as a float
            'quantity' => 1,  // Default quantity is 1
        ];
    }

    header('location: carth.php');
    exit();
}

?>

<!-- Cart Page Content -->
<div class="main-content">
    <div class="wrapper">
        <h1>Your Cart</h1>

        <?php
        // Display cart contents only if the cart exists and has items
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            echo "<form action='' method='POST'>";
            echo "<table class='tbl-full'>";
            echo "<tr>
                    <th>Food Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                  </tr>";

            $total_price = 0;

            foreach ($_SESSION['cart'] as $index => $item) {
                // Ensure price and quantity are valid numeric values
                if (isset($item['price']) && isset($item['quantity'])) {
                    $price = floatval($item['price']);
                    $quantity = intval($item['quantity']);

                    // Calculate the total price for this item
                    $item_total = $price * $quantity;
                    $total_price += $item_total;

                    echo "<tr id='cart-item-{$index}'>
                            <td>{$item['title']}</td>
                            <td>TK {$price}</td>
                            <td>
                                <input type='number' name='cart[{$index}][quantity]' value='{$quantity}' min='1' class='quantity' data-index='{$index}' onkeyup='updateTotalPrice()'>
                            </td>
                            <td class='item-total'>TK {$item_total}</td>
                            <td>
                                <button type='button' class='delete-item' data-index='{$index}'>Delete</button>
                            </td>
                          </tr>";
                }
            }

            echo "</table>";
            echo "<h3>Total: TK <span id='total-price'>{$total_price}</span></h3>";
            echo "</form>";

            // Checkout Button
            echo "<a href='checkouth.php' class='btn-primary'>Proceed to Checkout</a>";
        } else {
            echo "<p> <br> Your cart is empty. <a href='category-foodsh.php'>Continue Order</a>.</p>";
        }
        ?>
    </div>
</div>

<script>
    // Update the total price when quantity is changed
    function updateTotalPrice() {
        let total = 0;
        document.querySelectorAll('.tbl-full tr').forEach(function(row, index) {
            if (index > 0) {
                const quantity = row.querySelector('.quantity').value;
                const price = parseFloat(row.querySelector('td:nth-child(2)').innerText.replace('TK', ''));
                const itemTotal = quantity * price;
                row.querySelector('.item-total').innerText = 'TK' + itemTotal.toFixed(2);
                total += itemTotal;
            }
        });
        document.getElementById('total-price').innerText = total.toFixed(2);

        // Send the updated quantities to the backend (AJAX request)
        const formData = new FormData();
        document.querySelectorAll('.quantity').forEach(function(input, index) {
            formData.append('cart[' + index + '][quantity]', input.value);
        });

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update-cart-quantity.php", true);
        xhr.send(formData);
    }

    // Delete item from cart
    document.querySelectorAll('.delete-item').forEach(function(button) {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete-cart-item.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // On success, remove the item from the table and update the cart
                    document.getElementById('cart-item-' + index).remove();
                    updateTotalPrice();
                    updateCartCount(); // Update cart count in the header
                }
            };
            xhr.send("index=" + index); // Send the index to delete
        });
    });

    // Update cart count in the menu
    function updateCartCount() {
        // AJAX to get the cart count
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "get-cart-count.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('cart-count').innerText = xhr.responseText;
            }
        };
        xhr.send();
    }

    updateCartCount(); // Call it on page load
</script>

<?php include('partials-front/footer.php'); ?>
