<?php include('partials-front/header.php'); ?>

<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <form action="<?php echo SITEURL; ?>food-search.php" method="POST" id="search-form">
            <input type="search" name="search" placeholder="Search Foods" id="search-input" required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
        <div id="live-search-results"></div>
    </div>
</section>
<!-- fOOD sEARCH Section Ends Here -->

<!-- fOOD Menu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 
            // Display Foods that are Active
            $sql = "SELECT * FROM tbl_food WHERE active='Yes'";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);

            if ($count > 0) {
                // Foods Available
                while ($row = mysqli_fetch_assoc($res)) {
                    // Get the Values
                    $id = $row['id'];
                    $title = $row['title'];
                    $description = $row['description'];
                    $price = $row['price'];
                    $image_name = $row['image_name'];
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
                <p class="food-price">BDT <?php echo $price; ?></p>
                <p class="food-detail"><?php echo $description; ?></p>
                <br>

                <!-- Add to Cart Button -->
                <a href="javascript:void(0);" class="btn btn-primary add-to-cart" data-food-id="<?php echo $id; ?>">Add to Cart</a>
            </div>
        </div>

        <?php
                }
            } else {
                echo "<div class='error'>Food not found.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- fOOD Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>

<!-- JS to handle Add to Cart functionality -->
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
