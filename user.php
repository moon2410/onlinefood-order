<?php 
    // Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Include header and database connection
    include('partials-front/header.php'); 

    // Redirect to login if user is not logged in
    if (!isset($_SESSION['user'])) {
        header('location: login.php'); // Redirect to login page if not logged in
        exit();
    }

    // Get logged-in user's ID
    $user_id = $_SESSION['user']['id'];

    // Fetch user data from the database
    $sql = "SELECT * FROM tbl_user WHERE id='$user_id' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($res);
?>

<!-- User Profile and Menu Section -->
<section class="user-profile">
    <div class="container">
        <h2 class="text-center">Welcome, <?php echo $user['name']; ?></h2>
    </div>
</section>



<?php
// Fetch the blog settings from the database
$sql_settings = "SELECT * FROM tbl_blog_settings WHERE id = 1";
$res_settings = mysqli_query($conn, $sql_settings);
$settings = mysqli_fetch_assoc($res_settings);

// Check if the blog section is visible
if ($settings['is_visible'] == 'Yes') {
    ?>
    <section class="blogs">
        <div class="container">
            <h2 class="text-center"><?php echo $settings['header_text']; ?></h2>

            <?php 
                // Fetch active blogs
                $sql_blog = "SELECT * FROM tbl_blog WHERE status='Active' LIMIT 3";
                $res_blog = mysqli_query($conn, $sql_blog);
                while ($row_blog = mysqli_fetch_assoc($res_blog)) {
                    $image_name = $row_blog['image_name'];
            ?>

            <div class="blog-box">
                <?php 
                    if ($image_name != "") {
                        echo "<img src='".SITEURL."images/blog/".$image_name."' alt='Blog Image' class='img-responsive img-curve'>";
                    }
                ?>
                <h4><?php echo $row_blog['title']; ?></h4>
                <p><?php echo substr($row_blog['description'], 0, 150); ?>...</p>
                <a href="view-blog.php?id=<?php echo $row_blog['id']; ?>" class="btn btn-primary">Read More</a>
            </div>

            <?php
                }
            ?>
        </div>
    </section>
    <?php
}
?>






<?php
// Fetch the offer settings from the database
$sql_settings = "SELECT * FROM tbl_offer_settings WHERE id = 1";
$res_settings = mysqli_query($conn, $sql_settings);
$settings = mysqli_fetch_assoc($res_settings);

// Check if the offer section is visible
if ($settings['is_visible'] == 'Yes') {
    ?>
    <!-- Offer Section -->
    <section class="offer">
        <div class="container">
            <h2 class="text-center"><?php echo $settings['header_text']; ?></h2>

            <div class="offer-container">
                <?php 
                    // Fetch offers without status filtering
                    $sql_offer = "SELECT * FROM tbl_offer LIMIT 3";
                    $res_offer = mysqli_query($conn, $sql_offer);
                    while ($row_offer = mysqli_fetch_assoc($res_offer)) {
                        $image_name = $row_offer['image_name'];
                ?>

                <div class="offer-box">
                    <?php 
                        if ($image_name != "") {
                            echo "<img src='".SITEURL."images/offer/".$image_name."' alt='Offer Image' class='img-responsive img-curve'>";
                        }
                    ?>
                    <h4><?php echo $row_offer['title']; ?></h4>
                    <p><strike>$<?php echo $row_offer['original_price']; ?></strike> $<?php echo $row_offer['offer_price']; ?></p>
                    <p><?php echo isset($row_offer['description']) ? substr($row_offer['description'], 0, 150) : 'No description available'; ?>...</p>
                    <!-- Add to Cart Button -->
                    <a href="javascript:void(0);" class="btn btn-primary add-to-cart" data-food-id="<?php echo $row_offer['id']; ?>" data-price="<?php echo $row_offer['offer_price']; ?>">Add to Cart</a>
                </div>

                <?php
                    }
                ?>
            </div>
        </div>
    </section>
    <?php
}
?>




<!-- Food Menu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Feature Foods</h2>

        <?php 
            // Getting Foods from Database that are active and featured
            $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='Yes' LIMIT 6";
            $res2 = mysqli_query($conn, $sql2);
            $count2 = mysqli_num_rows($res2);

            if ($count2 > 0) {
                while ($row = mysqli_fetch_assoc($res2)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $description = $row['description'];
                    $image_name = $row['image_name'];
        ?>

        <div class="food-menu-box">
            <div class="food-menu-img">
                <?php 
                    if ($image_name == "") {
                        echo "<div class='error'>Image not available.</div>";
                    } else {
                        ?>
                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Food Item" class="img-responsive img-curve">
                        <?php
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
                echo "<div class='error'>Food not available.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- Food Menu Section Ends Here -->



<!-- Categories Section Starts Here -->
<section class="categories">
    <div class="container">
        <h2 class="text-center">Explore Food Categories</h2>

        <?php 
            // Fetch food categories from the database
            $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' ORDER BY id DESC LIMIT 30";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);

            if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $image_name = $row['image_name'];
        ?>

        <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
            <div class="box-3 float-container category-box">
                <?php 
                    if ($image_name == "") {
                        echo "<div class='error'>Image not Available</div>";
                    } else {
                        ?>
                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" alt="Pizza" class="img-responsive img-curve">
                        <?php
                    }
                ?>
                <h3 class="float-text text-white"><mark style="background-color:white;"><?php echo $title; ?></mark></h3>
            </div>
        </a>

        <?php
                }
            } else {
                echo "<div class='error'>Category not Added.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories Section Ends Here -->

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
<!-- JS for Categories Section Moving -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // Add hover effect to categories boxes
        $('.category-box').hover(function(){
            $(this).css('transform', 'scale(1.05)');
        }, function(){
            $(this).css('transform', 'scale(1)');
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







/* Offer Section Styling */
.offer {
    padding: 40px 20px;
    background-color: #f9f9f9;
}

.offer h2 {
    font-size: 32px;
    margin-bottom: 30px;
    color: #4CAF50;
}

/* Container for offer items */
.offer-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap; /* Allows wrapping on smaller screens */
}

.offer-box {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    transition: transform 0.3s ease-in-out;
    width: 30%; /* Default width for offer box */
}

.offer-box:hover {
    transform: scale(1.05); /* Slightly scale up on hover */
}

.offer-box img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
}

.offer-box h4 {
    font-size: 24px;
    color: #333;
    margin-top: 15px;
    margin-bottom: 10px;
}

.offer-box p {
    font-size: 16px;
    color: #555;
}

.offer-box p strike {
    color: red;
    margin-right: 10px;
    font-size: 18px;
    text-decoration: line-through;
}

.offer-box .btn-primary {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    text-decoration: none;
    margin-top: 15px;
}

.offer-box .btn-primary:hover {
    background-color: #45a049;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .offer-box {
        width: 48%; /* Adjust width for medium screens */
    }
}

@media (max-width: 480px) {
    .offer-box {
        width: 100%; /* Full width on small screens */
    }
}










</style>
