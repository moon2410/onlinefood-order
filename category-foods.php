<?php include('partials-front/menu.php'); ?>

<?php 
    // Check if user is logged in
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header('location: login.php'); // Redirect to login page if not logged in
    }

    // Get category ID
    if (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];

        // Get category title
        $sql = "SELECT title FROM tbl_category WHERE id=$category_id";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        $category_title = $row['title'];
    } else {
        header('location: user.php'); // Redirect to home page if category is not passed
    }
?>

<!-- Food Search Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <h2><a href="#" class="text-white">Foods on "<?php echo $category_title; ?>"</a></h2>
    </div>
</section>
<!-- Food Search Section Ends Here -->

<!-- Food Menu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 
            // Fetch foods based on category ID
            $sql2 = "SELECT * FROM tbl_food WHERE category_id=$category_id";
            $res2 = mysqli_query($conn, $sql2);
            $count2 = mysqli_num_rows($res2);

            if ($count2 > 0) {
                while ($row2 = mysqli_fetch_assoc($res2)) {
                    $id = $row2['id'];
                    $title = $row2['title'];
                    $price = $row2['price'];
                    $description = $row2['description'];
                    $image_name = $row2['image_name'];
        ?>

        <div class="food-menu-box">
            <div class="food-menu-img">
                <?php 
                    if ($image_name == "") {
                        echo "<div class='error'>Image not Available.</div>";
                    } else {
                        echo "<img src='".SITEURL."images/food/".$image_name."' alt='$title' class='img-responsive img-curve'>";
                    }
                ?>
            </div>

            <div class="food-menu-desc">
                <h4><?php echo $title; ?></h4>
                <p class="food-price">TK <?php echo $price; ?></p>
                <p class="food-detail"><?php echo $description; ?></p>
                <br>

                <!-- Add to Cart Button -->
                <a href="javascript:void(0);" class="btn btn-primary add-to-cart" data-food-id="<?php echo $id; ?>">Add to Cart</a>
            </div>
        </div>

        <?php
                }
            } else {
                echo "<div class='error'>Food not Available.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- Food Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>

<script>
    // Handle Add to Cart button click event
    // Handle Add to Cart button click event
document.querySelectorAll('.add-to-cart').forEach(function(button) {
    button.addEventListener('click', function() {
        var foodId = this.getAttribute('data-food-id'); // Get the food ID

        // Send an AJAX request to add the item to the cart
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?php echo SITEURL; ?>add_to_cart.php?food_id=' + foodId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText); // Log the response for debugging

                // Check if cart count exists and update dynamically
                var cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    cartCountElement.innerText = xhr.responseText; // Update cart count
                }

                // Show success message
                var message = document.createElement("div");
                message.classList.add("alert", "alert-success");
                message.innerText = "Item added to cart!";

                // Append the message to the body and make it visible
                document.body.appendChild(message);

                // Add an animation class to make it slide down smoothly
                message.classList.add("slide-in");

                // Hide the success message after 3 seconds
                setTimeout(function() {
                    message.classList.add("slide-out");
                }, 3000);

                // Remove the message after the animation is done
                setTimeout(function() {
                    message.remove();
                }, 3500);
            } else if (xhr.readyState == 4) {
                // Handle errors
                console.error('Error adding to cart: ' + xhr.status);
            }
        };
        xhr.send();
    });
});

</script>

<!-- CSS for Success Message -->
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
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.alert.slide-in {
    opacity: 1;
    animation: slideIn 0.5s forwards;
}

.alert.slide-out {
    opacity: 0;
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
